<div id="contenu">
    <h2>Suivi du paiement</h2>
    <h3>Fiche frais &agrave; selectionner : </h3>
    <form action="index.php?uc=comptable&action=voirFiche" method="post">
        <div class="corpsForm">

            <p>

                <label for="lstFicheFrais" accesskey="n">Fiche :</label>
                <select id="lstFicheFrais" name="lstFicheFrais">
                        <?php
                    foreach ($lesFichesFrais as $uneFicheFrais) {
                        $id1 = $uneFicheFrais['id'];
                        $nom = $uneFicheFrais['nom'];
                        $prenom = $uneFicheFrais['prenom'];
                        $mois = $uneFicheFrais['mois'];
                        $numAnnee = $uneFicheFrais['mois'];
                        $numMois = $uneFicheFrais['mois'];
                        if($mois == $mois || $id1 == $id1 ){
                            ?>
                            <option selected value="<?php echo $id1 . " " . $mois; ?>"><?php echo  $numAnnee . '/' . $prenom . " " . $nom  ?></option>
                            <?php
                       }

				else{ ?>
                    <option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
                    <?php
                }

                    }?>


                </select>
            </p>
        </div>
        <div class="piedForm">
            <p>
                <input id="ok"  type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p>
        </div>
    </form>
