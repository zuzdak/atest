<?php
//session_start();
// ob_start();
// error_reporting(E_ALL);
define('ROOTPATH', __DIR__);

require_once('afunk_file.php');

if (isset($_GET['id'])){
	$in = $_GET['id'];
	if($in == 'in') saveSession('in');
}

$time_start = microtime(true);	
$data= (string) date('Y-m-d'); 
$kom = '';

/// ------------------  POST -------------------------
if (isset ($_POST['zap'])) {
 		$fName = $_FILES['afile']['name'];
  		$tmpName  = $_FILES['afile']['tmp_name'];
  		$fSize = $_FILES['afile']['size'];
		$fType =  $_FILES['afile']['type'] ;
			
	$fields = array(); 
	// **********               check filetype
	if (ifImage($fType) || ifPdf($fType)) {
	
		$ext = '';$fn = '';
		$ar = saveFile('src'); // *-----  arrray  filename , up = 0/1 
		$fn = $ar['filename']; // strtolower($str)

		if(isset($ar['ext'])) $ext = strtolower($ar['ext']);
		$kom = $fn.'--Rozmiar :'. round(($fSize/1000),0 ).'_kB__';
				
		$buf = array();
//  *** ---------------------- IF PDF ---------------------------------------	 	
	 	if ($ext == 'pdf'){
	 	  $ar = pdftoppmPIC($ar);
	 	  $fnc = $ar['core'];
	 	  
	 	$a = array();
			if ($handle = opendir('./src/')) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != "..") {
				  if(strlen($entry) > 12 && substr($entry, 0,8) == $fnc )
			            $a[]= $entry;
			        }
			    }
			    closedir($handle);
			}
	 	$buf= array();
		foreach ($a as $k => $v){
			$b['filename'] = $v;    // as array
			$ar = tesseractTXT($b);          
			foreach ($ar as $v) $buf[] = $v;
		}			
	 	  	 	  
	 	}elseif ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'tif' ) {
	 	$buf = tesseractTXT($ar) ;
	 	}
	}
 }


$time_end = microtime(true); 
$time = round(($time_end - $time_start),2 );


include('header.html');
include('aform.html');
echo '<p>';
if(isset($fName)){
echo $fName ; echo '<br>'. $kom;
}
echo '&nbsp czas : '. $time.'_sek</p>';
echo '<div class="ex8">';
 if (isset ($buf) ) {
	foreach($buf as $v ) echo $v.'<br>';
}
echo '</div>';

include('footer.html');
?>
