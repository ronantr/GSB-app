<?php
/**
 * Classe d'accès aux données.

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb
{
	/**
	 * @var string
	 */
	private static $serveur = 'mysql:host=localhost';
	private static $bdd = 'dbname=gsb';
	private static $user = 'rtrouillard';
	private static $mdp = 'test';
	private static $monPdo;
	private static $monPdoGsb = null;

	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la classe
	 */
	private function __construct()
	{
		PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}

	public function _destruct()
	{
		PdoGsb::$monPdo = null;
	}

	/**
	 * Fonction statique qui crée l'unique instance de la classe
	 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
	 * @return l'unique objet de la classe PdoGsb
	 */
	public static function getPdoGsb()
	{
		if (PdoGsb::$monPdoGsb == null) {
			PdoGsb::$monPdoGsb = new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;
	}

	/**
	 * Retourne les informations d'un visiteur
	 * @param $login
	 * @param $mdp
	 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
	 */
	public function getInfosVisiteur($login, $mdp)
	{
		$req = "select visiteur.id as id,visiteur.type_droit as type_droit, visiteur.nom as nom, visiteur.prenom as prenom from visiteur
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

	/**
	 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
	 * concernées par les deux arguments
	 * La boucle foreach ne peut être utilisée ici car on procède
	 * à une modification de la structure itérée - transformation du champ date-
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
	 */
	public function getLesFraisHorsForfait($idVisiteur, $mois)
	{
		$req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur'
		and lignefraishorsforfait.mois = '$mois' ";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i = 0; $i < $nbLignes; $i++) {
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
		}
		//print_r($req);
		return $lesLignes;
	}

	/**
	 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return le nombre entier de justificatifs
	 */
	public function getNbjustificatifs($idVisiteur, $mois)
	{
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}

	/**
	 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
	 * concernées par les deux arguments
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif
	 */
	public function getLesFraisForfait($idVisiteur, $mois)
	{
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, fraisforfait.montant as montant, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois'
		order by lignefraisforfait.idfraisforfait";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

	/**
	 * Retourne tous les id de la table FraisForfait
	 * @return un tableau associatif
	 */
	public function getLesIdFrais()
	{
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

	/**
	 * Met à jour la table ligneFraisForfait
	 * Met à jour la table ligneFraisForfait pour un visiteur et
	 * un mois donné en enregistrant les nouveaux montants
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
	 * @return un tableau associatif
	 */
	public function majFraisForfait($idVisiteur, $mois, $lesFrais)
	{
		$lesCles = array_keys($lesFrais);
		foreach ($lesCles as $unIdFrais) {
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.mois = '$mois' and lignefraisforfait.idvisiteur = '$idVisiteur'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}

	}

	/**
	 * met à jour le nombre de justificatifs de la table ficheFrais
	 * pour le mois et le visiteur concerné
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 */
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
	{
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

	/**
	 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return vrai ou faux
	 */
	public function estPremierFraisMois($idVisiteur, $mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if ($laLigne['nblignesfrais'] == 0) {
			$ok = true;
		}
		return $ok;
	}

	/**
	 * Retourne le dernier mois en cours d'un visiteur
	 * @param $idVisiteur
	 * @return le mois sous la forme aaaamm
	 */
	public function dernierMoisSaisi($idVisiteur)
	{
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}

	/**
	 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
	 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
	 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 */
	public function creeNouvellesLignesFrais($idVisiteur, $mois)
	{
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
		if ($laDerniereFiche['idEtat'] == 'CR') {
			$this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');

		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat)
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach ($lesIdFrais as $uneLigneIdFrais) {
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite)
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		}
	}

	/**
	 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
	 * à partir des informations fournies en paramètre
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @param $libelle : le libelle du frais
	 * @param $date : la date du frais au format français jj//mm/aaaa
	 * @param $montant : le montant
	 */
	public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant)
	{
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait
		values(NULL,'$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}

	/**
	 * Supprime le frais hors forfait dont l'id est passé en argument
	 * @param $idFrais
	 */
	public function supprimerFraisHorsForfait($idFrais)
	{
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}

	/**
	 * Retourne les mois pour lesquel un visiteur a une fiche de frais
	 * @param $idVisiteur
	 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
	 */
	public function getLesMoisDisponibles($idVisiteur)
	{
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur'
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois = array();
		$laLigne = $res->fetch();
		while ($laLigne != null) {
			$mois = $laLigne['mois'];
			$numAnnee = substr($mois, 0, 4);
			$numMois = substr($mois, 4, 2);
			$lesMois["$mois"] = array(
				"mois" => "$mois",
				"numAnnee" => "$numAnnee",
				"numMois" => "$numMois"
			);
			$laLigne = $res->fetch();
		}
		return $lesMois;
	}

	/**
	 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné avce comme condition cl
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état
	 */
	public function getLesInfosFicheFrais($idVisiteur, $mois)
	{
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs,
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois' and ficheFrais.idEtat ='CL' ";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}

	/**
	 * Modifie l'état et la date de modification d'une fiche de frais
	 * Modifie le champ idEtat et met la date de modif à aujourd'hui
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 */

	public function majEtatFicheFrais($idVisiteur, $mois, $etat)
	{
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now()
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}


	/**
	 *Récupére les id qui ont  clotures une fiche  dans la bdd et le mois de cloturé
	 *
	 * @param $idclient
	 * @param $mois
	 * @return
	 */
	public function getIDclient()
	{
		$req = "SELECT distinct idVisiteur,nom,prenom,idEtat FROM visiteur,fichefrais WHERE idEtat='CL' AND fichefrais.idVisiteur=visiteur.id AND visiteur.type_droit='0' order by nom ASC";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes=$res->fetchAll();
		//print_r($req);
		return $lesLignes;
	}

	/**
	 *Update le libelle du tableau tr en REFUSE : 'nom du libelle'
	 *
	 * @param $idclient
	 * @param $mois
	 * @return
	 */
	public function refuserfrais($id) {
		$req = "update lignefraishorsforfait SET libelle =  CONCAT('REFUSE : ', libelle) WHERE id ='$id';";
		return PdoGsb::$monPdo->exec($req);
		//PdoGsb::$monPdo->exec($req);
		//CONCAT('REFUSE : ', libelle)
		//print_r($req);

	}

	/**
	 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return le nombre entier de justificatifs
	 */
	public function setNbjustificatifs($date,$idVisiteur, $test)
	{
		$req = "UPDATE fichefrais SET nbJustificatifs='$test' WHERE idVisiteur='$idVisiteur' and mois='$date'";
		PdoGsb::$monPdo->exec($req);
		//print_r($req);
	}

	/**
	 * Retourne les fiches frais qui sont validées
	 *
	 * @return $ligneResultat sous forme de tableau associatif content l'id, le nom ainsi que le prénom du visiteur, et le mois concerné.
	 */
	public function getFichesFraisValidees(){
		$req = "select distinct id, nom, prenom, mois from fichefrais join visiteur on fichefrais.idvisiteur = visiteur.id and idetat = 'VA'";
		$res = PdoGsb::$monPdo->query($req);
		$ligneResultat = $res->fetchall();
		//print_r($req);
		return $ligneResultat;
	}
	/**
	 *Récupére les id qui ont  clotures une fiche  dans la bdd et le mois de cloturé
	 *
	 * @param $idclient
	 * @param $mois
	 * @return
	 */
	public function ListeFicheValider ($idVisiteur,$date)    {
		$req = "UPDATE fichefrais SET idEtat='VA' WHERE fichefrais.idVisiteur='$idVisiteur' and fichefrais.mois='$date'";
		//UPDATE `fichefrais` SET `idEtat` = 'VA' WHERE `fichefrais`.`idVisiteur` = 'a12' AND `fichefrais`.`mois` = '201911';
		PdoGsb::$monPdo->exec($req);
	}

	public function getMoisSuivant($annee, $mois, $id) {
		$ladate =$mois; // concatene les dates
		$req = "update lignefraishorsforfait set mois = '$ladate' where id='$id' ";
		//print_r($req);
		PdoGsb::$monPdo->query($req);
	}


	/**
	 * @param $idVisiteur
	 * @param $mois
	 */
	public function updateFraisForfait($idVisiteur, $mois)
	{
		$lesFraisForfait = $this -> getLesFraisForfait($idVisiteur, $mois);
		echo '<br>';
		//print_r($lesFraisForfait);
		echo '<br>';

		$lesFraisHF = $this -> getLesFraisHorsForfait($idVisiteur, $mois);
		echo '<br>';
		//print_r($lesFraisHF);
		echo '<br>';


		//Calcul du total
		$total = 0;
		foreach($lesFraisForfait as $leFraisForfait){
			$total += $leFraisForfait['montant']*$leFraisForfait['quantite'];
		}
		foreach($lesFraisHF as $leFraisHF){

			//On regarde si le libelle du frais commence par 'REFUSE : ' pour savoir si on le prends en compte
			$libelle = substr( $leFraisHF['libelle'] , 0,9);
//substr($libelle,0,9)=='REFUSE : '
			if($libelle != 'REFUSE : '){

				$total += $leFraisHF['montant'];

			}

		}

		$requetePrepare = PdoGSB::$monPdo->prepare(
			'UPDATE fichefrais '
			. 'SET fichefrais.montantvalide = :unTotal '
			. 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
			. 'AND fichefrais.mois = :unMois '
		);
		$requetePrepare->bindParam(':unTotal', $total, PDO::PARAM_INT);
		$requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
		$requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
		$requetePrepare->execute();
	}
	/**
	 *Récupére les id qui ont  clotures une fiche  dans la bdd et le mois de cloturé
	 *
	 * @param $idclient
	 * @param $mois
	 * @return
	 */
	public function ListeFicheValiderRB($idVisiteur,$date)    {
		$req = "UPDATE fichefrais SET idEtat='RB' WHERE fichefrais.idVisiteur='$idVisiteur' and fichefrais.mois='$date'";
		//UPDATE `fichefrais` SET `idEtat` = 'VA' WHERE `fichefrais`.`idVisiteur` = 'a12' AND `fichefrais`.`mois` = '201911';
		PdoGsb::$monPdo->exec($req);
		print_r($req);
	}
	/**
	 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné avce comme condition cl
	 * @param $idVisiteur
	 * @param $mois sous la forme aaaamm
	 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état
	 */
	public function getLesInfosFicheFraisVA($idVisiteur, $mois)
	{
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs,
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois' and ficheFrais.idEtat ='VA' ";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;

	}


}

?>
