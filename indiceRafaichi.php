<?php
session_start();
require_once 'fonctions.php';
$BDD = connect();
//$idEqu = $_SESSION['idEqu'];

//chercher + afficher indice?>

<!doctype html>
<html>

<head>
	<meta http-equiv="refresh" content="1">
</head>

<body>
	<?php
$_SESSION['indice']--;

if ($_SESSION['indice'] <= 0) {
    $requeteIndice = $BDD->prepare("SELECT enonceIndice FROM indice WHERE idEnigme=:idEnigme");
    $requeteIndice->bindValue('idEnigme', escape($_SESSION['enigmeEC']['idEnigme']), PDO::PARAM_STR);
    $requeteIndice->execute();
    $indice = $requeteIndice->fetch()[0];
    ?> 
    <p style="text-align : center; font-family : calibri;"> <?php echo $indice; ?></p>
<?php }?>
</body>

</html>