<?php
/* Include before HTML: $mass_data_result = kmdata_handle($mydb);
   Render inside section: kmdata_render($mass_data_result); */
// Load this file after the database connection and before any HTML output.
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
if (!function_exists('kmdata_columns')) {
function kmdata_columns() {
 return ['product_id','root_id','root_name','brand_id','brand_name','sub_cate_id','sub_cate_name','product_name','product_slug','product_sku','product_color','product_size','product_image','product_description','meta_title','meta_description','meta_keywords','canonical_url','og_title','og_description','product_other_info_desc','original_price','sale_price','product_stock','discount_visibility','product_status','product_views','created_at','updated_at','product_img_1','product_img_1_alt','product_img_2','product_img_2_alt','product_img_3','product_img_3_alt','product_img_4','product_img_4_alt','product_img_5','product_img_5_alt','product_img_6','product_img_6_alt','product_img_7','product_img_7_alt','product_img_8','product_img_8_alt','product_img_9','product_img_9_alt','product_img_10','product_img_10_alt','product_brochure','product_video_1'];
}
function kmdata_editable() {return ['root_id','brand_id','sub_cate_id','product_name','product_slug','product_sku','product_color','product_size','product_description','meta_title','meta_description','meta_keywords','canonical_url','og_title','og_description','product_other_info_desc','original_price','sale_price','product_stock','discount_visibility','product_status'];}
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
function kmdata_export($db){
 if(!class_exists('ZipArchive'))throw new RuntimeException('PHP ZipArchive extension required.');
 $sql='SELECT products.*, root_categories.root_name, brands.brand_name, product_sub_cate.sub_cate_name, products_images.* FROM products INNER JOIN root_categories ON products.root_id=root_categories.root_id INNER JOIN brands ON products.brand_id=brands.brand_id INNER JOIN product_sub_cate ON products.sub_cate_id=product_sub_cate.sub_cate_id INNER JOIN products_images ON products.product_id=products_images.product_id ORDER BY products.product_id DESC';
 $q=mysqli_query($db,$sql);if(!$q)throw new RuntimeException('Export query failed: '.mysqli_error($db));
 $cols=kmdata_columns();$rows=[array_fill_keys($cols,'')];foreach($cols as $c)$rows[0][$c]=$c;
 while($r=mysqli_fetch_assoc($q))$rows[]=$r;
 // Blank template row, editable for creating a new product.
 $rows[]=array_fill_keys($cols,'');
 $tmp=tempnam(sys_get_temp_dir(),'kmdata_');
 if ($tmp === false) throw new RuntimeException('Temporary file create nahi ho rahi. Hosting temp directory permissions check karo.');$z=new ZipArchive();
 if ($z->open($tmp,ZipArchive::OVERWRITE)!==true) {
     @unlink($tmp);
     throw new RuntimeException('Cannot create XLSX. Temporary folder permissions check karo.');
 }
 $z->addFromString('[Content_Types].xml','<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');
 $z->addFromString('_rels/.rels','<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
 $z->addFromString('xl/workbook.xml','<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Products" sheetId="1" r:id="rId1"/></sheets></workbook>');
 $z->addFromString('xl/_rels/workbook.xml.rels','<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
 $z->addFromString('xl/styles.xml','<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="5"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF17365D"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFE2F0D9"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="5"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFill="1" applyFont="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" xfId="0" applyFill="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="0" xfId="0" applyFill="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" xfId="0" applyFill="1"/></cellXfs></styleSheet>');
 $z->addFromString('xl/worksheets/sheet1.xml',kmdata_sheet($rows,$cols));$z->close();
 if(headers_sent($sentFile,$sentLine)) {
     @unlink($tmp);
     throw new RuntimeException('Download headers already sent: '.$sentFile.' line '.$sentLine.'. kmdata_handle() ko HTML se pehle call karo.');
 }
 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');header('Content-Disposition: attachment; filename="kalora_products_'.date('Ymd_His').'.xlsx"');header('Content-Length: '.filesize($tmp));header('Cache-Control: no-store');readfile($tmp);unlink($tmp);exit;
}
function kmdata_parse($path){
 if(!class_exists('ZipArchive')||!function_exists('simplexml_load_string'))throw new RuntimeException('ZipArchive and SimpleXML required.');
 $z=new ZipArchive();if($z->open($path)!==true)throw new RuntimeException('Excel open failed.');
 try{$raw=$z->getFromName('xl/worksheets/sheet1.xml');if($raw===false||strlen($raw)>20*1024*1024)throw new RuntimeException('Worksheet missing or too large.');$shared=[];$ss=$z->getFromName('xl/sharedStrings.xml');if($ss!==false){$sx=@simplexml_load_string($ss);if(!$sx)throw new RuntimeException('Invalid shared strings.');foreach($sx->si as $si){$s='';foreach($si->xpath('.//*[local-name()="t"]') as $t)$s.=(string)$t;$shared[]=$s;}}
 $xml=@simplexml_load_string($raw);if(!$xml)throw new RuntimeException('Invalid worksheet.');$rows=[];foreach($xml->sheetData->row as $row){$vals=[];foreach($row->c as $c){preg_match('/^[A-Z]+/',(string)$c['r'],$m);if(!$m)continue;$idx=0;foreach(str_split($m[0]) as $ch)$idx=$idx*26+ord($ch)-64;$idx--; $type=(string)$c['t'];if($type==='s')$v=$shared[(int)$c->v]??'';elseif($type==='inlineStr'){$v='';foreach($c->xpath('.//*[local-name()="t"]') as $t)$v.=(string)$t;}else $v=(string)$c->v;$vals[$idx]=trim($v);}if($vals)$rows[]=$vals;if(count($rows)>2001)throw new RuntimeException('Maximum 2000 rows per upload.');}
 if(!$rows)throw new RuntimeException('Empty Excel.');$head=array_shift($rows);$map=[];foreach($head as $i=>$h){$h=trim(strtolower($h));if($h!=='')$map[$h]=$i;}foreach(array_merge(['product_id'],kmdata_editable()) as $c)if(!isset($map[$c]))throw new RuntimeException('Missing Excel header: '.$c);
 $out=[];foreach($rows as $i=>$vals){$d=[];foreach(kmdata_columns() as $c)$d[$c]=isset($map[$c])?(string)($vals[$map[$c]]??''):'';if(implode('',array_values($d))==='')continue;$d['_row']=$i+2;$out[]=$d;}return $out;
 }finally{$z->close();}
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
 $fields=kmdata_editable();$vals=[];foreach($fields as $f)$vals[]=$d[$f];$types=str_repeat('s',count($fields));
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
 try{$rows=kmdata_parse($_FILES['kmdata_excel']['tmp_name']);$seen=[];$slugs=[];$work=[];foreach($rows as $d){$id=$d['product_id'];$old=null;$err='';if($id!==''){if(!ctype_digit($id)||(int)$id<1)$err='Invalid product_id';else {$id=(int)$id;if(isset($seen[$id]))$err='Duplicate product_id in Excel';else{$seen[$id]=true;$old=kmdata_existing($db,$id);if(!$old)$err='product_id not found; leave blank to add new';}}}
 if(!$err)$err=kmdata_check($db,$d,$old);if(!$err&&isset($slugs[$d['product_slug']]))$err='Duplicate slug in Excel';$slugs[$d['product_slug']]=true;
 if($err)$result['errors'][]='Row '.$d['_row'].': '.$err;else $work[]=[$d,$old];}
 if($result['errors']){$result['message']='Validation failed. No changes saved.';return $result;} if(!$work){$result['message']='Excel mein koi product row nahi mili.';return $result;}
 mysqli_begin_transaction($db);try{foreach($work as $item){$status=kmdata_write($db,$item[0],$item[1]);if(strpos($status,'added')===0)$result['added']++;else $result[$status]++;}mysqli_commit($db);$result['message']='Import complete.';}catch(Throwable $e){mysqli_rollback($db);$result['added']=$result['updated']=$result['unchanged']=0;$result['message']='Import rolled back: '.$e->getMessage();$result['errors'][]=$result['message'];}}
 catch(Throwable $e){$result['message']='Error: '.$e->getMessage();$result['errors'][]=$result['message'];}return $result;
}
function kmdata_render($result){if(session_status()===PHP_SESSION_ACTIVE&&empty($_SESSION['kmdata_csrf']))$_SESSION['kmdata_csrf']=bin2hex(random_bytes(24));$csrf=$_SESSION['kmdata_csrf']??'';?>
<style>
#contect_6_contect_box .kmd-wrap{max-width:1050px;margin:auto;color:#18263d;font-family:inherit}#contect_6_contect_box .kmd-title{font-size:clamp(23px,3vw,30px);font-weight:800;margin:0 0 7px}#contect_6_contect_box .kmd-sub{color:#63738b;margin:0 0 20px}#contect_6_contect_box .kmd-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}#contect_6_contect_box .kmd-card{background:#fff;border:1px solid #dfe7f1;border-radius:15px;padding:22px;box-shadow:0 6px 22px #17365d0a}#contect_6_contect_box .kmd-card h3{font-size:19px;margin:0 0 10px}#contect_6_contect_box .kmd-card p{font-size:14px;line-height:1.65;color:#63738b}#contect_6_contect_box .kmd-btn{display:inline-block;background:#173f80;border:0;border-radius:10px;color:white;font-weight:700;padding:13px 19px;cursor:pointer;margin-top:10px}#contect_6_contect_box .kmd-green{background:#16835d}#contect_6_contect_box .kmd-file{width:100%;padding:14px;border:2px dashed #b9cce5;border-radius:10px;box-sizing:border-box}#contect_6_contect_box .kmd-note{background:#edf5ff;color:#254776;border-radius:10px;padding:15px;margin:18px 0;line-height:1.7}#contect_6_contect_box .kmd-error{background:#fff1f0;color:#9b1c1c}#contect_6_contect_box .kmd-legend{display:flex;gap:12px;flex-wrap:wrap;font-size:13px}#contect_6_contect_box .kmd-chip{padding:7px 11px;border-radius:8px;background:#e2f0d9}#contect_6_contect_box .kmd-chip2{background:#f1f5f9}#contect_6_contect_box .kmd-results{margin-top:14px}#contect_6_contect_box .kmd-results li{margin:4px 0}@media(max-width:720px){#contect_6_contect_box .kmd-grid{grid-template-columns:1fr}#contect_6_contect_box .kmd-card{padding:17px}#contect_6_contect_box .kmd-btn{width:100%}}
</style>
<div class="kmd-wrap"><h2 class="kmd-title">Mass Upload Products — Data</h2><p class="kmd-sub">Existing products download karo, Excel edit karo aur dobara upload karke add/update karo.</p><div class="kmd-legend"><span class="kmd-chip">Green columns: editable</span><span class="kmd-chip kmd-chip2">Grey columns: reference only</span></div><div class="kmd-note"><b>Important:</b> Existing <code>product_id</code> mat badlo. Naya product add karne ke liye <code>product_id</code> blank rakho. <code>product_sku</code> blank allowed hai. Image paths abhi update nahi honge.</div><div class="kmd-grid"><div class="kmd-card"><h3>1. Download Product Excel</h3><p>Database se existing products, category/brand/subcategory names aur gallery details ke saath formatted XLSX download karo.</p><form method="post"><input type="hidden" name="kmdata_csrf" value="<?=kmdata_e($csrf)?>"><button class="kmd-btn" type="submit" name="kmdata_export" value="1">Download Products Excel</button></form></div><div class="kmd-card"><h3>2. Upload Edited Excel</h3><p>Green columns edit karo. Existing IDs update hongi; blank ID wali rows naye products banayengi.</p><form method="post" enctype="multipart/form-data"><input type="hidden" name="kmdata_csrf" value="<?=kmdata_e($csrf)?>"><input class="kmd-file" type="file" name="kmdata_excel" accept=".xlsx" required><button class="kmd-btn kmd-green" type="submit" name="kmdata_import" value="1" onclick="return confirm('Excel ke existing products update aur new products add karne hain?')">Upload &amp; Save Changes</button></form></div></div><?php if($result['message']!==''):?><div class="kmd-note <?=!empty($result['errors'])?'kmd-error':''?>"><b><?=kmdata_e($result['message'])?></b><div class="kmd-results">Added: <?=intval($result['added'])?> · Updated: <?=intval($result['updated'])?> · Unchanged: <?=intval($result['unchanged'])?></div><?php if($result['errors']):?><ul class="kmd-results"><?php foreach(array_slice($result['errors'],0,30) as $err):?><li><?=kmdata_e($err)?></li><?php endforeach;?></ul><?php endif;?></div><?php endif;?></div>
<?php }
}
