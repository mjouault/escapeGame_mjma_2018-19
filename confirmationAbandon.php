<?php
session_start(); 
require_once 'fonctions.php';
$BDD = connect();
	$requeteChgtStadeTentative = $BDD->prepare("UPDATE tentative, partie SET codeStade='A', dateFin=NOW() WHERE codeStade='EC' AND partie.idEqu=:idEqu");
$requeteChgtStadeTentative->bindValue('idEqu', escape($_SESSION['idEqu']), PDO::PARAM_STR);
$requeteChgtStadeTentative->execute();
header('Location:resultats.php');
?>