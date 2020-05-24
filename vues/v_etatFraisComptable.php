<form method="POST" action="index.php?uc=comptable&action=Elementsforfaitises">

    <h3>Fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?> :
    </h3>
    <div class="encadre">
        <p>
            Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>


        </p>
        <table class="listeLegere">
            <caption>Eléments forfaitisés</caption>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait)
                {
                    $libelle = $unFraisForfait['libelle'];
                    $quantite = $unFraisForfait['quantite'];
                    $idfrais = $unFraisForfait['idfrais'];
                    $idvisiteur = $_SESSION['lstVisiteurs'];
                    echo "<input type ='hidden' name='date' value='$numAnnee$numMois'>";
                    echo "<input type ='hidden' name='idFrais' value='$idfrais'>";
                    echo "<input type ='hidden' name='lstVisiteurs' value='$idvisiteur'>";
                    ?>
                    <th> <?php echo $libelle ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite'];
                    $idFrais = $unFraisForfait['idfrais'];
                    ?>
                    <td class="qteForfait"><input type="text" size="4" maxlength="5" name="lesFrais[<?php echo $idFrais ?>]" value="<?php echo $quantite ?> "></td>


                    <?php
                }
                ?>
            </tr>
        </table>
            <div class="button" align="center">
                <input id="ok" type="submit" name="valider2" value="Valider" size="20" />
            </div>
</form>
<form method="POST" action="index.php?uc=comptable&action=ValidNbJustif&id=<?php echo $id;?>">
<table class="listeLegere">

    <caption><br>Descriptif des éléments hors forfait -<input name='nbjustif' type="number" size="3px" maxlength="5" value="<?php echo $nbJustificatifs ?>"> justificatifs reçus -
            <input id="ok" type="submit" name="valider3" value="Valider" size="5" />
    </caption>
    <tr>
        <th class="date">Date</th>
        <th class="libelle">Libellé</th>
        <th class='montant'>Montant €</th>
        <th colspan=2 class="action">Action</th>
        <!--  <th class='supprimer'>Supprimer</th>
       <th class='reporter'>Reporter</th> -->
    </tr>
    <?php
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $id = $unFraisHorsForfait['id'];
    $date = $unFraisHorsForfait['date'];
    $libelle = $unFraisHorsForfait['libelle'];
    $montant = $unFraisHorsForfait['montant'];
    ?>

    <tr <?php if (substr($libelle,0,9)=='REFUSE : '){ echo 'bgcolor="red">' ?>

        <td><?php echo $date ?></td>
        <td><?php echo $libelle ?></td>
        <td><?php echo $montant ?></td>

        <td><p>Supprimer</p></td>
        <td><p>Reporter</p></td>
        <?php
        }else{
            ?>
            <td></td>
            <td><?php echo $date ?></td>
            <td><?php echo $libelle ?></td>
            <td><?php echo $montant ?></td>
            <td><a href="index.php?uc=comptable&action=Supprimer&id=<?php echo $id; ?>">Supprimer</a></td>
            <td><a href="index.php?uc=comptable&action=Reporter&id=<?php echo $id; ?>">Reporter</a></td>
            <?php
        }
    }
        ?>
</table>
</form>
<form method="POST" action="index.php?uc=comptable&action=ListeFicheValider">
<tr>
    <h3>Valider la fiche de frais</h3>
    <div class="button" align="center">
        <input id="ok" type="submit" name="valider7" value="Valider" size="20" />
    </div>
</tr>
</form>
</div>