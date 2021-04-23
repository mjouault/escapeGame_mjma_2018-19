<?php
require_once 'fonctions.php';
$BDD = connect();
require_once 'head.php';
require_once 'navbar.php';
//requete récup ancien mdp 

if (isset ($_POST['loginEqu']) && isset($_POST['nvMdp']) && isset ($_POST['nomEqu']))
{
    $recupMdp=$BDD->prepare("SELECT mdpEqu, idEqu FROM equipe WHERE loginEqu=:loginEqu AND nomEqu=:nomEqu");
    $recupMdp->bindValue('loginEqu', escape($_POST['loginEqu']), PDO::PARAM_STR);
    $recupMdp->bindValue('nomEqu', escape($_POST['nomEqu']), PDO::PARAM_STR);
    $recupMdp->execute();
   $ancienMdp= $recupMdp->fetch();

    if (isset ($ancienMdp))
    {
            $requeteNvMdp = $BDD->prepare("UPDATE equipe SET mdpEqu=:nvMdp WHERE idEqu=:idEqu");
            $requeteNvMdp->bindValue('nvMdp', escape($_POST['nvMdp']), PDO::PARAM_STR);
            $requeteNvMdp->bindValue('idEqu', escape($ancienMdp['idEqu']), PDO::PARAM_STR);
            $requeteNvMdp->execute();
    }
    else {?>
    <div class="alert alert-danger">
	<strong>Erreur!</strong>
	Utilisateur non reconnu
    </div>
    <?php
    }
}
?>

<html>
<header>
</header>
    <body>
		<div class="container">
			<div class="row">
				<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
					<div class="card card-signin my-5">
						<div class="card-body">
                            <h4 class="card-title text-center">Mot de passe oublié ?</h4>
                            <form action="mdpOublie.php" method="post">
                                <div class="form-label-group">
								<input type="text" class="form-control" name="loginEqu" id="login" placeholder="login" required>
								<label for="login">Votre login</label>
                                </div>
                                <div class="form-label-group">
								<input type="text" class="form-control" name="nomEqu" id="nomEqu" placeholder="nom d'équipe" required>
								<label for="nomEqu">Votre nom d'équipe</label>
                                </div>
                                <div class="form-label-group">
								<input type="password" class="form-control" name="nvMdp" id="inputNvMdp" placeholder="nouveau mot de passe" required>
								<!-- obligatoire-->
								<label for="inputNvMdp">Nouveau mot de passe</label>
							    </div>

                                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Envoyer</button>

                            </form>
                        </div>
                    </div>
                <div>
            <div>
        </div>
    </body>
</html>