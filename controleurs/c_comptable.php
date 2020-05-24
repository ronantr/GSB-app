<?php
include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$type_droit = $_SESSION['type_droit'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
switch($action){
    case 'ValiderFicheFrais':{
        $lesVisiteurs = $pdo->getIDclient();//getIDclient
        //$leMois = isset($_SESSION['lstMois']) ? $_SESSION['lstMois'] : null;
        $lesClesV = array_keys($lesVisiteurs);
        $visiteurASelectionner = $lesClesV[0];
        $lastSixMonth = getLesSixDerniersMois();

        // print_r($_REQUEST['lstVisiteur']);
        include("vues/v_ValiderFicheFrais.php");
        break;
    }
    case 'AfficheFicheFrais': {
        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['lstMois'];
        }

        //print_r($_POST['lstVisiteur']);
        $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }

        break;
    }


    case 'Elementsforfaitises': { //fonctionnele
        if (isset($_POST['valider2'])){
            ajouterValider("Informations mises à jour");
            require("vues/v_valider.php");
            $lesFrais = $_REQUEST['lesFrais'];
            $leMois=$_REQUEST['date'];
            $idVisiteur=  $_SESSION['lstVisiteurs'];
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
        }
        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['date'];
        }

        //print_r($_POST['lstVisiteur']);
        // $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }

    case 'ValidNbJustif': { //fonctionnele
        if (isset($_REQUEST['valider3'])){
            ajouterValider("Les justificatif on ete mis à jour");
            require("vues/v_valider.php");
            $test =$_REQUEST['nbjustif'];
            $date =$_SESSION['lstMois'];
            $idVisiteur=$_SESSION['lstVisiteurs'];
            $pdo->setNbjustificatifs($date,$idVisiteur, $test);
            //  print_r($test);
            //echo'<br>';
            //print_r($date);
            //echo'<br>';
            //print_r($idVisiteur);
        }
        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['lstMois'];
        }

        //print_r($_POST['lstVisiteur']);
        $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }


    case 'Supprimer':{
        //fonctionnele
        if (isset($_REQUEST['id'])){
            ajouterValider("Informations mises à jour");
            $id = $_REQUEST['id'];
            $pdo->refuserfrais($id);
            require("vues/v_valider.php");
        }
        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['lstMois'];
        }

        //print_r($_POST['lstVisiteur']);
        $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }


    case 'Reporter':{
        //fonctionnele
        if (isset($_REQUEST['id'])){
            ajouterValider("Informations mises à jour");
            require("vues/v_valider.php");
            $idVisiteur=  $_SESSION['lstVisiteurs'];
            $id = $_REQUEST['id'];
            $MoisPlus = getMoisNext($numAnnee, substr($_SESSION['lstMois'], 4, 2)); // appel de la fonction qui ajoute 1 au mois
            $ficheExiste = $pdo->estPremierFraisMois($idVisiteur,$MoisPlus); // un visiteur possède une fiche de frais pour le mois passé en argument
            //var_dump($ficheExiste);
            //var_dump($MoisPlus);
            //var_dump($idVisiteur);
             if ($pdo->estPremierFraisMois($idVisiteur, $MoisPlus)==false) {
              $pdo->getMoisSuivant($numAnnee, $MoisPlus, $id);
              } else {
            $pdo->creeNouvellesLignesFrais($idVisiteur, $MoisPlus);
            $req = "UPDATE `lignefraisforfait` SET `mois`='" . $MoisPlus . "' WHERE `idVisiteur`='" . $idVisiteur . "' and `idFraisForfait`='" . $id . "'";
        }
        }

        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['lstMois'];
        }

        //print_r($_POST['lstVisiteur']);
        $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $montantValide =  $pdo->updateFraisForfait($mois,$idVisiteur);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }


    case 'ListeFicheValider':{
        //fonctionnele
        if (isset($_REQUEST['valider7'])){
            ajouterValider("Informations mises à jour");
            $date =$_SESSION['lstMois'];
            $idVisiteur =$_SESSION['lstVisiteurs'];
            $pdo->ListeFicheValider ($idVisiteur,$date) ;
            require("vues/v_valider.php");
        }
        if (isset($_REQUEST['lstVisiteurs']) || isset($_REQUEST['lstMois'])){
            $_SESSION['lstVisiteurs'] = $_REQUEST['lstVisiteurs'];
            $_SESSION['lstMois'] =  $_REQUEST['lstMois'];
        }

        //print_r($_POST['lstVisiteur']);
        $idVisiteur=  $_SESSION['lstVisiteurs'];
        $visiteurASelectionner = $idVisiteur;
        $lastSixMonth = getLesSixDerniersMois();
        $lesVisiteurs = $pdo->getIDclient();
        $leMois= $_SESSION['lstMois'];
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        $mois= $_SESSION['lstMois'];
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_ValiderFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptable.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }
    case 'SuiviPaiement';{
        $lesFichesFrais= $pdo->getFichesFraisValidees();
        include ("vues/v_listeFicheFrais.php");
        break;
    }
    case 'voirFiche';{
        $lesFichesFrais= $pdo->getFichesFraisValidees();
        $idEtMois = explode(" ", $_POST['lstFicheFrais']);
        if (isset($_REQUEST['lstFicheFrais'])){
            $_SESSION['lstVisiteurs'] = $idEtMois[0];
            $_SESSION['lstMois'] =  $idEtMois[1];
        }
        $idVisiteur = $idEtMois[0];
        $mois = $idEtMois[1];
        $leMois = $idEtMois[1];
        $visiteurASelectionner = $idVisiteur;
        $montantValide =  $pdo->updateFraisForfait($idVisiteur, $mois);
        include("vues/v_listeFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFraisVA($idVisiteur, $leMois);
        if(is_array($lesInfosFicheFrais)){
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_etatFraisComptableFINAL.php");
        }else{
            ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
            include("vues/v_erreurs.php");
        }
        break;
    }
    case 'rembourse';{
        if (isset($_REQUEST['lstFicheFrais'])){
            $_SESSION['lstVisiteurs'] = $idEtMois[0];
            $_SESSION['lstMois'] =  $idEtMois[1];
        }
//print_r($_SESSION['lstVisiteurs']);
  //      print_r($_SESSION['lstMois']);

        $idVisiteur = $_SESSION['lstVisiteurs'];
        $mois = $_SESSION['lstMois'];
        $pdo->ListeFicheValiderRB($idVisiteur, $mois);
        include("vues/v_rembourse.php");
        break;
    }
}
?>
