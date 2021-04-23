<?php
/* -----------------------------------------------------
[equipe] Inscription sur le site
---------------------------------------------------------
 */
require_once 'fonctions.php';
$BDD = connect();

//Insertion des réponses au formulaire d'inscription dans la BDD escape_game

// on teste si les variables du formulaire sont bien déclarées (image facultative)
if (isset($_POST['loginEqu'], $_POST['mdp'], $_POST['nbjoueur'], $_POST['nomEqu'])) {
	
	//recherche ds la BDD si l'équipe existe déjà
	$requeteEqu=$BDD->prepare("SELECT idEqu FROM equipe WHERE nomEqu=:nomEqu AND loginEqu=:loginEqu");
	$requeteEqu->bindValue('nomEqu', escape($_POST['nomEqu']), PDO::PARAM_STR);
	$requeteEqu->bindValue('loginEqu', escape($_POST['loginEqu']), PDO::PARAM_STR);
	$requeteEqu->execute();
	$infoEqu=$requeteEqu->fetch()[0];

	if (isset($infoEqu))
	{$reconnu=true;}
	else
	{
	$insertionEqu = $BDD->prepare("INSERT INTO equipe (loginEqu, mdpEqu, nbjoueurs, nomEqu)
		VALUES (:loginEqu,:mdpEqu,:nbjoueurs,:nomEqu)");

	// sécurisation des données entrées par l'utilisateur
    $loginEqu = escape($_POST['loginEqu']);
    $mdpEqu = escape($_POST['mdp']);
    $nbjoueur = escape($_POST['nbjoueur']);
	$nomEqu = $_POST['nomEqu'];

    $insertionEqu->bindValue('loginEqu', $loginEqu, PDO::PARAM_STR);
    $insertionEqu->bindValue('mdpEqu', $mdpEqu, PDO::PARAM_STR);
    $insertionEqu->bindValue('nbjoueurs', $nbjoueur, PDO::PARAM_STR);
	$insertionEqu->bindValue('nomEqu', $nomEqu, PDO::PARAM_STR);
	$insertionEqu->execute();

	//une fois inscrit, redirection vers la page de connexion
	header('Location:connexion.php');
	}
}
?>
<!doctype html>
<html>
<?php
require_once 'head.php';
require_once 'navbar.php';
?>

<!-- formulaire d'inscription -->

<body class="signUp">
<?php if(isset($reconnu)){?>
<div class="alert alert-danger">
	<strong>Erreur!</strong>
	Une équipe du même nom existe déjà. Veuillez changer le nom et le login.
</div><?php }?>
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-body">
						<h5 class="card-title text-center">Incrire une équipe</h5>
						<form class="form-signin" action="inscription.php" method="post">
							<!-- Traitement des réponses sur la même page -->

							<div class="form-label-group">
								<!-- Entrée d'un identifiant d'équipe-->
								<input type="login" class="form-control" name="loginEqu" id="inputlogin" placeholder="Login" required autofocus>
								<!-- obligatoire pour envoyer-->
								<label for="inputlogin">Identifiant</label>
							</div>

							<!-- Entrée d'un mot de passe -->
							<div class="form-label-group">
								<input type="password" class="form-control" name="mdp" id="inputpassword" placeholder="Password" required>
								<!-- obligatoire-->
								<label for="inputpassword">Mot de passe</label>
							</div>

							<!-- Entrée d'un nombre de joueurs dans l'équipe -->
							<div class="form-label-group">
								<input type="number" class="form-control" name="nbjoueur" id="nbjoueur" min="1" max="6" required>
								<label for="nbjoueur">Nombre de joueurs</label>
							</div>

							<!-- Entrée d'un nom d'équipe (peut être différent du login) -->
							<div class="form-label-group">
								<input type="text" class="form-control" name="nomEqu" id="inputnom" placeholder="text" required>
								<!-- obligatoire-->
								<label for="inputnom">Nom d'équipe</label>
							</div>
							<button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Envoyer</button>

						</form>
					</div>
				</div>
			</div>
		</div>	
	</div>
<?php require_once 'footer.php';?>
</body>

</html>