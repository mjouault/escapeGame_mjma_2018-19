<?php
/* -----------------------------------------------------
[equipe et MJ] chat entre une équipe et les MJ connectés
- inséré ds les pages mj.php et énigme. php
---------------------------------------------------------
 */
session_start();
require_once 'fonctions.php';
$BDD = connect();

?>

<!doctype html>
<html>

<head>
	<?php require_once 'head.php';?>
	<meta http-equiv="refresh" content="2">
</head>

<body>
	<?php
    // Récupération des messages 
    if (isset($_SESSION['idEqu']))
    { 
        $idEnigmeEC= $_SESSION['enigmeEC']['idEnigme'];
        $partieEC=$_SESSION['partieEC'];
        	 //requete tentative en cours 
	 $requeteTentativeEC=$BDD->prepare("SELECT idTent, partie.idEqu FROM tentative, partie WHERE codeStade='EC' AND tentative.idPart=:idPart");
	 $requeteTentativeEC->bindValue('idPart', escape($partieEC['idPart']), PDO::PARAM_STR);
	 $requeteTentativeEC->execute();
     $tentEC= $requeteTentativeEC->fetch();

     $_SESSION['tentativeEC']=$tentEC;
     
        $requeteMsg=$BDD->prepare("SELECT msg, dateMsg, idMJ, loginEqu
                                    FROM aider, tentative, equipe,partie
                                    WHERE tentative.idTent = aider.idTent AND tentative.idPart= partie.idPart AND partie.idEqu=equipe.idEqu AND tentative.idTent=:idTent
                                    ORDER BY dateMsg");
        $requeteMsg->bindValue('idTent', escape($tentEC['idTent']), PDO::PARAM_STR);
        $requeteMsg->execute();
        $derniersMessages = $requeteMsg->fetchAll();
    }
    else if (isset($_SESSION['idMJ']) && isset($_SESSION['idEquPrMj']))
    { 
        $requeteMsg=$BDD->prepare("SELECT msg, dateMsg, idMJ, loginEqu
                                    FROM aider, tentative, equipe, partie
                                    WHERE tentative.idTent = aider.idTent AND tentative.idPart=partie.idPart AND partie.idEqu=equipe.idEqu AND equipe.idEqu=(:idEqu) AND codeStade='EC' 
                                    ORDER BY dateMsg");
        $requeteMsg->bindValue('idEqu', escape($_SESSION['idEquPrMj']), PDO::PARAM_STR);
        $requeteMsg->execute();
        $derniersMessages = $requeteMsg->fetchAll();
    }

    // Affichage de chaque message
    foreach ($derniersMessages as $line) {
        //si le message est une demande d'aide de la part d'une équipe
        if ($line['idMJ'] == null) {
            ?>
            <br/>
            <div class="speech-bubble">
                    <p><?php echo escape($line['loginEqu']) . ': ';?>
                    <?php echo escape($line['msg']);?><br/>
                    <span class="date"><?php echo escape($line['dateMsg']);?></span>
                    </p>
            </div>
            <?php 
                                     
        } else {?>
            <div class="speech-bubblemj">
                <p><?php echo 'MJ : ' . escape($line['msg']) ?><br/>
                <span class="date"> <?php echo escape($line['dateMsg']);?></span>
                 </p>
            </div>
	<?php
        }
    }

$requeteMsg->closeCursor();?>
</body>

</html>