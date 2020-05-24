<div id="contenu">
    <h2>Valider fiche frais </h2>
    <h3>Visiteurs et mois à sélectionner : </h3>
    <form action="index.php?uc=comptable&action=AfficheFicheFrais" method="post">

        <div class="corpsForm">

            <p>
                <label for="lstVisiteurs" accesskey="n">Visiteur: </label>
                <select id="lstVisiteurs" name="lstVisiteurs">
                    <?php
                    foreach ($lesVisiteurs as $Visiteur) {
                        $id = $Visiteur['idVisiteur'];
                        $prenom = $Visiteur['prenom'];
                        $nom = $Visiteur['nom'];

                        if ($Visiteur == $Visiteur['idVisiteur']) {
                            ?>
                            <option selected value="<?php echo $id ?>"><?php echo $nom . " " . $prenom ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>"><?php echo $nom . " " . $prenom ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="lstMois" accesskey="n">Mois: </label>
                <select id="lstMois" name="lstMois">
                    <?php
                    $i = 0;
                    $tableMois = $lastSixMonth['idVisiteur'];

                    foreach ($tableMois as $mois) {
                        if ($mois == $Visiteur['idVisiteur']) {
                            ?>
                            <option selected value="<?php echo $mois; ?>"><?php echo $lastSixMonth['libelle'][$i]; ?></option>
                            <?php
                        } else {
                            ?>
                            <option  value="<?php echo $mois; ?>"><?php echo $lastSixMonth['libelle'][$i]; ?></option>
                            <?php
                        }
                        $i++;
                    }
                    ?>
                </select>

            </p>



        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p>

        </div>

    </form>

