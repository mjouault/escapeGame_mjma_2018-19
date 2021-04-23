<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();

$MaRequete = "SELECT* FROM jeu "; // requête sélectionnant toutes les données relatives à la table jeu
$rslt = $BDD->prepare($MaRequete);
$rslt->execute();
$infoJeu = $rslt->fetchAll();

?>
<!doctype html>
<html>
<?php
require_once 'head.php'; // paramètres d'affichage préalables
?>

<body>
	<!-- <body class="Degrade"> -->
	<?php require_once 'navbar.php';?>
	<!-- affichage de la barre de navigation-->

	<img class="arrondie responsive" src="images/slide_1.jpg" width="100%" height="500px">
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>

	<!-- scenarii -->
	<h2 id="scenario" class=titre> Nouvelle partie</h2>
	<p>Choisissez un jeu parmi ceux proposés !</p>
	<br/>
	<br/>
	<?php


    /*/ affichage, pour chaque jeu, de son titre et de sa description*/
    foreach ($infoJeu as $ligne) {
        ?>
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class=zoom>
					<div class="overlay-image">
							<div class=image_scenario>
											<img class="arrondie" src="bdd\img\\<?php echo $ligne['imageJeu']?>" width="260" />
										<div class="text">
												<p>Temps moyen de jeu : <?php echo gmdate("H:i:s", strtotime($ligne['tmpsMoy']))?><br/>
													Difficulté : <?php for ($i=0; $i<$ligne['niv']; $i++) {?>
													<img class="image_niv" src="images\star.png" />
														<?php } ?>
												</p>
										</div>
								</div>				 
						</div>
				</div>
			</div>

				<div class="col-xs-12 col-sm-7 col-md-8">
					<h3>
							<?php if (isset ($_SESSION ['idEqu'])) { ?>
							<a class="TitreJeu" href="regles.php?idJeuEqu=<?php echo $ligne['idJeu']?>"
							style="color:black">
								<?php echo $ligne["titreJeu"]; } ?>
							</a>

							<?php if (empty ($_SESSION ['idEqu'])) { ?>
							<a class="TitreJeu" href="connexion.php" style="color:black">
								<?php	 echo $ligne["titreJeu"];} ?>
							</a>
					</h3>
					<?php echo $ligne["descriptionJeu"]; ?>
					<br/>
					<br/>
					<br/>
				</div>
		</div>
	</div>

	<?php
}
?>
	<br/>
	<br/>
	<br/>
	<br/>

	
	<br/>
	<br/>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7">
				<h2 id="apropos" class=titre> Qui sommes-nous? </h2>
				<br/>
				<!-- Qui sommes-nous? -->
				<h3> Deux étudiantes en 1ère année à l'ENSC</h3>

				<p> Originaires de formations différentes : Maurine venant de Prépa B/L et Maëlle de Licence de Biologie, nous sommes maintenant
					toutes les deux en première année à l'Ecole Nationale Supérieure de Cognitique. </p>

				<p> Cette école d'ingénieur fait partie du groupe Bordeaux INP, on y étudie la dualité homme/machine, les sciences numériques
					appliquées (programmation, intelligence artificielle, automatique) et également le facteur humain et la neurobiologie.
				</p>

				<p> Ce site web a été créé dans le cadre d'un projet en binôme au sein du module "Programmation WEB" </p>
				<br/><br/><br/>	<br/><br/><br/>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5">
				<div class="zoom">
				<!-- photo de nous -->
					<div class=image_apropos>
						<img class="arrondie" src="images/mjma.jpg" alt="Bootstrap">
						<br/><br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php require_once 'footer.php';?>	
</body>

<!-- JavaScript Boostrap plugin -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="jquery/jquery.min.js"></script>

</html>