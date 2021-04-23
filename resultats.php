<?php
/* -----------------------------------------------------
Affichage des résultats de l'équipe à la fin d'une partie
---------------------------------------------------------
 */
session_start();
require_once 'fonctions.php';
$BDD = connect();

//  COTÉ ÉQUIPE
if (isset($_SESSION['idEqu']))
{
	$idPart=$_SESSION['partieEC']['idPart'];
	$idJeuEqu=$_SESSION['partieEC']['idJeu'];
	$nbEnigmesJeu=$_SESSION['nbEnigmesJeu'];

	//si l'équipe arrive sur cette page, c'est quelle a terminé  sa partie
	$insertionInfosPartie = $BDD->prepare("UPDATE partie SET partieFinie=true WHERE idPart=:idPart");
	$insertionInfosPartie->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
	$insertionInfosPartie->execute();

}
// COTE MJ
else if(isset($_GET['idEqu'])&& isset($_SESSION['idMJ']))
{
	$idEqu = $_GET['idEqu'];
	$_SESSION['idEquPrMj'] = $idEqu;
	$idJeuEqu= $_SESSION['idJeuPrMJ'];

	//recherche de la partie en cours de l'équipe 
	$requetePartieMax=$BDD->prepare("SELECT MAX(partie.idPart) FROM partie WHERE idEqu=:idEqu AND idJeu=:idJeuEqu");
	$requetePartieMax->bindValue('idEqu', escape($idEqu), PDO::PARAM_STR);
	$requetePartieMax->bindValue('idJeuEqu', escape($idJeuEqu), PDO::PARAM_STR);
	$requetePartieMax->execute();
	$idPart=$requetePartieMax->fetch()[0];
}

	 
	 //recherche de la date de lancement de la première énigme
	 $requeteDateMin = $BDD->prepare("SELECT MIN(datedebut) FROM tentative, equipe WHERE idPart=:idPart");
	 $requeteDateMin->bindValue('idPart', escape($idPart), PDO::PARAM_STR);	
	 $requeteDateMin->execute();
	 $DateMin=$requeteDateMin->fetch()[0];
 
	 // recherche de la date de la dernière résolution
		 $requeteDateMax = $BDD->prepare("SELECT MAX(dateFin) FROM tentative, equipe WHERE idPart=:idPart");//ajouter jeu
	 $requeteDateMax->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
	 $requeteDateMax->execute();
	 $DateMax=$requeteDateMax->fetch()[0];
 
		
	 $tmpsMis =gmdate("H:i:s", strtotime($DateMax) - strtotime($DateMin));

	 if (isset($_SESSION['idEqu']))
	 {
		 //insertion dans la BDD du temps de la partie
	 $insertionTmps = $BDD->prepare("UPDATE partie SET tmpsTotal=:tmpsTotal WHERE idPart=:idPart");
	 $insertionTmps->bindValue('tmpsTotal', escape($tmpsMis), PDO::PARAM_STR);
	 $insertionTmps->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
	 $insertionTmps->execute();
	 }

	//On récupère le nombre total d'énigmes dans le jeu
	$requeteMaxEnigme =$BDD->prepare("SELECT MAX(ordre) FROM enigme WHERE idJeu=:idJeuEqu");
	$requeteMaxEnigme->bindValue('idJeuEqu', escape($idJeuEqu), PDO::PARAM_STR);
	$requeteMaxEnigme->execute();
	$nbEnigmesJeu=$requeteMaxEnigme->fetch()[0];

// on récupère le nombre d'énigmes réalisées
$requeteNbEnigmes = $BDD->prepare("SELECT COUNT(tentative.idEnigme) FROM tentative WHERE codeStade ='T' AND idPart=:idPart");
$requeteNbEnigmes->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
$requeteNbEnigmes->execute();
$nbEnigmesRealisees = $requeteNbEnigmes->fetch()[0]; 

if ($nbEnigmesRealisees==$nbEnigmesJeu){
	$updatePartieReussie=$BDD->prepare("UPDATE partie SET partieReussie=true WHERE idPart=:idPart");
	$updatePartieReussie->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
	$updatePartieReussie->execute();
	$victoire=true;
}
?>

<!doctype html>
<html>
 <?php require_once 'head.php'; ?>

<body class="Degradee">
	<?php require_once 'navbar.php';?>

<div class="container">
	<div class="row">
		<img class="responsive" src="images/loupe.png" width="30%" height="30%">
		<div class="col-sm-9 col-md-7 col-lg-5 mx-auto" style="text-align:center">
			<div class="card card-signin my-5">
					<div class="card-body">
							<h2 class="card-title text-center">Résultats de l'équipe</h2>

							<h4 style="text-align: center"><b>Nombres d'énigmes réalisées : </b></h4><?php echo $nbEnigmesRealisees?>/<?php echo $nbEnigmesJeu?>

							 <!--  MESSAGE VICTOIRE si toutes les énigmes ont été réalisées -->
							<?php	if(isset($victoire)){ ?> <!--variable session enregistrée dans enigme.php -->
											<p>Félicitation, Vous avez terminé le jeu ! </p>
							<?php }?>

							
								<!-- affichage du temps de jeu de l'équipe sur cette partie -->
							<h4 style="text-align: center">Durée de jeu : </h4> <?php echo $tmpsMis?>
							</h4></p>
					</div>
			</div>
			<?php if(isset($_SESSION['idEqu'])) {?>
				<a href="index.php">
				<input class="btn" type="button" value="Enregistrer">
				</a>
				<?php 
						if (isset($victoire) && $victoire==true){ require_once 'statistiques.php';}
				}  ?> 
		</div>
		<div>
			<?php if (isset($_SESSION['idMJ'])){ ?>
			<!-- Chat -->
			<div class="chat-btn">
			<?php require_once 'chat.php';} ?>
		</div>
	</div>	
</div>
<div class=footer>
	<?php require_once 'footer.php';?>
</div>
</body>

</html>