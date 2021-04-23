<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();
// cherche la video correspondante à l'énigme venant d'être résolue 
			$requetevideo = $BDD->prepare("SELECT videoEnigme FROM enigme, tentative WHERE enigme.idEnigme=tentative.idEnigme AND ordre=(:ordreAncienneEnigme) AND  idPart=:idPart");
			$requetevideo->bindValue('ordreAncienneEnigme', escape($_SESSION['enigmeEC']['ordre']) - 1, PDO::PARAM_STR);
			$requetevideo->bindValue('idPart', $_SESSION['partieEC']['idPart'], PDO::PARAM_STR);
			$requetevideo->execute();
            $video = $requetevideo->fetch()[0];
?>


<!doctype html>
<html>
<body style="background:gray">
<video class="video-bg" id="vid" playsinline autoplay muted onended="videoEnded()" width= "100%" height="100%" src="<?php echo"bdd\img\\".$video?>"> ici la vidéo</video>


<script> //permet de remettre la video à 0 pour les prochaines parties et de lancer la fonction vidéo ended une fois la video finie
document.querySelector('video').addEventListener('ended', function () {
 this.play();
 videoEnded();
})
// fonction qui redirige automatiquement vers la page enigme une fois la vidéo lue
function videoEnded() {
   window.location.replace("enigme.php");
}
</script>





</body>
</html>