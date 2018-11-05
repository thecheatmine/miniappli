<?php

$sql = "SELECT * FROM user WHERE login=? AND mdp=PASSWORD(?)";

// Etape 1  : preparation
$query = $pdo->prepare($sql); // Etape 1 : Préparation de la requête

// Etape 2 : execution : 2 paramètres dans la requêtes !!

$query->execute(array($_POST['login'], $_POST['password']));  // Etape 2 : exécution de la requête

// Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.

// un seul fetch
$line = $query->fetch();

// Si $line est faux le couple login mdp est mauvais, on retourne au formulaire
if($line){
    $_SESSION['id'] = $line['id'];
    $_SESSION['login'] = $line['login'];
    header('Location: /~valentin.wojtasinski/miniappli/index.php');
    exit();
}
else{
    echo "Mauvais couple identifiants";
    header('Location: /~valentin.wojtasinski/miniappli/index.php?action=login');
    exit();
}
// sinon on crée les variables de session $_SESSION['id'] et $_SESSION['login'] et on va à la page d'accueil
?>