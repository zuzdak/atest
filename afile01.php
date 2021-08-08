<?php
define('ROOTPATH', __DIR__);
       
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);

if(isset($_GET['plik'])){   //  && isset($_GET['wyk']) )
        $plik= trim($_GET['plik']).'_o.pdf';
}
   //  echo $plik.'<br>';

     $dir =ROOTPATH.'/src/';

$filename = $dir.$plik;

$content = file_get_contents($filename);
header('Content-Type: application/pdf');
header('Content-Length: '.strlen( $content ));
header('Content-disposition: inline; filename="' . $filename . '"');
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
echo $content;



?>
