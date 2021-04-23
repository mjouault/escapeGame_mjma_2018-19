<?php
session_start();
require_once 'fonctions.php';
/* -----------------------------------------------------
Demande de confirmation d'abandon
---------------------------------------------------------
 */
if( !isset ($_SESSION['idEqu']))
{ header('Location:connexion.php');}
else{?>
<!doctype html>
<?php require_once 'head.php';?>
<html>

	<body>
		<?php require_once 'navbar.php';?>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-xs-5 col-lg-5" style="margin-top: 5%;">
				<img width="50%" src="images/door.png" class="responsive">
				</div>
				<div class="col-sm-12 col-xs-7 col-lg-7" style="width:45%; margin-top: 10%;">
					<h3 class="abandon">Etes-vous sûrs de vouloir abandonner la partie ?</h3>
					<p>Vous ne pourrez plus revenir en arrière, réfléchissez-y ..</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 col-lg-12" style="text-align:center;">
					<a href="confirmationAbandon.php" class="btn" style="margin-right:5%;">Oui</a>
					<a href="enigme.php" class="btn">Non, on continue l'aventure !</a>
				</div>
			</div>
		</div>
			<!-- redirige vers page de résultats si confirmation d'abandon -->
		<div class=footer style="position: absolute;">
		<?php
		require_once 'footer.php'
		?>
		</div>
	</body>
</html>
<?php } ?>