<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();
/* -----------------------------------------------------
[MJ] Tableau de bord du MJ
---------------------------------------------------------
 */
if (isset($_SESSION['idMJ'])) {?>
<!doctype html>
<html>

<!--HEAD -->
<?php require_once 'head.php'; ?>
<body class="Degrade">


	<?php require_once 'navbar.php'; ?>

	<br/>
	<br/>
	<br/>
	<!--liste des équipes qui jouent -->
	<h2>Panneau de gestion des équipes</h2>
	<br/>
	<br/>
	<br/>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<h2>Equipes connectées</h2>
			</div>
		</div>
	</div>
	<br/>
	<br/>

	<?php
// récupération des informations relatives aux équipes indépendemment du jeu qu'elles font
if ($BDD) {
    $requeteEqu = ("SELECT equipe.idEqu, nomEqu, imageEqu, nbJoueurs, titreJeu, jeu.idJeu  FROM equipe, partie, jeu, tentative, enigme WHERE partie.idEqu = equipe.idEqu
					AND partie.idJeu = jeu.idJeu
					AND enigme.idJeu = jeu.idJeu
					AND tentative.idPart=partie.idPart
					AND tentative.idEnigme=enigme.idEnigme
					AND codeStade='EC'");
	$rslt = $BDD->prepare($requeteEqu);
	$rslt->execute();
	$infoEqu = $rslt->fetchAll();

    foreach ($infoEqu as $ligne) { // Affichage dynamique, pour chaque équipe ...
        ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<a href="resultats.php?idEqu=<?php echo $ligne["idEqu"]?>" color="black">
					<h4 style="color:black">
						<?= $ligne["nomEqu"]; ?>
					</h4>
					<?php $_SESSION['idJeuPrMJ']= $ligne['idJeu'];

						?>
				</a>

				<!-- ... du nom de l'équipe -->
				Scénario en cours :
				<?php echo $ligne['titreJeu']; ?>
				<br/>Nombre de joueurs :
				<?php echo $ligne["nbJoueurs"]; ?>

				<!-- ... du nombre de joueurs -->
			</div>
			<div class="col-sm-3">
				<?php if (isset($ligne['imageEqu'])){ ?>
				<img class="arrondie" src="<?php echo " bdd\img\\".$ligne['imageEqu']?>" width="250" <?php } ?> 
				/>
				<!-- ... de son logo -->
			</div>
			<?php
			
		}
	}

?> <div style="margin-right:auto; margin-left:auto;"><?php
require_once 'statistiques.php'; 

// FOOOTER
require_once 'footer.php'; ?></div>
</body>
</html>
<?php } 
else { header ('Location:connexion.php');}