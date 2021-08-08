<?php
require_once('aconf.php');

if (session_status() == PHP_SESSION_ACTIVE) {
  echo 'Session is active';
  $smarty->assign('sesja', 'Session is active');
}

// if (! isset($_SESSION['au']) ) session_start();
ob_start();


if (isset($_POST['loguj'])) {
	$pas = cleanString($_POST['haslo']);
	$na = cleanString( $_POST['nazwa_au']);
  //	echo $pas.'---'.$na;
	// $au=loguj_aut(strtolower($na),$pas); // funkcje_bazy
/*
	sleep(1);
	if ($au == 0) {  // array data of user
		$smarty->assign('vi', 0);
	}elseif($au == 1 and $_SESSION['au']['idp'] == 2 ){
		//echo $_SESSION['au']['idp'];
		$smarty->assign('vi', 1);
	}
	*/     
}


if (!isset ($_SESSION['au'])) {
	$smarty->assign('vi', 0);
	
}else{
	$smarty->assign('au', $_SESSION['au']);
	$smarty->assign('today', date("Y-m-d"));
}
	$smarty->assign('title', 'OCR <- IMG/PDF');
	$smarty->display('index.tpl');
ob_end_flush();
?>

