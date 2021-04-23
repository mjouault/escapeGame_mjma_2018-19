<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();

//vérification s'il s'agit bien d'une équipe connectée qui arrive sur cette page
if (isset($_SESSION['idEqu'])){

	//récupération de l'identifiant du jeu choisi pour en fair eune varibale de session qui sera réutilisée dans tout le code
	if (isset($_GET['idJeuEqu']))
	{
		$_SESSION['idJeuEqu']= $_GET['idJeuEqu'];
	}		
?>
<!doctype html>
<html>
	<!-- HEAD -->
	<?php require_once 'head.php';?>

	<body>
		<!-- HEADER -->
		<?php require_once 'navbar.php';?>

		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-md-10 col-lg-10 mx-auto">
					<div class="card card-signin my-5">
						<div class="card-body">
<!-- affichage des règles du jeu -->
							<h4 class="card-title text-center">Règles du jeu</h4>
							<p> En équipe allant de 1 à 6 joueurs maximum, répondez aux différentes énigmes pour aider les protagonistes à s'en sortir.
							Vous bloquez sur une question ?  Pas de panique, un indice est généré au bout d'un temps indiqué. Vous avez également la possibilité de communiquer à tout moment avec un maître du jeu via le chat. 
							Ecrivez sans les réponses sans accent et sans déterminant pour éviter toute ambiguité de saisie. Veillez à  commencer par une majuscule pour les noms propres uniquement.
							Tentez de répondre le plus vite possible aux énigmes.Au bout de 3 énigmes résolues, revivez à nouveau les meilleurs moment de vos films préférés. 
							Bonne chance ! 
							</p>
							<!-- Bouton qui redirige vers enigme une fois qu'on a lu les règles -->
							<a href="enigme.php"><input class="btn" type="button" value="C'est parti!"></a>

						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- FOOTER -->
		<?php require_once 'footer.php'; ?>
		<!-- JavaScript Boostrap plugin -->
		<script src="jquery/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		
	</body>

</html>
<?php } else {header('Location: connexion.php');}