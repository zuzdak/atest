<?php

define('ROOTPATH', __DIR__);

require_once('afunk_file.php');

if (isset($_GET['id'])){
	$in = $_GET['id'];
	if($in == 'in') saveSession('in');
}
$yn =0;
$af= array(0=>'x', 1=>'y');
$time_start = microtime(true);	
$data= (string) date('Y-m-d'); 
$kom = '';
 $con= false ;

/// ------------------  POST -------------------------
if (isset ($_POST['zap'])) {
 		$fName = $_FILES['afile']['name'];
  		$tmpName  = $_FILES['afile']['tmp_name'];
  		$fSize = $_FILES['afile']['size'];
		$fType =  $_FILES['afile']['type'] ;
		
// 	$fields = array(); 
	// **********               check filetype
	 if (ifPdf($fType)) {
	
		$ext = '';$fn = '';
	 	$ar = saveFile('src'); // *-----  arrray  filename , up = 0/1 
		$fn = $ar['filename']; // strtolower($str)
		if(isset($ar['ext'])) $ext = strtolower($ar['ext']);
		$kom = $fn.'--Rozmiar :'. round(($fSize/1000),0 ).'_kB__';
		$af= explode('.',$fn);
		$p=$_POST['p'];	
		$k=$_POST['k'];	
	
		$con = ($p>0 && $k>0 && ($k-$p)>=0);
		if($con) {

	 	 //  echo $k .'- '.$p.' = '.($k-$p);
			$yn = pdftk($ar,$p,$k);
		}
	}
 }


$time_end = microtime(true); 
$time = round(($time_end - $time_start),2 );


// include('header.html');
include('aformpdf.html');
echo '<p>';
if(isset($fName)) echo $fName ; echo '<br>';
echo  $kom;

echo '&nbsp czas : '. $time.'_sek</p>';
echo '<div class="ex8">';

$file = ROOTPATH.'/src/'.$af[0].'_o.pdf'; //  && $con
if (file_exists($file) ) {
	echo '<a target="__blank" href="afile.php?plik='.$af[0].'">POBIERZ PLIK</a>';
}elseif(isset($_POST['k'])) {
 echo '<h3 style="text-align:center;>ZŁE PARAMETRY lub BRAK PLIKU </h3>';	
}

echo '</div>';

include('footer.html');
?>
