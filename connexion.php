<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();

if (isset($_POST['login']) && isset($_POST['mdp']) && isset($_POST['statut'])) {
	$verif = false;

    if ($BDD) {

        if ($_POST['statut'] == "equipe") {
            $MaRequete = "SELECT* FROM equipe"; 
            $rslt = $BDD->query($MaRequete);
            $team = $rslt->fetchAll();

            foreach ($team as $ligne) {
                if ($_POST['login'] == $ligne["loginEqu"] && $_POST['mdp'] == $ligne["mdpEqu"]) {
                    $verif = true;
                    $_SESSION['loginEqu'] = $_POST['login'];
                    $_SESSION['idEqu'] = $ligne['idEqu'];
                }
            }
        } else {
            $MaRequete = "SELECT* FROM mj"; 
            $rslt = $BDD->query($MaRequete);
            $team = $rslt->fetchAll();
            foreach ($team as $ligne) {
                if ($_POST['login'] == $ligne["loginMJ"] && $_POST['mdp'] == $ligne["mdpMJ"]) {
                    $verif = true;
					$_SESSION['loginMJ'] = $_POST['login'];
					$_SESSION['idMJ'] = $ligne['idMJ'];
                }
            }
        }
	}
	if ($verif) {
        if ($_POST['statut'] == "MJ") {
            header('Location:mj.php');
            exit();
        } else {
            header('Location:index.php#scenario');
            exit();
        }

	}
}
?>

<!doctype html>
<html>
<?php
require_once 'head.php';
require_once 'navbar.php';
	?>
<body class="signUp">
<?php if (isset($verif) && $verif==false) { ?>
<div class="alert alert-danger">
	<strong>Erreur!</strong>
	Utilisateur non reconnu
</div>
<?php }?>
<div class="container">
	<div class="row">
		<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
			<div class="card card-signin my-5">
				<div class="card-body">
					<h4 class="card-title text-center">Se connecter</h4>
					<form class="form-signin" action="connexion.php" method="post">
						<div class="form-label-group">

							<h5 id="titleStatus"> Qui êtes-vous ? </h5>
							<div class="statusSelector" id="statut">
								<input type="radio" name="statut" value="MJ" id="MJ" class="champ" checked="" /> Un maitre du jeu
								<br/>
								<input type="radio" name="statut" value="equipe" id="equipe" class="champ" checked="checked" />Un joueur
								<br/>
							</div>

						</div>

						<div class="form-label-group">
							<input type="login" id="inputlogin" class="form-control" name="login" placeholder="Login" required autofocus>
							<label for="inputlogin">Identifiant</label>
						</div>
						<div class="form-label-group">
							<input type="password" id="inputPassword" class="form-control" name="mdp" placeholder="Password" required>
							<label for="inputPassword">Mot de passe</label>
						</div>

						<button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Envoyer</button><br/>
						<a class='sinscrire' name="noCompte" href="inscription.php" style="color: #032438; margin-left:70px;"> Oups, je n'ai pas encore de compte... </a><br/><br/>
						<a class='sinscrire' name="noCompte" href="mdpOublie.php" style="color: #032438; margin-left:100px;"> Mot de passe oublié ? </a>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once 'footer.php';?>
	</body>

</html>