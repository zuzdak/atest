<?php
define('ROOTPATH', __DIR__);
       
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);

if(isset($_GET['plik'])){   //  && isset($_GET['wyk']) )
        $plik= trim($_GET['plik']).'_o.pdf';
}
   //  echo $plik.'<br>';

     $dir =ROOTPATH.'/src/';

$file = $dir.$plik;

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}



?>
