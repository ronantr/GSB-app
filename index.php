<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
$json = file_get_contents("include/composer.json");
include("vues/v_entete.php") ;
session_start();
//print_r($_SESSION);
//print_r(($_REQUEST));
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		  include("controleurs/c_etatFrais.php");break;
	}
	case 'comptable' :{
		include("controleurs/c_comptable.php");break;
	}
}
include("vues/v_pied.php") ;
?>
