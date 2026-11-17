<?php
/* Include before HTML: $mass_data_result = kmdata_handle($mydb);
   Render inside section: kmdata_render($mass_data_result); */
// Load Composer dependencies from the Kalora project root.
$autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// Load this file after the database connection and before any HTML output.
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
if (!function_exists('kmdata_columns')) {
function kmdata_columns() {
 return ['product_id','root_id','root_name','brand_id','brand_name','sub_cate_id','sub_cate_name','product_name','product_slug','product_sku','product_color','product_size','product_image','product_description','meta_title','meta_description','meta_keywords','canonical_url','og_title','og_description','product_other_info_desc','original_price','sale_price','product_stock','discount_visibility','product_status','product_views','created_at','updated_at','product_img_1','product_img_1_alt','product_img_2','product_img_2_alt','product_img_3','product_img_3_alt','product_img_4','product_img_4_alt','product_img_5','product_img_5_alt','product_img_6','product_img_6_alt','product_img_7','product_img_7_alt','product_img_8','product_img_8_alt','product_img_9','product_img_9_alt','product_img_10','product_img_10_alt','product_brochure','product_video_1'];
}
function kmdata_editable() {return ['root_name','brand_name','sub_cate_name','root_id','brand_id','sub_cate_id','product_name','product_slug','product_sku','product_color','product_size','product_description','meta_title','meta_description','meta_keywords','canonical_url','og_title','og_description','product_other_info_desc','original_price','sale_price','product_stock','discount_visibility','product_status'];}
function kmdata_e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function kmdata_excel_col($n){$s='';for($n++;$n>0;$n=intdiv($n-1,26))$s=chr(65+(($n-1)%26)).$s;return $s;}
function kmdata_xml($s){return htmlspecialchars((string)$s,ENT_XML1|ENT_QUOTES,'UTF-8');}
function kmdata_sheet($rows,$cols){
 $x='<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><cols>';
 foreach($cols as $i=>$c){$width=in_array($c,['product_description','product_other_info_desc','meta_description','canonical_url'],true)?38:(in_array($c,['product_name','product_slug','root_name','brand_name','sub_cate_name'],true)?27:19);$x.='<col min="'.($i+1).'" max="'.($i+1).'" width="'.$width.'" customWidth="1"/>';}
 $x.='</cols><sheetData>';
 foreach($rows as $ri=>$row){$x.='<row r="'.($ri+1).'"'.($ri===0?' ht="30" customHeight="1"':'').'>';
 foreach($cols as $ci=>$c){$val=(string)($row[$c]??'');$style=$ri===0?1:($c==='product_id'?2:(in_array($c,kmdata_editable(),true)?3:4));$cell=kmdata_excel_col($ci).($ri+1);
 // Always write strings to preserve exact IDs, slugs and long text.
 $x.='<c r="'.$cell.'" s="'.$style.'" t="inlineStr"><is><t xml:space="preserve">'.kmdata_xml($ri===0?$c:$val).'</t></is></c>';
 }$x.='</row>';}
 $last=kmdata_excel_col(count($cols)-1);$x.='</sheetData><autoFilter ref="A1:'.$last.count($rows).'"/></worksheet>';return $x;
}
function kmdata_spreadsheet_boot() {
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) return;
    $paths = [
        dirname(__DIR__, 2) . '/vendor/autoload.php',
        dirname(__DIR__, 2) . '/admin_access/vendor/autoload.php',
        __DIR__ . '/vendor/autoload.php'
    ];
    foreach ($paths as $path) {
        if (is_file($path)) { require_once $path; break; }
    }
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        throw new RuntimeException('PhpSpreadsheet library missing. Project root mein composer require phpoffice/phpspreadsheet run karo aur vendor folder server par upload karo.');
    }
}
function kmdata_category_records($db, $table, $idField, $nameField) {
    $statusField = null;
    $cols = mysqli_query($db, 'SHOW COLUMNS FROM `'.$table.'`');
    if (!$cols) throw new RuntimeException('Cannot inspect '.$table.': '.mysqli_error($db));
    $all = [];
    while ($c = mysqli_fetch_assoc($cols)) $all[] = $c['Field'];
    mysqli_free_result($cols);
    foreach ([$table === 'root_categories' ? 'root_status' : ($table === 'brands' ? 'brand_status' : 'sub_cate_status'), 'status', 'category_status', 'is_active'] as $candidate) {
        if (in_array($candidate, $all, true)) { $statusField = $candidate; break; }
    }
    if (!$statusField) throw new RuntimeException('Active status column not found in '.$table.'. Check table schema.');
    $sql = 'SELECT `'.$idField.'` AS ref_id, `'.$nameField.'` AS ref_name, `'.$statusField.'` AS ref_status FROM `'.$table.'` ORDER BY `'.$nameField.'`, `'.$idField.'`';
    $q = mysqli_query($db, $sql);
    if (!$q) throw new RuntimeException(mysqli_error($db));
    $out = [];
    while ($r = mysqli_fetch_assoc($q)) {
        $status = strtolower(trim((string)$r['ref_status']));
        $r['active'] = in_array($status, ['active','1','yes','enabled'], true);
        $out[] = $r;
    }
    mysqli_free_result($q);
    return $out;
}
function kmdata_category_sets($db) {
    return [
      'root' => kmdata_category_records($db,'root_categories','root_id','root_name'),
      'brand' => kmdata_category_records($db,'brands','brand_id','brand_name'),
      'sub' => kmdata_category_records($db,'product_sub_cate','sub_cate_id','sub_cate_name')
    ];
}
function kmdata_category_match($list, $name, $id) {
    $name = trim((string)$name); $id = trim((string)$id);
    $matches = [];
    foreach ($list as $r) if (trim((string)$r['ref_name']) === $name) $matches[] = $r;
    if (count($matches) !== 1) return [null, count($matches) ? 'Ambiguous category name: '.$name : 'Unknown category name: '.$name];
    $ref = $matches[0];
    if ($id !== '' && (!ctype_digit($id) || (int)$id !== (int)$ref['ref_id'])) return [null, 'Category name/ID mismatch: '.$name];
    return [$ref, ''];
}
function kmdata_validate_category_row($d, $old, $sets) {
    foreach (['root'=>['root_name','root_id'],'brand'=>['brand_name','brand_id'],'sub'=>['sub_cate_name','sub_cate_id']] as $type=>$fields) {
        [$ref,$error] = kmdata_category_match($sets[$type], $d[$fields[0]], $d[$fields[1]]);
        if ($error) return $error;
        $d[$fields[1]] = (string)$ref['ref_id'];
        if (!$ref['active'] && (!$old || (string)$old[$fields[1]] !== $d[$fields[1]])) return 'Inactive '.$type.' cannot be assigned: '.$ref['ref_name'];
    }
    return '';
}
function kmdata_add_validation($sheet, $cell, $rangeName) {
    $v = $sheet->getCell($cell)->getDataValidation();
    $v->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
    $v->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
    $v->setAllowBlank(false);
    $v->setShowInputMessage(true);
    $v->setPromptTitle('Active options');
    $v->setPrompt('Select from dropdown. ID fills automatically.');
    $v->setShowErrorMessage(true);
    $v->setError('Select an active name from the dropdown.');
    $v->setShowDropDown(true);
    $v->setFormula1('='.$rangeName);
}
function kmdata_export($db) {
    if (!($db instanceof mysqli)) throw new RuntimeException('Database connection $mydb missing.');
    kmdata_spreadsheet_boot();
    $sql = 'SELECT products.*, root_categories.root_name, brands.brand_name, product_sub_cate.sub_cate_name, products_images.* FROM products INNER JOIN root_categories ON products.root_id = root_categories.root_id INNER JOIN brands ON products.brand_id = brands.brand_id INNER JOIN product_sub_cate ON products.sub_cate_id = product_sub_cate.sub_cate_id INNER JOIN products_images ON products.product_id = products_images.product_id ORDER BY products.product_id DESC';
    $q = mysqli_query($db, $sql);
    if (!$q) throw new RuntimeException('Export SQL error: '.mysqli_error($db));
    $sets = kmdata_category_sets($db);
    $cols = kmdata_columns();
    $book = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $book->getActiveSheet();
    $sheet->setTitle('Products');
    $refSheet = $book->createSheet();
    $refSheet->setTitle('Category Reference');
    $categoryConfig = [
        'root' => ['activeCol'=>'A','allNameCol'=>'B','allIdCol'=>'C','label'=>'ROOT CATEGORY'],
        'brand'=> ['activeCol'=>'E','allNameCol'=>'F','allIdCol'=>'G','label'=>'BRAND'],
        'sub'  => ['activeCol'=>'I','allNameCol'=>'J','allIdCol'=>'K','label'=>'SUB CATEGORY']
    ];
    foreach ($categoryConfig as $type=>$cfg) {
        $refSheet->setCellValue($cfg['activeCol'].'1', $cfg['label'].' — ACTIVE');
        $refSheet->setCellValue($cfg['allNameCol'].'1', $cfg['label'].' — ALL NAMES');
        $refSheet->setCellValue($cfg['allIdCol'].'1', 'DATABASE ID');
        $a = 2; $b = 2;
        foreach ($sets[$type] as $rec) {
            if ($rec['active']) $refSheet->setCellValueExplicit($cfg['activeCol'].$a++, (string)$rec['ref_name'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $refSheet->setCellValueExplicit($cfg['allNameCol'].$b, (string)$rec['ref_name'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $refSheet->setCellValue($cfg['allIdCol'].$b, (int)$rec['ref_id']);
            $b++;
        }
        if ($a === 2) throw new RuntimeException('No active '.$type.' options found.');
        $book->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('KM_'.strtoupper($type).'_ACTIVE', $refSheet, '$'.$cfg['activeCol'].'$2:$'.$cfg['activeCol'].'$'.($a-1)));
        $book->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('KM_'.strtoupper($type).'_ALL_NAMES', $refSheet, '$'.$cfg['allNameCol'].'$2:$'.$cfg['allNameCol'].'$'.max(2,$b-1)));
        $book->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('KM_'.strtoupper($type).'_ALL_IDS', $refSheet, '$'.$cfg['allIdCol'].'$2:$'.$cfg['allIdCol'].'$'.max(2,$b-1)));
        foreach ([$cfg['activeCol'],$cfg['allNameCol'],$cfg['allIdCol']] as $letter) {
            $refSheet->getColumnDimension($letter)->setWidth($letter === $cfg['allIdCol'] ? 17 : 34);
            $refSheet->getStyle($letter.'1')->applyFromArray(['font'=>['bold'=>true,'color'=>['rgb'=>'FFFFFF']], 'fill'=>['fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=>['rgb'=>'166B63']]]);
        }
    }
    $refSheet->freezePane('A2');
    $refSheet->getTabColor()->setRGB('CDA657');
    $sheet->fromArray($cols, null, 'A1');
    $editable = kmdata_editable();
    $row = 2;
    while ($item = mysqli_fetch_assoc($q)) {
        foreach ($cols as $i => $col) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . $row;
            $sheet->setCellValueExplicit($cell, (string)($item[$col] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
        $row++;
    }
    mysqli_free_result($q);
    // Auto-generate slug in Excel when product_name changes.
    // Existing rows retain their original slug until their name is edited.
    // New rows calculate a slug as soon as a product name is entered.
    $nameLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(array_search('product_name', $cols, true) + 1);
    $slugLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(array_search('product_slug', $cols, true) + 1);
    $existingRows = $row - 2;
    $slugInputEnd = min(2001, max(202, $row + 200));
    for ($sr = 2; $sr <= $slugInputEnd; $sr++) {
        $nameRef = $nameLetter . $sr;
        $normalized = 'LOWER(TRIM(' . $nameRef . '))';
        foreach (["'", '"', '.', ',', ':', ';', '!', '?', '(', ')', '[', ']', '/', '&', '+', '_'] as $remove) {
            $normalized = 'SUBSTITUTE(' . $normalized . ',"' . str_replace('"', '""', $remove) . '"," ")';
        }
        $normalized = 'SUBSTITUTE(' . $normalized . '," ","-")';
        $slugFormula = 'IF(' . $nameRef . '= "","",' . $normalized . ')';
        if ($sr < $row) {
            $oldName = (string) $sheet->getCell($nameRef)->getValue();
            $oldSlug = (string) $sheet->getCell($slugLetter . $sr)->getValue();
            $oldName = str_replace('"', '""', $oldName);
            $oldSlug = str_replace('"', '""', $oldSlug);
            $slugFormula = 'IF(' . $nameRef . '= "","",IF(' . $nameRef . '= "' . $oldName . '","' . $oldSlug . '",' . $normalized . '))';
        }
        $sheet->setCellValue($slugLetter . $sr, '=' . $slugFormula);
    }
    // Name dropdowns and formula IDs, including 200 blank rows for new products.
    $nameToId = ['root'=>['root_name','root_id'],'brand'=>['brand_name','brand_id'],'sub'=>['sub_cate_name','sub_cate_id']];
    $indexMap = array_flip($cols);
    $maxInputRow = min(2001, max(202, $row + 200));
    foreach ($nameToId as $type=>$pair) {
        $nameCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indexMap[$pair[0]]+1);
        $idCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indexMap[$pair[1]]+1);
        $prefix = 'KM_'.strtoupper($type);
        for ($r=2; $r<=$maxInputRow; $r++) {
            kmdata_add_validation($sheet, $nameCol.$r, $prefix.'_ACTIVE');
            $formula = '=IF('.$nameCol.$r.'="","",IFERROR(INDEX('.$prefix.'_ALL_IDS,MATCH('.$nameCol.$r.','.$prefix.'_ALL_NAMES,0)),""))';
            $sheet->setCellValue($idCol.$r, $formula);
        }
    }
    // One blank row for adding a new product. More rows can be added in Excel.
    $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($cols));
    $lastRow = max(2, $row);
    $sheet->freezePane('H2'); // Keep IDs and reference names visible while scrolling.
    $sheet->setAutoFilter('A1:'.$lastCol.$lastRow);
    $sheet->getSheetView()->setZoomScale(85);
    $sheet->getRowDimension(1)->setRowHeight(42);
    $sheet->getDefaultRowDimension()->setRowHeight(25);
    $sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray([
        'font'=>['bold'=>true,'color'=>['rgb'=>'FFFFFF'],'size'=>11,'name'=>'Aptos'],
        'fill'=>['fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=>['rgb'=>'142B49']],
        'alignment'=>['vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,'wrapText'=>true],
        'borders'=>['bottom'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,'color'=>['rgb'=>'39BBA6']]]
    ]);
    $wide = ['product_description','product_other_info_desc','meta_description','canonical_url','og_description'];
    $medium = ['product_name','product_slug','root_name','brand_name','sub_cate_name','meta_title','og_title','product_image'];
    foreach ($cols as $i=>$col) {
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i+1);
        $width = in_array($col,$wide,true) ? 43 : (in_array($col,$medium,true) ? 33 : (in_array($col,['product_id','root_id','brand_id','sub_cate_id','product_stock','product_views'],true) ? 16 : 22));
        $sheet->getColumnDimension($letter)->setWidth($width);
        $editableCol = in_array($col,$editable,true);
        $sheet->getStyle($letter.'2:'.$letter.$lastRow)->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle($letter.'1')->getFill()->getStartColor()->setRGB($col==='product_id' ? '855D20' : ($editableCol ? '166B63' : '142B49'));
        $sheet->getStyle($letter.'1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle($letter.'2:'.$letter.$lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        if (in_array($col,['product_name','product_description','product_other_info_desc'],true)) {
            $sheet->getStyle($letter.'2:'.$letter.$lastRow)->getAlignment()->setWrapText(true);
        }
    }
    // Alternating subtle row fills: green editable fields, slate reference fields.
    for ($r=2; $r<=$lastRow; $r++) {
        foreach ($cols as $i=>$col) {
            $letter=\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i+1);
            $editableCol=in_array($col,$editable,true);
            $fill = $col==='product_id' ? ($r%2 ? 'FFF1D6':'FFF6E6') : ($editableCol ? ($r%2 ? 'E8F6F0':'F2FBF7') : ($r%2 ? 'E9EFF6':'F5F8FC'));
            $sheet->getStyle($letter.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($fill);
        }
    }
    // Dropdowns for status columns; data stays as text to preserve the importer contract.
    foreach (['discount_visibility'=>['Show','Hide'],'product_status'=>['Active','Inactive']] as $field=>$options) {
        $index=array_search($field,$cols,true);
        if ($index===false) continue;
        $letter=\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index+1);
        for($r=2;$r<=max($lastRow+100,150);$r++) {
            $validation=$sheet->getCell($letter.$r)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowErrorMessage(true);
            $validation->setError('Please select a value from the dropdown.');
            $validation->setShowDropDown(true);
            $validation->setFormula1('"'.implode(',',$options).'"');
        }
    }
    $sheet->getTabColor()->setRGB('166B63');
    // CSV formula injection is prevented by writing all DB values explicitly as strings.
    $temp = tempnam(sys_get_temp_dir(), 'kalora_xlsx_');
    if ($temp === false) throw new RuntimeException('Cannot create temporary Excel file.');
    try {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book);
        $writer->save($temp);
        $book->disconnectWorksheets();
        if (!is_file($temp) || filesize($temp) < 100) throw new RuntimeException('Generated Excel is empty.');
        if (headers_sent($file,$line)) throw new RuntimeException('Headers already sent at '.$file.':'.$line.'. Handler HTML se pehle call karo.');
        // Prevent any previously buffered HTML/warnings from corrupting the XLSX.
        while (ob_get_level() > 0) { ob_end_clean(); }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="kalora_products_'.date('Ymd_His').'.xlsx"');
        header('Content-Length: '.filesize($temp));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Content-Type-Options: nosniff');
        readfile($temp);
    } finally { @unlink($temp); }
    exit;
}
function kmdata_parse($path) {
    kmdata_spreadsheet_boot();
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $book = $reader->load($path);
    try {
        $sheet = $book->getSheetByName('Products') ?: $book->getActiveSheet();
        $maxRow = $sheet->getHighestDataRow();
        if ($maxRow > 2001) throw new RuntimeException('Maximum 2000 product rows per import.');
        $head = $sheet->rangeToArray('A1:'.$sheet->getHighestDataColumn().'1', '', false, false, false)[0];
        $map = [];
        foreach ($head as $i=>$name) {
            $name = strtolower(trim((string)$name));
            if ($name !== '') $map[$name] = $i;
        }
        foreach (array_merge(['product_id'],kmdata_editable()) as $col) {
            if (!array_key_exists($col,$map)) throw new RuntimeException('Missing Excel column: '.$col);
        }
        $out = [];
        for ($r=2; $r<=$maxRow; $r++) {
            $vals = $sheet->rangeToArray('A'.$r.':'.$sheet->getHighestDataColumn().$r, '', true, false, false)[0];
            $d = [];
            foreach (kmdata_columns() as $col) $d[$col] = isset($map[$col]) ? trim((string)($vals[$map[$col]] ?? '')) : '';
            if (implode('', array_values($d)) === '') continue;
            $d['_row'] = $r;
            $out[] = $d;
        }
        return $out;
    } finally { $book->disconnectWorksheets(); }
}
function kmdata_slug_from_name($name) {
    $name = trim((string)$name);
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
        if ($converted !== false) $name = $converted;
    }
    $name = strtolower($name);
    $name = preg_replace('/[^a-z0-9]+/', '-', $name);
    return trim((string)$name, '-');
}
function kmdata_existing($db,$id){$s=mysqli_prepare($db,'SELECT * FROM products WHERE product_id=? LIMIT 1');mysqli_stmt_bind_param($s,'i',$id);mysqli_stmt_execute($s);$r=mysqli_stmt_result_metadata($s);$fields=[];$values=[];while($f=mysqli_fetch_field($r)){$fields[]=$f->name;$values[$f->name]=null;}$refs=[];foreach($fields as $f)$refs[]=&$values[$f];mysqli_stmt_bind_result($s,...$refs);$found=mysqli_stmt_fetch($s);mysqli_stmt_close($s);return $found?$values:null;}
function kmdata_ref($db,$table,$field,$id){$s=mysqli_prepare($db,"SELECT $field FROM $table WHERE $field=? LIMIT 1");mysqli_stmt_bind_param($s,'i',$id);mysqli_stmt_execute($s);mysqli_stmt_store_result($s);$ok=mysqli_stmt_num_rows($s)>0;mysqli_stmt_close($s);return $ok;}
function kmdata_check($db,$d,$old){
 if($d['product_name']===''||$d['product_slug']==='')return 'Product name and slug required';
 if(!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$d['product_slug']))return 'Slug lowercase letters, numbers, hyphens only';
 foreach(['root_id'=>['root_categories','root_id'],'brand_id'=>['brands','brand_id'],'sub_cate_id'=>['product_sub_cate','sub_cate_id']] as $c=>$ref){if(!ctype_digit($d[$c])||(int)$d[$c]<1||!kmdata_ref($db,$ref[0],$ref[1],(int)$d[$c]))return 'Invalid '.$c;}
 foreach(['original_price','sale_price'] as $c)if($d[$c]!==''&&(!is_numeric($d[$c])||(float)$d[$c]<0))return 'Invalid '.$c;
 if($d['product_stock']!==''&&(!ctype_digit($d['product_stock'])))return 'Invalid stock';
 if(!in_array($d['discount_visibility'],['Show','Hide'],true))return 'discount_visibility must be Show/Hide';
 if(!in_array($d['product_status'],['Active','Inactive'],true))return 'product_status must be Active/Inactive';
 $s=mysqli_prepare($db,'SELECT product_id FROM products WHERE product_slug=? AND product_id<>? LIMIT 1');$id=$old?(int)$old['product_id']:0;mysqli_stmt_bind_param($s,'si',$d['product_slug'],$id);mysqli_stmt_execute($s);mysqli_stmt_store_result($s);$duplicate=mysqli_stmt_num_rows($s)>0;mysqli_stmt_close($s);if($duplicate)return 'Duplicate product_slug';return '';
}
function kmdata_write($db,$d,$old){
 $fields=array_values(array_diff(kmdata_editable(), ['root_name','brand_name','sub_cate_name']));$vals=[];foreach($fields as $f)$vals[]=$d[$f];$types=str_repeat('s',count($fields));
 if($old){$changes=[];$changedVals=[];foreach($fields as $i=>$f){if((string)$old[$f]!==$vals[$i]){$changes[]="$f=?";$changedVals[]=$vals[$i];}}if(!$changes)return 'unchanged';$id=(int)$old['product_id'];$changedVals[]=$id;$s=mysqli_prepare($db,'UPDATE products SET '.implode(',',$changes).', updated_at='.time().' WHERE product_id=?');$t=str_repeat('s',count($changes)).'i';mysqli_stmt_bind_param($s,$t,...$changedVals);$ok=mysqli_stmt_execute($s);mysqli_stmt_close($s);if(!$ok)throw new RuntimeException('UPDATE failed');return 'updated';}
 $cols=array_merge($fields,['product_image','product_views','created_at','updated_at']);$vals=array_merge($vals,['',0,time(),0]);$types=str_repeat('s',count($vals));$s=mysqli_prepare($db,'INSERT INTO products ('.implode(',',$cols).') VALUES ('.implode(',',array_fill(0,count($cols),'?')).')');if(!$s)throw new RuntimeException('INSERT prepare failed: '.mysqli_error($db));mysqli_stmt_bind_param($s,$types,...$vals);$ok=mysqli_stmt_execute($s);$id=mysqli_insert_id($db);mysqli_stmt_close($s);if(!$ok)throw new RuntimeException('INSERT failed');
 // Ensure export INNER JOIN includes new product next time.
 $fields2=[];for($i=1;$i<=10;$i++){$fields2[]='product_img_'.$i;$fields2[]='product_img_'.$i.'_alt';}$fields2[]='product_brochure';$fields2[]='product_video_1';$s=mysqli_prepare($db,'INSERT INTO products_images (product_id,'.implode(',',$fields2).') VALUES (?,'.implode(',',array_fill(0,count($fields2),'?')).')');$empty=array_fill(0,count($fields2),'');mysqli_stmt_bind_param($s,'i'.str_repeat('s',count($empty)),$id,...$empty);if(!mysqli_stmt_execute($s)){mysqli_stmt_close($s);throw new RuntimeException('Images placeholder row failed');}mysqli_stmt_close($s);return 'added #'.$id;
}
function kmdata_handle($db){
 $result=['message'=>'','added'=>0,'updated'=>0,'unchanged'=>0,'errors'=>[]];
 if(session_status()===PHP_SESSION_ACTIVE && empty($_SESSION['kmdata_csrf'])) {
     $_SESSION['kmdata_csrf']=bin2hex(random_bytes(24));
 }
 if(($_SERVER['REQUEST_METHOD']??'GET')!=='POST')return $result;
 if(isset($_POST['kmdata_export'])) {
   if(empty($_SESSION['kmdata_csrf']) || !hash_equals((string)$_SESSION['kmdata_csrf'], (string)($_POST['kmdata_csrf']??''))) {
       $result['message']='Security token invalid. Page refresh karke try karo.';
       return $result;
   }
   try {
       kmdata_export($db);
   } catch (Throwable $e) {
       error_log('[Kalora Mass Excel Export] '.$e->getMessage());
       $result['message']='Excel download error: '.$e->getMessage();
       $result['errors'][]=$result['message'];
       return $result;
   }
 }
 if(!isset($_POST['kmdata_import']))return $result;
 if(empty($_SESSION['kmdata_csrf'])||!hash_equals($_SESSION['kmdata_csrf'],(string)($_POST['kmdata_csrf']??''))){$result['message']='Invalid security token';return $result;}
 if(!isset($_FILES['kmdata_excel'])||$_FILES['kmdata_excel']['error']!==UPLOAD_ERR_OK||strtolower(pathinfo($_FILES['kmdata_excel']['name'],PATHINFO_EXTENSION))!=='xlsx'||$_FILES['kmdata_excel']['size']>8*1024*1024){$result['message']='Valid XLSX upload required (max 8 MB).';return $result;}
 try{$rows=kmdata_parse($_FILES['kmdata_excel']['tmp_name']);$sets=kmdata_category_sets($db);$seen=[];$slugs=[];$work=[];foreach($rows as $d){if($d['product_slug']==='' && $d['product_name']!=='')$d['product_slug']=kmdata_slug_from_name($d['product_name']);$id=$d['product_id'];$old=null;$err='';if($id!==''){if(!ctype_digit($id)||(int)$id<1)$err='Invalid product_id';else {$id=(int)$id;if(isset($seen[$id]))$err='Duplicate product_id in Excel';else{$seen[$id]=true;$old=kmdata_existing($db,$id);if(!$old)$err='product_id not found; leave blank to add new';}}}
 if(!$err)$err=kmdata_validate_category_row($d,$old,$sets);if(!$err)$err=kmdata_check($db,$d,$old);if(!$err&&isset($slugs[$d['product_slug']]))$err='Duplicate slug in Excel';$slugs[$d['product_slug']]=true;
 if($err)$result['errors'][]='Row '.$d['_row'].': '.$err;else $work[]=[$d,$old];}
 if($result['errors']){$result['message']='Validation failed. No changes saved.';return $result;} if(!$work){$result['message']='Excel mein koi product row nahi mili.';return $result;}
 mysqli_begin_transaction($db);try{foreach($work as $item){$status=kmdata_write($db,$item[0],$item[1]);if(strpos($status,'added')===0)$result['added']++;else $result[$status]++;}mysqli_commit($db);$result['message']='Import complete.';}catch(Throwable $e){mysqli_rollback($db);$result['added']=$result['updated']=$result['unchanged']=0;$result['message']='Import rolled back: '.$e->getMessage();$result['errors'][]=$result['message'];}}
 catch(Throwable $e){$result['message']='Error: '.$e->getMessage();$result['errors'][]=$result['message'];}return $result;
}
function kmdata_render($result){if(session_status()===PHP_SESSION_ACTIVE&&empty($_SESSION['kmdata_csrf']))$_SESSION['kmdata_csrf']=bin2hex(random_bytes(24));$csrf=$_SESSION['kmdata_csrf']??'';?>
<style>
#contect_6_contect_box .kmd-wrap{max-width:1050px;margin:auto;color:#18263d;font-family:inherit}#contect_6_contect_box .kmd-title{font-size:clamp(23px,3vw,30px);font-weight:800;margin:0 0 7px}#contect_6_contect_box .kmd-sub{color:#63738b;margin:0 0 20px}#contect_6_contect_box .kmd-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}#contect_6_contect_box .kmd-card{background:#fff;border:1px solid #dfe7f1;border-radius:15px;padding:22px;box-shadow:0 6px 22px #17365d0a}#contect_6_contect_box .kmd-card h3{font-size:19px;margin:0 0 10px}#contect_6_contect_box .kmd-card p{font-size:14px;line-height:1.65;color:#63738b}#contect_6_contect_box .kmd-btn{display:inline-block;background:#173f80;border:0;border-radius:10px;color:white;font-weight:700;padding:13px 19px;cursor:pointer;margin-top:10px}#contect_6_contect_box .kmd-green{background:#16835d}#contect_6_contect_box .kmd-file{width:100%;padding:14px;border:2px dashed #b9cce5;border-radius:10px;box-sizing:border-box}#contect_6_contect_box .kmd-note{background:#edf5ff;color:#254776;border-radius:10px;padding:15px;margin:18px 0;line-height:1.7}#contect_6_contect_box .kmd-error{background:#fff1f0;color:#9b1c1c}#contect_6_contect_box .kmd-legend{display:flex;gap:12px;flex-wrap:wrap;font-size:13px}#contect_6_contect_box .kmd-chip{padding:7px 11px;border-radius:8px;background:#e2f0d9}#contect_6_contect_box .kmd-chip2{background:#f1f5f9}#contect_6_contect_box .kmd-results{margin-top:14px}#contect_6_contect_box .kmd-results li{margin:4px 0}@media(max-width:720px){#contect_6_contect_box .kmd-grid{grid-template-columns:1fr}#contect_6_contect_box .kmd-card{padding:17px}#contect_6_contect_box .kmd-btn{width:100%}}
</style>
<div class="kmd-wrap"><h2 class="kmd-title">Mass Upload Products — Data</h2><p class="kmd-sub">Existing products download karo, Excel edit karo aur dobara upload karke add/update karo.</p><div class="kmd-legend"><span class="kmd-chip">Green columns: editable · Select active category names from Excel dropdowns</span><span class="kmd-chip kmd-chip2">Grey columns: reference only</span></div><div class="kmd-note"><b>Important:</b> Existing <code>product_id</code> mat badlo. Naya product add karne ke liye <code>product_id</code> blank rakho. <code>product_sku</code> blank allowed hai. Root/Brand/Sub Category ke names dropdown se select karo; IDs automatically fill hongi. Image paths abhi update nahi honge.</div><div class="kmd-grid"><div class="kmd-card"><h3>1. Download Product Excel</h3><p>Database se existing products, category/brand/subcategory names aur gallery details ke saath formatted XLSX download karo.</p><form method="post"><input type="hidden" name="kmdata_csrf" value="<?=kmdata_e($csrf)?>"><button class="kmd-btn" type="submit" name="kmdata_export" value="1">Download Products Excel</button></form></div><div class="kmd-card"><h3>2. Upload Edited Excel</h3><p>Green columns edit karo. Existing IDs update hongi; blank ID wali rows naye products banayengi.</p><form method="post" enctype="multipart/form-data"><input type="hidden" name="kmdata_csrf" value="<?=kmdata_e($csrf)?>"><input class="kmd-file" type="file" name="kmdata_excel" accept=".xlsx" required><button class="kmd-btn kmd-green" type="submit" name="kmdata_import" value="1" onclick="return confirm('Excel ke existing products update aur new products add karne hain?')">Upload &amp; Save Changes</button></form></div></div><?php if($result['message']!==''):?><div class="kmd-note <?=!empty($result['errors'])?'kmd-error':''?>"><b><?=kmdata_e($result['message'])?></b><div class="kmd-results">Added: <?=intval($result['added'])?> · Updated: <?=intval($result['updated'])?> · Unchanged: <?=intval($result['unchanged'])?></div><?php if($result['errors']):?><ul class="kmd-results"><?php foreach(array_slice($result['errors'],0,30) as $err):?><li><?=kmdata_e($err)?></li><?php endforeach;?></ul><?php endif;?></div><?php endif;?></div>
<?php }
}
