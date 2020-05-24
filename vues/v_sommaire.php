    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
                <?php if ($_SESSION['type_droit']==1 ) {?>
            <li>
                <a>Comptable</a>
                            </li>
            <li class="active">
                <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
                <a></a>
                <br>
            </li>
            <li class="active">
                <a href="index.php?uc=comptable&action=ValiderFicheFrais" title="ValiderFicheFrais">Valider Les Fiches de Frais</a>
                <br>
            </li>
            <li class="active">
                <a href="index.php?uc=comptable&action=SuiviPaiement" title="test8">Suivi des Fiches de Frais</a>
                <br>
            </li>

            <?php }  else { ?>
                <li class="active">
                    <a>Visiteur</a>
                </li>

                <li class="active">
                    <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
                    <a></a>
                    <br>
                </li>
                <li class="active">
                    <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
                </li>
                <li class="active">
                    <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
                </li>

            <?php } ?>


            <li class="smenu">
                <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
            </li>
         </ul>

    </div>
    