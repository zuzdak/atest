<?php

/**
 * @see function saveFile($dir)
 * @param uploadFile
 * @return 2x array
 */
function saveFile($dir)
{
	
	$filename = basename($_FILES['afile']['name']);
	$ar= array();
	// $ext = filename_extension(substr(trim($fname),-5)); // 5 znakow od konca
	$ext = pathinfo($filename, PATHINFO_EXTENSION);  
	$fname = pathinfo($filename, PATHINFO_FILENAME);
	$fname = substr(toEnglish($fname),0,95);
	$dt=date('ymdHis');
	$fn = generateRandomString( 8).'.'.$ext;  
	$dy =date('Ym') ;
	$tmpName  = $_FILES['afile']['tmp_name'];
	$fsize = $_FILES['afile']['size'];
	$ftype = $_FILES['afile']['type'];  // dir korespondencja/zawiadomienia
	//$uploaddir = '/srv/www/ssl/iocr/'.$dir.'/';//<----This is all I changed
	$uploaddir = ROOTPATH.'/'.$dir.'/';
	//ROOTPATH
	$uploadfile = $uploaddir.$fn ;
    $up=null;
	$up = (int)  move_uploaded_file($_FILES['afile']['tmp_name'], $uploadfile);
	$ar['up']= $up;
     if ($up ==1){
     	$ar['ftype']= $ftype;
     	$ar['filename']= $fn;
     	$ar['ext']= $ext;
     } else{
     	$ar['filename']= null;
     }
	
	return ($ar);
}

/*
 * function pdftk($ar)
 * pdftk
 * @input $file
 * output pdf file
 */  
function pdftk($ar,$p,$k)  { // $file = include path
	
	$yn=0;
	$fn = $ar['filename'];
	
	$afn = explode('.', $fn);
	$fnc = $afn[0].'_o.pdf';  //core of filename +out
	$fnp =ROOTPATH.'/src/'. $afn[0].'.txt';  // file number of pages
$src= './src/'.$fn;
$des = './src/'.$fnc;

$np = exec('pdftops ' . $src . ' - | grep showpage | wc -l', $fnp);

	if($k > $np) $k= $np;
	if($p > $np) $p= $np;

$cmd = 'pdftk '. $src.' cat '.$p.'-'.$k.' output '.$des ;
if (is_file($src)) 	$yn = shell_exec($cmd);
return ((int) $yn);
}

/*
 * function tesseractTXT($ar)
 * poppler-utils
 * @input $file
 * output pic file
 */  
function pdftoppmPIC($ar)  { // $file = include path
	$fn = $ar['filename'];
	$afn = explode('.', $fn);
	$fnc = $afn[0];  //core of filename 
	$ext='png';
	$src= './src/'.$fn;
	$des = './src/'.$fnc;
	// $scmd = 'pdftoppm -png -r 200  26912.pdf topng'; -rx 200 -ry 300
	$cmd = 'pdftoppm -r 200 -'.$ext.' '. $src.' '.$des ;
	if (is_file($src)){
		shell_exec($cmd);
	}  
	//$fi = $fnc.'-1.'.$ext;
	$fi = $fnc.'.'.$ext;
	$br['core']=$fnc;
	$br['filename']=$fi;
	$br['ext']=$ext;
	$srcfi= './src/'.$fi;
	if (! is_file($srcfi))$br['ext'] = 'qwe' ;

return ($br);
}

/*
 * function tesseractTXT($ar)
 * @input $file
 * output txt file
 */  
function tesseractTXT($ar)  { // $file = include path
	$fn = $ar['filename'];
	$afn = explode('.', $fn);
	$src= './src/'.$ar['filename'];
	$des = './dest/'.$afn[0]; //.'.txt';
	$cmd = 'tesseract -l pol '.$src.' '.$des ;
    shell_exec($cmd);
	$buf = null; 	
        $h0 = @fopen($des.'.txt', "r");
        if (is_file($des.'.txt')) {
        	 $h0 = @fopen($des.'.txt', "r");
        	 $nbuf= 0;
        	while (($buffer = fgets($h0, 4096)) !== false) {
        		$bufi = trim($buffer);
        		if (strlen($bufi)== 0) $nbuf ++;
        		if ($nbuf == 2) $buf[]= " ";
        		if (strlen($bufi)>0)
        			$buf[]= $bufi;
        			$nbuf= 0;
        	}
        }
        if ($h0) fclose($h0);
	
return ($buf);
}

function saveSession($log){
	
	$dwp = date ("Y-m-d H:i:s");
	$dym = date ("Ym");
	$dir = './logs/';
	$f = $dir.$dym.'.txt';
	$h1 = @fopen($f, "a");
	$ip = $_SERVER['REMOTE_ADDR'];
	fwrite($h1,$dwp.';'.$ip.';'.$log."\n" );
	if ($h1) fclose($h1);
}


function ifImage($img){
$image = array("image/jpeg", "image/png", "image/gif", "image/tiff");
	if (in_array($img, $image)) {
	    return true;
	}else{
		return false;
	}
}

function ifPdf($pdf){
$pdffile = array("application/pdf");
	if (in_array($pdf, $pdffile)) {
	    return true;
	}else{
		return false;
	}
}


/*
 * function generateRandomString($length = 10)
 * @input int
 * output string     
 */  

function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


/*
 * function DownloadFile($file) 
 * @input $file
 * output action
 */  

function DownloadFile($file) { // $file = include path
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }

    }

 function toEnglish($string){
		$string = str_replace(' ', '-', $string);
 		$string = strtolower($string);
		$polskie = array(',', ' - ',' ','ę', 'Ę', 'ó', 'Ó', 'Ą', 'ą', 'Ś', 's', 'ł', 'Ł', 'ż', 'Ż', 'Ź', 'ź', 'ć', 'Ć', 'ń', 'Ń','-',"'","/","?", '"', ":", 'ś', '!', '&', '&', '#', ';', '[',']','domena.pl', '(', ')', '`', '%', '”', '„', '…');
		$miedzyn = array('_','_','_','e', 'e', 'o', 'o', 'a', 'a', 's', 's', 'l', 'l', 'z', 'z', 'z', 'z', 'c', 'c', 'n', 'n','-',"","","","","",'s','','', '', '', '', '', '', '', '', '', '', '', '', '');
		$string = str_replace($polskie, $miedzyn, $string);
		// usuń wszytko co jest niedozwolonym znakiem
		$string = preg_replace('/[^0-9a-z\-]+/', '', $string);
		// zredukuj liczbę myślników do jednego obok siebie
		$string = preg_replace('/[\-]+/', '-', $string);
		// usuwamy możliwe myślniki na początku i końcu
		$string = trim($string, '-');
		$string = str_replace('-', '_', $string);
		$string = stripslashes($string);
		// na wszelki wypadek
		$string = urlencode($string);
	return $string;
}

function filename_extension($filename) {
    $pos = strrpos($filename, '.');
    if($pos===false) {
        return ('');
    } else {
        return substr($filename, $pos+1);
    }
}

?>
