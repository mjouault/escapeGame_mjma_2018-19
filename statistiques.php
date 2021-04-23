<?php
require_once 'fonctions.php';
$BDD = connect();
//---------------------------------------------------------------------
//[equipe, MJ] statistiques de temps par jeu (pire temps, meilleur temps, temps moyen)
//----------------------------------------------------------------------

$requeteIdJeu=$BDD->prepare("SELECT * FROM jeu");
$requeteIdJeu->execute();
$infoJeu=$requeteIdJeu->fetchAll();
?>
<!-- Affichage des statistiques de chaque jeu -->
					<div class="card card-signin my-5">
						<div class="card-body">
                                <?php if(isset($_SESSION['idEqu'])){ ?>
                                <h4>Situez-vous sur ce jeu par rapport aux autres équipes : </h4><?php
                                    $requeteStatsTmps=$BDD->prepare("SELECT MIN(tmpsTotal) AS meilleurTmps, MAX(tmpsTotal) AS pireTmps, AVG(tmpsTotal) AS tmpsMoy FROM partie WHERE idJeu=:idJeu AND partieReussie=true");
                                    $requeteStatsTmps->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);  
                                    $requeteStatsTmps->execute();
                                    $statsTmps=$requeteStatsTmps->fetch(); 

                                    // maj de la BDD que du côté des équipes
                                        $insertionTmps=$BDD->prepare("UPDATE jeu SET tmpsMoy=:tmpsMoy, meilleurTmps=:meilleurTmps, pireTmps=:pireTmps WHERE idJeu=:idJeu");
                                        $insertionTmps->bindValue('tmpsMoy', escape($statsTmps['tmpsMoy']), PDO::PARAM_STR);
                                        $insertionTmps->bindValue('meilleurTmps', escape($statsTmps['meilleurTmps']), PDO::PARAM_STR);
                                        $insertionTmps->bindValue('pireTmps', escape($statsTmps['pireTmps']), PDO::PARAM_STR);
                                        $insertionTmps->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
                                        $insertionTmps->execute(); 
                                        ?>

                                        <!-- affichage des résultats calculés -->
                                        <p>Meilleur temps : <?php echo $statsTmps['meilleurTmps']; ?></p>
                                        <p>Temps le plus long : <?php echo $statsTmps['pireTmps']; ?></p>
                                        <p>Temps moyen de réussite : <?php echo date("H:i:s", $statsTmps['tmpsMoy']); ?><p><br/>
                                        </p>
                                       <?php }

                                       //affichage de toutes les stats de tous les jeux pour le MJ
                                        else if (isset($_SESSION['idMJ'])){?> <h4>Quelques statistiques :</h4>

                                        <?php foreach ($infoJeu as $line){ ?>
                                        <h5> Jeu : <?php echo $line['titreJeu'] ?></h5> 
                                        <p>Meilleur temps : <?php echo $line['meilleurTmps']; ?></p>
                                        <p>Temps le plus long : <?php echo $line['pireTmps']; ?></p>
                                        <p>Temps moyen de réussite : <?php echo date("H:i:s", $line['tmpsMoy']); ?><p><br/>
                                        </p>
                                        <?php }
                                        } ?>
                        </div>
                    </div>
