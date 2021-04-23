<?php
session_start();
require_once 'fonctions.php';
require_once 'head.php';
$BDD = connect();

//insertion des données relatives au message envoyé
if (isset($_POST['message'])) {

    // si une équipe fait une demande
    if (isset($_SESSION['idEqu'])) {
        $idTent= $_SESSION['tentativeEC']['idTent'];

        //2) création d'une demande d'aide
        $requeteAide = $BDD->prepare("INSERT INTO aider (idTent, msg, dateMsg)
                                    VALUES (:idTent, :msg, NOW())");
         $requeteAide->bindValue('idTent', $idTent, PDO::PARAM_STR); // Le idMJ precedemment recupere 
    } else // si un mj répond à une demande d'aide
    {

        $requeteIdTent = $BDD->prepare("SELECT idTent FROM tentative, partie WHERE codeStade = 'EC' AND tentative.idPart=partie.idPart AND idEqu = :idEqu");
        $requeteIdTent->bindValue('idEqu', $_POST['idEquPrMj'], PDO::PARAM_STR);
        $requeteIdTent->execute();
        $idTent = $requeteIdTent->fetch()['idTent'];

        // Maintenant qu'on a l'idMJ, on crée une réponse d'aide
        $requeteAide = $BDD->prepare("INSERT INTO aider (idMJ, idTent, msg, dateMsg)
                   VALUES ( :idMJ, :idTent, :msg, NOW() )");
        $requeteAide->bindValue('idMJ', $_SESSION['idMJ'], PDO::PARAM_STR); // Le idMJ precedemment recupere 
        $requeteAide->bindValue('idTent', $idTent, PDO::PARAM_STR); // Le idMJ precedemment recupere 
    }

    $msg = $_POST['message'];

    $requeteAide->bindValue('msg', $msg, PDO::PARAM_STR); // Ca c'etait present a la fois dans le if et dans le else = duplication de code, donc on peut le faire apres
    $requeteAide->execute();
}
?>
<br/>
<form action="chatEnvoiMsg.php" method="post">
	<div class=form-label-group>
		<label for="message"></label>
		<input class="entreemessage" type="text" name="message" id="inputmessage" placeholder="Tapez votre message ici" />
        <?php if(isset($_SESSION['loginMJ'])) { 
            ?>
            <input type="text" name="idEquPrMj" value="<?= $_SESSION['idEquPrMj'] ?>" style="display: none" />
            <?php
        } ?>
	</div>
	<button class="btn" type="submit">Envoyer</button>
</form>