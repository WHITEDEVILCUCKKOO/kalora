<?php
/**
 * Kalora ZIP image importer. Include in admin.php AFTER $mydb is connected,
 * BEFORE any HTML output. Call klzip_handle_x91($mydb) before HTML and
 * klzip_render_x91($result) in #contect_6_contect_box.
 * No changes to the existing products.php or database schema.
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) { session_start(); }

function klzip_product_exists_x91($db, $id) {
    $st = mysqli_prepare($db, 'SELECT product_id FROM products WHERE product_id = ? LIMIT 1');
    if (!$st) { throw new RuntimeException('Product lookup prepare failed'); }
    mysqli_stmt_bind_param($st, 'i', $id);
    mysqli_stmt_execute($st);
    mysqli_stmt_store_result($st);
    $found = mysqli_stmt_num_rows($st) === 1;
    mysqli_stmt_close($st);
    return $found;
}

function klzip_image_info_x91($bytes, $extension) {
    $info = @getimagesizefromstring($bytes);
    if (!$info || !isset($info[2])) { return false; }
    $types = ['jpg' => IMAGETYPE_JPEG, 'jpeg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'webp' => IMAGETYPE_WEBP, 'gif' => IMAGETYPE_GIF];
    return isset($types[$extension]) && $info[2] === $types[$extension];
}

function klzip_gallery_update_x91($db, $id, $paths) {
    $exists = false;
    $st = mysqli_prepare($db, 'SELECT product_id FROM products_images WHERE product_id = ? LIMIT 1');
    if (!$st) { throw new RuntimeException('Gallery lookup failed'); }
    mysqli_stmt_bind_param($st, 'i', $id);
    mysqli_stmt_execute($st);
    mysqli_stmt_store_result($st);
    $exists = mysqli_stmt_num_rows($st) > 0;
    mysqli_stmt_close($st);
    if (!$exists) {
        $st = mysqli_prepare($db, 'INSERT INTO products_images (product_id) VALUES (?)');
        if (!$st) { throw new RuntimeException('Gallery row insert failed'); }
        mysqli_stmt_bind_param($st, 'i', $id);
        if (!mysqli_stmt_execute($st)) { throw new RuntimeException('Gallery row insert failed'); }
        mysqli_stmt_close($st);
    }
    // Only update image path columns. Existing alt, brochure and video are untouched.
    $parts = []; $values = [];
    for ($i = 1; $i <= 10; $i++) {
        $parts[] = 'product_img_' . $i . ' = ?';
        $values[] = $paths[$i - 1] ?? '';
    }
    $sql = 'UPDATE products_images SET ' . implode(', ', $parts) . ' WHERE product_id = ?';
    $st = mysqli_prepare($db, $sql);
    if (!$st) { throw new RuntimeException('Gallery update prepare failed'); }
    $values[] = $id;
    mysqli_stmt_bind_param($st, str_repeat('s', 10) . 'i', ...$values);
    if (!mysqli_stmt_execute($st)) { throw new RuntimeException('Gallery update failed'); }
    mysqli_stmt_close($st);
}

function klzip_import_one_x91($db, $id, $items, $replaceGallery) {
    $root = dirname(__DIR__, 2); // file located admin_access/functions/
    $folder = $root . '/assets/products/product_' . $id;
    if (!is_dir($folder) && !mkdir($folder, 0755, true) && !is_dir($folder)) {
        throw new RuntimeException('Cannot create product folder');
    }
    $staged = []; $backups = []; $written = [];
    $galleryPaths = [];
    $mainPath = null;
    try {
        foreach ($items as $item) {
            $dest = $folder . '/' . $item['target'];
            $temp = tempnam($folder, '.klzip_');
            if ($temp === false || file_put_contents($temp, $item['bytes'], LOCK_EX) === false) {
                throw new RuntimeException('Could not stage image');
            }
            $staged[] = ['temp' => $temp, 'dest' => $dest, 'relative' => 'assets/products/product_' . $id . '/' . $item['target'], 'main' => $item['main']];
        }
        mysqli_begin_transaction($db);
        foreach ($staged as $item) {
            if (is_file($item['dest'])) {
                $backup = tempnam($folder, '.klbackup_');
                if ($backup === false || !rename($item['dest'], $backup)) { throw new RuntimeException('Could not backup existing image'); }
                $backups[] = ['backup' => $backup, 'dest' => $item['dest']];
            }
            if (!rename($item['temp'], $item['dest'])) { throw new RuntimeException('Could not save image'); }
            $written[] = $item['dest'];
            if ($item['main']) { $mainPath = $item['relative']; }
            else { $galleryPaths[] = $item['relative']; }
        }
        if ($mainPath !== null) {
            $st = mysqli_prepare($db, 'UPDATE products SET product_image = ? WHERE product_id = ?');
            if (!$st) { throw new RuntimeException('Main image update prepare failed'); }
            mysqli_stmt_bind_param($st, 'si', $mainPath, $id);
            if (!mysqli_stmt_execute($st)) { throw new RuntimeException('Main image update failed'); }
            mysqli_stmt_close($st);
        }
        if ($replaceGallery && count($galleryPaths) > 0) {
            klzip_gallery_update_x91($db, $id, $galleryPaths);
        }
        mysqli_commit($db);
        foreach ($backups as $backup) { @unlink($backup['backup']); }
        return ['main' => $mainPath !== null, 'gallery' => count($galleryPaths)];
    } catch (Throwable $e) {
        @mysqli_rollback($db);
        foreach ($written as $path) { @unlink($path); }
        foreach ($backups as $backup) { @rename($backup['backup'], $backup['dest']); }
        throw $e;
    } finally {
        foreach ($staged as $item) { if (is_file($item['temp'])) { @unlink($item['temp']); } }
    }
}

function klzip_progress_path_x91($token) {
    return sys_get_temp_dir() . '/kalora_zip_' . hash('sha256', $token) . '.json';
}
function klzip_progress_write_x91($token, $stage, $done, $total, $message) {
    if ($token === '') return;
    $data = json_encode(['stage'=>$stage,'done'=>(int)$done,'total'=>(int)$total,'message'=>$message,'updated'=>time()]);
    $path = klzip_progress_path_x91($token);
    $temp = tempnam(sys_get_temp_dir(), 'kz_');
    if ($temp !== false) {
        file_put_contents($temp, $data, LOCK_EX);
        @rename($temp, $path);
    }
}
function klzip_progress_read_x91() {
    if (!isset($_GET['klzip_progress_x91'])) return false;
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    $token = (string)$_GET['klzip_progress_x91'];
    if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['klzip_progress_token_x91']) || !hash_equals($_SESSION['klzip_progress_token_x91'], $token)) {
        http_response_code(403);
        echo json_encode(['error'=>'Unauthorized']);
        exit;
    }
    $path = klzip_progress_path_x91($token);
    echo is_file($path) ? file_get_contents($path) : json_encode(['stage'=>'waiting','done'=>0,'total'=>0,'message'=>'Waiting for server']);
    exit;
}

function klzip_handle_x91($db) {
    klzip_progress_read_x91();
    $result = ['ok' => [], 'errors' => []];
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { return $result; }
    if (!isset($_POST['klzip_upload_x91'])) {
        if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && empty($_POST) && empty($_FILES)) {
            $result['errors'][] = 'Upload exceeds PHP post_max_size; increase post_max_size and upload_max_filesize.';
        }
        return $result;
    }
    if (empty($_SESSION['klzip_csrf_x91']) || !hash_equals($_SESSION['klzip_csrf_x91'], (string)($_POST['klzip_csrf_x91'] ?? ''))) {
        $result['errors'][] = 'Security token expired. Refresh and try again.';
        return $result;
    }
    if (!class_exists('ZipArchive')) { $result['errors'][] = 'PHP ZIP extension is disabled.'; return $result; }
    if (!isset($_FILES['klzip_file_x91']) || $_FILES['klzip_file_x91']['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($_FILES['klzip_file_x91']['tmp_name'])) {
        $result['errors'][] = 'ZIP upload failed. Check PHP upload limits.';
        return $result;
    }
    $progressToken = (string)($_POST['klzip_progress_token_x91'] ?? '');
    if ($progressToken === '' || strlen($progressToken) > 128 || !preg_match('/^[a-f0-9]{32,128}$/', $progressToken)) {
        $result['errors'][] = 'Invalid progress token. Refresh and retry.';
        return $result;
    }
    $_SESSION['klzip_progress_token_x91'] = $progressToken;
    // Release PHP session lock so polling requests can run during import.
    if (session_status() === PHP_SESSION_ACTIVE) session_write_close();
    klzip_progress_write_x91($progressToken, 'scanning', 0, 0, 'Opening ZIP');
    $zip = new ZipArchive();
    if ($zip->open($_FILES['klzip_file_x91']['tmp_name']) !== true) {
        $result['errors'][] = 'Invalid ZIP file.';
        return $result;
    }
    $groups = []; $totalBytes = 0;
    klzip_progress_write_x91($progressToken, 'scanning', 0, $zip->numFiles, 'Checking ZIP images');
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    try {
        if ($zip->numFiles > 1500) { throw new RuntimeException('ZIP has too many files (max 1500).'); }
        for ($i = 0; $i < $zip->numFiles; $i++) {
            if ($i % 3 === 0) klzip_progress_write_x91($progressToken, 'scanning', $i, $zip->numFiles, 'Validating image ' . ($i + 1) . ' of ' . $zip->numFiles);
            $stat = $zip->statIndex($i);
            if (!$stat) { continue; }
            $name = str_replace('\\', '/', $stat['name']);
            if (str_ends_with($name, '/') || str_starts_with(basename($name), '.')) { continue; }
            $segments = explode('/', $name);
            if (in_array('..', $segments, true) || str_starts_with($name, '/')) { throw new RuntimeException('Unsafe ZIP path rejected.'); }
            // Accept product_ID/... or a single enclosing ZIP folder/product_ID/...
            $folderIndex = -1;
            foreach ($segments as $index => $segment) {
                if (preg_match('/^product_([1-9][0-9]*)$/i', $segment)) { $folderIndex = $index; break; }
            }
            if ($folderIndex < 0 || $folderIndex > 1 || count($segments) !== $folderIndex + 2) { continue; }
            preg_match('/^product_([1-9][0-9]*)$/i', $segments[$folderIndex], $match);
            $id = (int)$match[1];
            $filename = $segments[$folderIndex + 1];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) { continue; }
            $bytesSize = (int)($stat['size'] ?? 0);
            if ($bytesSize < 1 || $bytesSize > 12 * 1024 * 1024) { $result['errors'][] = "Product $id: image size invalid (max 12 MB)."; continue; }
            $totalBytes += $bytesSize;
            if ($totalBytes > 250 * 1024 * 1024) { throw new RuntimeException('ZIP uncompressed images exceed 250 MB.'); }
            $stream = $zip->getStream($stat['name']);
            if (!$stream) { $result['errors'][] = "Product $id: cannot read $filename"; continue; }
            $bytes = stream_get_contents($stream, 12 * 1024 * 1024 + 1);
            fclose($stream);
            if ($bytes === false || strlen($bytes) !== $bytesSize || !klzip_image_info_x91($bytes, $ext)) {
                $result['errors'][] = "Product $id: invalid image $filename";
                continue;
            }
            $base = pathinfo($filename, PATHINFO_FILENAME);
            $isMain = strcasecmp($base, 'main') === 0;
            $groups[$id][] = ['name' => $filename, 'ext' => $ext, 'main' => $isMain, 'bytes' => $bytes];
        }
        klzip_progress_write_x91($progressToken, 'scanning', $zip->numFiles, $zip->numFiles, 'ZIP images checked');
        $replaceGallery = isset($_POST['klzip_replace_gallery_x91']);
        ksort($groups, SORT_NUMERIC);
        $productCount = count($groups); $processedCount = 0;
        klzip_progress_write_x91($progressToken, 'saving', 0, $productCount, 'Saving product images');
        foreach ($groups as $id => $items) {
            try {
                if (!klzip_product_exists_x91($db, $id)) { throw new RuntimeException('Product ID does not exist in database'); }
                $mains = array_values(array_filter($items, fn($x) => $x['main']));
                if (count($mains) !== 1) { throw new RuntimeException('Exactly one main.* image required'); }
                $galleries = array_values(array_filter($items, fn($x) => !$x['main']));
                if (count($galleries) > 10) { throw new RuntimeException('Maximum 10 gallery images per product'); }
                usort($galleries, fn($a, $b) => strnatcasecmp($a['name'], $b['name']));
                $prepared = [['main' => true, 'target' => 'main.' . $mains[0]['ext'], 'bytes' => $mains[0]['bytes']]];
                foreach ($galleries as $index => $gallery) {
                    $prepared[] = ['main' => false, 'target' => 'gallery_' . ($index + 1) . '.' . $gallery['ext'], 'bytes' => $gallery['bytes']];
                }
                if (!$replaceGallery) { $prepared = array_slice($prepared, 0, 1); }
                $done = klzip_import_one_x91($db, $id, $prepared, $replaceGallery);
                $result['ok'][] = 'Product ' . $id . ': main updated' . ($replaceGallery ? ', gallery images ' . $done['gallery'] : ', gallery unchanged');
            } catch (Throwable $e) {
                error_log('[Kalora ZIP] Product ' . $id . ': ' . $e->getMessage());
                $result['errors'][] = 'Product ' . $id . ': ' . $e->getMessage();
            }
            $processedCount++;
            klzip_progress_write_x91($progressToken, 'saving', $processedCount, $productCount, 'Processed ' . $processedCount . ' of ' . $productCount . ' products');
        }
        if (!$groups && !$result['errors']) { $result['errors'][] = 'No product_ID image folders found in ZIP.'; }
    } catch (Throwable $e) { $result['errors'][] = $e->getMessage(); }
    $zip->close();
    klzip_progress_write_x91($progressToken, 'complete', count($result['ok']), count($result['ok']) + count($result['errors']), 'Finished: ' . count($result['ok']) . ' products updated, ' . count($result['errors']) . ' errors');
    return $result;
}

function klzip_render_x91($result) {
    if (empty($_SESSION['klzip_csrf_x91'])) { $_SESSION['klzip_csrf_x91'] = bin2hex(random_bytes(24)); }
    ?>
    <style>
      .klzip-panel-x91{max-width:80%;margin:20px auto;padding:26px;border:1px solid #dbe5ef;border-radius:16px;background:#fff;box-shadow:0 8px 28px rgba(18,41,84,.07);font-family:inherit}
      .klzip-title-x91{margin:0 0 7px;color:#122954;font-size:23px;font-weight:750}
      .klzip-muted-x91{color:#5b6b80;font-size:14px;line-height:1.65}
      .klzip-code-x91{background:#f4f7fb;border:1px solid #e4eaf2;border-radius:10px;padding:14px;white-space:pre-wrap;overflow-wrap:anywhere;font-size:13px;color:#18345c}
      .klzip-input-x91{width:100%;padding:14px;border:1px dashed #7f9bb8;border-radius:10px;background:#f8fbff;box-sizing:border-box;margin:12px 0}
      .klzip-btn-x91{border:0;border-radius:10px;background:#1b3d7b;color:white;font-weight:700;padding:13px 23px;cursor:pointer}
      .klzip-note-x91{margin:12px 0;color:#344861;font-size:14px}
      .klzip-msg-x91{padding:12px 14px;margin:10px 0;border-radius:9px;background:#f5f7fa;color:#273f59;font-size:14px}
      .klzip-error-x91{background:#fff1f0;color:#8a2424}
      .klzip-progress-wrap-x91{display:none;margin:18px 0;padding:15px;border:1px solid #dbe5ef;border-radius:10px;background:#f8fbff}
      .klzip-progress-head-x91{display:flex;justify-content:space-between;gap:12px;color:#122954;font-weight:700;margin-bottom:10px}
      .klzip-progress-track-x91{height:14px;background:#e0e8f4;border-radius:20px;overflow:hidden}
      .klzip-progress-fill-x91{height:100%;width:0;background:#168b70;transition:width .18s ease}
      .klzip-progress-detail-x91{margin:9px 0 0;color:#52627a;font-size:13px}
      .klzip-btn-x91:disabled{opacity:.55;cursor:not-allowed}
    </style>
    <section class="klzip-panel-x91">
      <h3 class="klzip-title-x91">Bulk Product Images — ZIP Upload</h3>
      <p class="klzip-muted-x91">Folder name must match an existing product ID. The main image can use any supported extension; gallery images can have any filename.</p>
      <pre class="klzip-code-x91">products_images.zip
├── product_101/
│   ├── main.webp
│   ├── front.jpg
│   └── back.png
└── product_102/
    ├── main.jpeg
    └── side.webp</pre>
      <?php foreach (($result['ok'] ?? []) as $msg): ?><div class="klzip-msg-x91"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div><?php endforeach; ?>
      <?php foreach (($result['errors'] ?? []) as $msg): ?><div class="klzip-msg-x91 klzip-error-x91"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div><?php endforeach; ?>
      <form id="klzip-form-x91" method="post" enctype="multipart/form-data">
        <input type="hidden" name="klzip_csrf_x91" value="<?php echo htmlspecialchars($_SESSION['klzip_csrf_x91'], ENT_QUOTES, 'UTF-8'); ?>">
        <label for="klzip_file_x91"><strong>Select images ZIP file</strong></label>
        <input class="klzip-input-x91" type="file" id="klzip_file_x91" name="klzip_file_x91" accept=".zip,application/zip" required>
        <label class="klzip-note-x91" style="display:block"><input type="checkbox" name="klzip_replace_gallery_x91" value="1" checked> Replace gallery slots 1–10 using ZIP gallery images (otherwise only main images are updated)</label>
        <p class="klzip-muted-x91">Allowed: JPG, JPEG, PNG, WEBP, GIF. Each product needs exactly one main.*. Old image files are not automatically deleted. Backup before uploading.</p>
        <button class="klzip-btn-x91" id="klzip-submit-x91" type="submit" name="klzip_upload_x91" value="1">Upload Product Images</button>
        <div class="klzip-progress-wrap-x91" id="klzip-progress-x91" role="status" aria-live="polite">
          <div class="klzip-progress-head-x91"><span id="klzip-stage-x91">Uploading ZIP</span><span id="klzip-percent-x91">0%</span></div>
          <div class="klzip-progress-track-x91"><div class="klzip-progress-fill-x91" id="klzip-fill-x91"></div></div>
          <p class="klzip-progress-detail-x91" id="klzip-detail-x91">Preparing upload...</p>
        </div>
      </form>
      <div id="klzip-ajax-result-x91"></div>
      <script>
      (function () {
        var form = document.getElementById('klzip-form-x91');
        if (!form || !window.XMLHttpRequest || !window.FormData) return;
        var button = document.getElementById('klzip-submit-x91');
        var panel = document.getElementById('klzip-progress-x91');
        var stage = document.getElementById('klzip-stage-x91');
        var percent = document.getElementById('klzip-percent-x91');
        var fill = document.getElementById('klzip-fill-x91');
        var detail = document.getElementById('klzip-detail-x91');
        var resultBox = document.getElementById('klzip-ajax-result-x91');
        function show(label, done, total, message) {
          var value = total > 0 ? Math.min(100, Math.round(done * 100 / total)) : 0;
          stage.textContent = label;
          percent.textContent = total > 0 ? value + '%' : 'Working...';
          fill.style.width = value + '%';
          detail.textContent = message;
        }
        function token() {
          var a = new Uint8Array(24);
          if (window.crypto && crypto.getRandomValues) crypto.getRandomValues(a);
          else for (var i = 0; i < a.length; i++) a[i] = Math.floor(Math.random() * 256);
          return Array.from(a).map(function(x){return x.toString(16).padStart(2,'0');}).join('');
        }
        form.addEventListener('submit', function (event) {
          event.preventDefault();
          if (!form.reportValidity()) return;
          var file = document.getElementById('klzip_file_x91').files[0];
          if (!file) return;
          button.disabled = true;
          panel.style.display = 'block';
          resultBox.textContent = '';
          show('Uploading ZIP', 0, file.size, 'Transferring ZIP to server');
          var progressToken = token();
          var data = new FormData(form);
          data.append('klzip_upload_x91', '1');
          data.append('klzip_progress_token_x91', progressToken);
          var xhr = new XMLHttpRequest();
          var poll = null;
          var startedProcessing = false;
          var endpoint = window.location.pathname + window.location.search;
          xhr.open('POST', endpoint, true);
          xhr.upload.onprogress = function (e) {
            if (e.lengthComputable && !startedProcessing) show('Uploading ZIP', e.loaded, e.total, 'Transferred ' + (e.loaded / 1048576).toFixed(1) + ' / ' + (e.total / 1048576).toFixed(1) + ' MB');
          };
          xhr.upload.onload = function () {
            startedProcessing = true;
            show('Server processing', 0, 0, 'ZIP transferred. Waiting for server progress...');
            poll = window.setInterval(function () {
              var req = new XMLHttpRequest();
              req.open('GET', window.location.pathname + '?klzip_progress_x91=' + encodeURIComponent(progressToken) + '&_=' + Date.now(), true);
              req.onload = function () {
                if (req.status !== 200) return;
                try {
                  var p = JSON.parse(req.responseText);
                  if (p.stage === 'scanning') show('Checking ZIP images', p.done, p.total, p.message);
                  else if (p.stage === 'saving') show('Saving products', p.done, p.total, p.message);
                  else if (p.stage === 'complete') show('Completed', 1, 1, p.message);
                } catch (ignore) {}
              };
              req.send();
            }, 650);
          };
          xhr.onload = function () {
            if (poll) clearInterval(poll);
            button.disabled = false;
            if (xhr.status >= 200 && xhr.status < 300) {
              // Existing PHP returns HTML with per-product success/error messages.
              var doc = new DOMParser().parseFromString(xhr.responseText, 'text/html');
              var messages = doc.querySelectorAll('.klzip-msg-x91');
              if (messages.length) {
                messages.forEach(function(node) {
                  var el = document.createElement('div');
                  el.className = node.className;
                  el.textContent = node.textContent;
                  resultBox.appendChild(el);
                });
                show('Completed', 1, 1, 'Processing finished. Review results below.');
              } else {
                show('Response received', 0, 0, 'No results found. Check PHP errors and upload limits.');
              }
            } else show('Upload failed', 0, 0, 'Server returned HTTP ' + xhr.status + '. Check PHP limits and error log.');
          };
          xhr.onerror = function () { if (poll) clearInterval(poll); button.disabled = false; show('Network error', 0, 0, 'Connection interrupted'); };
          xhr.send(data);
        });
      })();
      </script>
    </section>
    <?php
}
