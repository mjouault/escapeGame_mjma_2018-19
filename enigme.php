<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();

/* -----------------------------------------------------
[equipe] Page d'affichage des énigmes
---------------------------------------------------------
 */

// ACCES UNIQUEMENT EN TANT QUE JOUEUR
if (!isset($_SESSION['idEqu']))
{
	header('Location:connexion.php');
}
else
{
	$BDD = connect();
	$idEqu = $_SESSION['idEqu'];
	
		//requête pour trouver la dernière énigme du jeu
			$requeteMaxEnigme =$BDD->prepare("SELECT MAX(ordre) FROM enigme WHERE idJeu =:idJeu");
			$requeteMaxEnigme->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
			$requeteMaxEnigme->execute();
			$_SESSION['nbEnigmesJeu']=$requeteMaxEnigme->fetch()[0];

	//requete pour trouver la partie en cours de l'équipe

	// 1) s'il y en a plusieurs, recherche de l'identifiant maximal des parties jouées
	$requetePartieMax=$BDD->prepare("SELECT MAX(partie.idPart) FROM partie WHERE idEqu=:idEqu AND idJeu=:idJeuEqu"); //ajouter idJeu --> ajouter ['idPart'] partout où idPart est appelé
	$requetePartieMax->bindValue('idEqu', escape($idEqu), PDO::PARAM_STR);
	$requetePartieMax->bindValue('idJeuEqu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
	$requetePartieMax->execute();
	$partieMax=$requetePartieMax->fetch()[0];

	// 2) recherche de la partie en cours ayant cet identifiant maximal
	$requetePartie=$BDD->prepare("SELECT partie.idPart, idJeu, partieFinie, partieReussie FROM partie WHERE idPart=:idPart");
	$requetePartie->bindValue('idPart', escape($partieMax), PDO::PARAM_STR);
	$requetePartie->execute();
	$partieEC=$requetePartie->fetch();
	$idPart=$partieEC['idPart'];
	$_SESSION['partieEC']= $partieEC;

	//s'il n'en existe pas ou si la dernière énigme comprise dedans est résolue, on crée une nouvelle partie
	if (empty($idPart) || $partieEC['partieFinie']==true)
	{
		$insertionNvllePartie = $BDD->prepare("INSERT INTO partie (idEqu, idJeu) VALUES (:idEqu,:idJeu)");
		$insertionNvllePartie->bindValue('idEqu', escape($idEqu), PDO::PARAM_STR);
		$insertionNvllePartie->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
		$insertionNvllePartie->execute();

		$requetePartieMax->execute();
		$partieMax=$requetePartieMax->fetch()[0];

		$requeteNvllePartie=$BDD->prepare("SELECT partie.idPart, idJeu, partieFinie FROM partie WHERE idPart=:idPart");
		$requeteNvllePartie->bindValue('idPart', escape($partieMax), PDO::PARAM_STR);
		$requeteNvllePartie->execute();
		$partieEC=$requeteNvllePartie->fetch();
		$idPart=$partieEC['idPart'];
		$_SESSION['partieEC']= $partieEC;
	}

	$requeteEnigme= $BDD->prepare("SELECT enigme.idEnigme, ordre, titreEnigme,enonceEnigme,imageEnigme, repUnique, videoEnigme FROM enigme, tentative WHERE idPart=:idPart AND codeStade='EC' AND tentative.idEnigme= enigme.idEnigme");
	$requeteEnigme->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
	$requeteEnigme->execute();
	$enigmeEC= $requeteEnigme->fetch();
	$_SESSION['enigmeEC']=$enigmeEC;

	// si tentative en cours inexistante, il s'agit de la 1ere tentative
	if (empty($enigmeEC['idEnigme'])) 
	{ 
		//récupération de l'identifiant de la 1ere énigme du jeu
		$requeteIdEnigme1= $BDD->prepare("SELECT enigme.idEnigme FROM enigme WHERE idJeu=:idJeu AND ordre=1");
		$requeteIdEnigme1->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
		$requeteIdEnigme1->execute();
		$idEnigme1=$requeteIdEnigme1->fetch()[0];

		//création d'une nouvelle tentative
		$requeteNvlleTentative = $BDD->prepare("INSERT INTO tentative (dateDebut, idEnigme, codeStade, idPart)
													VALUES (NOW(),:idEnigme, 'EC', :idPart)");
		$requeteNvlleTentative->bindValue('idEnigme', escape($idEnigme1), PDO::PARAM_STR);
		$requeteNvlleTentative->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
		$requeteNvlleTentative->execute();

		// Rappel d'un requete précédente
		$requeteEnigme->execute();
		$enigmeEC = $requeteEnigme->fetch();
		$_SESSION['enigmeEC'] = $enigmeEC;
	}

	//recherche de l'indice associé à l'énigme en cours
	$requeteTmpsIndice = $BDD->prepare("SELECT nbSecMaxIndice FROM indice WHERE idEnigme=:idEnigme");
	$requeteTmpsIndice->bindValue('idEnigme', escape($enigmeEC['idEnigme']), PDO::PARAM_STR);
	$requeteTmpsIndice->execute();
	$tmpsIndice = $requeteTmpsIndice->fetch()[0];

	$_SESSION['indice'] = $tmpsIndice;

	// Si bonne réponse, Affichage de l'énigme suivante
	if (isset($_POST["rep"]) && escape($_POST["rep"] == $enigmeEC['repUnique'])) 
	{
		//vérif de l'ordre de l'énigme 
		$requeteDerniereEnigme=$BDD->prepare("SELECT enigme.idEnigme, codeStade FROM tentative, enigme WHERE tentative.idEnigme=enigme.idEnigme AND ordre=:ordre AND idPart=:idPart");
        $requeteDerniereEnigme->bindValue('ordre', escape($_SESSION['nbEnigmesJeu']), PDO::PARAM_STR);
        $requeteDerniereEnigme->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
        $requeteDerniereEnigme->execute();
		$DerniereEnigme=$requeteDerniereEnigme->fetch();

		//Cas1 : cétait la dernière énigme qui vient d'être réalisée
		if (escape($DerniereEnigme['codeStade'])=="EC")
		{
			//passage à terminé
			$requeteChgtStadeTentative = $BDD->prepare("UPDATE tentative SET codeStade='T', dateFin=NOW() WHERE idEnigme=(:idEnigme)");
			$requeteChgtStadeTentative->bindValue('idEnigme', escape($enigmeEC['idEnigme']), PDO::PARAM_STR);
			$requeteChgtStadeTentative->execute();

			//affichage des résultats
			header("Location:resultats.php"); //pas sécurisé
		}
		else // Cas2 :pas la dernière énigme
		{
			//2) recherche de la prochaine énigme
			$requeteNvlleEnigme = $BDD->prepare("SELECT enigme.idEnigme, ordre FROM enigme, jeu WHERE ordre = (:ordreNvlleEnigme) AND jeu.idJeu=enigme.idJeu AND enigme.idJeu=:idJeu");
			$requeteNvlleEnigme->bindValue('ordreNvlleEnigme', escape($enigmeEC['ordre']+1), PDO::PARAM_STR);
			$requeteNvlleEnigme->bindValue('idJeu', escape($_SESSION['idJeuEqu']), PDO::PARAM_STR);
			$requeteNvlleEnigme->execute();
			$NvlleEnigme= $requeteNvlleEnigme->fetch();

			//Ancienne énigme définie comme résolue par l'équipe (Passage du stade "en cours" à "terminée")
			$requeteChgtStadeTentative = $BDD->prepare("UPDATE tentative SET codeStade='T', dateFin=NOW() WHERE idEnigme=(:idAncienneEnigme)");
			$requeteChgtStadeTentative->bindValue('idAncienneEnigme', escape($enigmeEC['idEnigme']), PDO::PARAM_STR);
			$requeteChgtStadeTentative->execute();
		}
		
		//rappel d'une requête précédente
		$requetePartie->execute();
		$partieEC=$requetePartie->fetch();
		$idPart=$partieEC['idPart'];
		$_SESSION['partieEC']= $partieEC;

		//3)Création d'une nouvelle tentative
		$requeteNvlleTentative = $BDD->prepare("INSERT INTO tentative (dateDebut, idPart, idEnigme, codeStade)
													VALUES (NOW(),:idPart,:idEnigme, 'EC')");
		$requeteNvlleTentative->bindValue('idPart', escape($idPart), PDO::PARAM_STR);
		$requeteNvlleTentative->bindValue('idEnigme', escape($NvlleEnigme['idEnigme']), PDO::PARAM_STR);
		$requeteNvlleTentative->execute();		

		if ($enigmeEC['ordre']%3==0)
		{
			header("Location:video.php");
		}
	}
	// si mauvaise réponse, affichage d'un message d'erreur
	else if (isset($_POST["rep"]) && escape($_POST["rep"] != $enigmeEC['repUnique'])) { 
		$err = true;
	}

	// Requête pour afficher l'énigme en cours de résolution
	$requeteEnigme->execute();
	$enigmeEC= $requeteEnigme->fetch();
	$_SESSION['enigmeEC']=$enigmeEC;
?>


<!doctype html>
<html>

	<!-- HEAD -->
	<?php require_once 'head.php'; ?>
	
	<body class="enigmeStyle" style="background-image: url('<?php echo " bdd/img//".$enigmeEC['imageEnigme']?>')">
	
		<!-- HEADER -->
		<?php require_once 'navbar.php'; ?>

		<!-- MESSAGE D'ERREUR -->
		<?php if(isset($err)) { ?>	
			<div class="alert alert-danger">
				<strong>Oups, ce n'est pas la bonne réponse...</strong>
				Essayez à nouveau !
			</div>
		<?php } ?>

		<div class="big-container">

			<!-- affichage du titre de l'énigme en cours -->
			<section class="container-enigme">
				<div class="enigme-details">
					
					<h4 class="card-title text-center">Enigme n°<?= $enigmeEC["ordre"] ?> : <?= $enigmeEC["titreEnigme"] ?></h4>
				
					<p><?= $enigmeEC["enonceEnigme"] ?></p>

					<form class="form-signin" action="enigme.php" method="POST">
						<div class=form-label-group>
							<input type="text" class="form-control" name="rep" id="inputrep" placeholder="Tapez votre réponse" required autofocus>
						</div>
									
						<button class="btn" type="submit">Envoyer</button>
					</form>
							
				</div>
				
				<!-- indice -->
				<div>
					Un indice s'affichera ici au bout de <?= $_SESSION['indice'] ?> secondes...
					<iframe class="arrondie" src="indiceRafaichi.php"></iframe>
				</div>			
			</section>
			<!-- Fin container enigme -->

			<section class="right-enigme">
				<!-- Chat -->
				<div class="chat-btn">
					<?php require_once 'chat.php'; ?>
				</div>

				<!-- Abandonner -->
				<div class="abandonner">
					<a href="abandonner.php" class="btn">Abandonner</a>
				</div>
			</section>

		</div>
		
		<!-- FOOTER -->
		<?php require_once 'footer.php'; ?>
		<!-- JavaScript Boostrap plugin -->
		<script src="jquery/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
<?php
}
