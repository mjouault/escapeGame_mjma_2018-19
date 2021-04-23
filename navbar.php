<header>

	<nav class="navbar navbar-expand-lg navbar-light" style="background-color : #EFEFEF">
		<img class="logo" src="images/logogris.png">
		<a class="navbar-brand" style="color:grey">E-scape Game</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
<!-- si on est pas connecté en tant que MJ (navigation equipe)-->
			<?php if (empty($_SESSION['loginMJ'])) { ?>
				<li class="nav-item">
					<a class="nav-link" href="index.php">Accueil
						<span class="sr-only">(current)</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php#apropos" >Qui sommes-nous?</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php#scenario">Jeux</a>
				</li>
<!-- si au contraire on est connecté en tant que MJ(navigation MJ) -->
			<?php } else { ?>
				<li class="nav-item active">
					<a class="nav-link" href="mj.php">Gestion des équipes
						<span class="sr-only">(current)</span>
					</a>
				</li>
			<?php } ?>

				<li>
					<div class="collapse navbar-collapse" id="navbar-collapse-target">
						<li class="nav-item dropdown">
<!-- si quelqu'un est connecté que ce soit une équipe ou un MJ -->
						<?php if (isset($_SESSION['loginEqu']) || isset($_SESSION['loginMJ'])) { ?>
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Bonjour <?php if (isset($_SESSION['loginEqu'])){ echo $_SESSION['loginEqu'];} else if (isset($_SESSION['loginMJ'])){ echo $_SESSION['loginMJ'];} ?></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
							</div>
						<?php } 
						// si personne n'est connecté
						else { ?>
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Se connecter</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="connexion.php">Connexion</a>
							</div>
						<?php } ?>

						</li>
					</div>
				</li>

			</ul>
		</div>
	</nav>

</header>