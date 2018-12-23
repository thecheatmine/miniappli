<?php

$compteurverif = 0;
$verif = false;


if(empty($_POST)
|| empty($_POST['login'])
|| empty($_POST['email'])
|| empty($_POST['password'])
|| empty($_POST['password2'])){
    header('Location: index.php?action=signup&erreur=1');
    exit;
}

//Vérifie si quelqu'un a déjà ce login
if(doExist("login", $_POST['login'], $pdo)){
    header('Location: index.php?action=signup&erreur=2');
    exit;
}

//Vérifie que le mail est valide
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    header('Location: index.php?action=signup&erreur=3');
    exit;
}

//Vérifie si quelqu'un a déjà ce mail
if(doExist("email", $_POST['email'], $pdo)){
    header('Location: index.php?action=signup&erreur=4');
    exit;
}

//Vérifie si les deux mots de passe sont identiques
if($_POST['password'] != $_POST['password2']){
    header('Location: index.php?action=signup&erreur=5');
    exit;
}


//Arrivé ici 
$login = $_POST['login'];
$mail = $_POST['email'];
$pass = $_POST['password'];

$sql = "INSERT INTO user VALUES(NULL,?,PASSWORD(?),?,NULL,NULL)";
$query = $pdo->prepare($sql);
$query->execute(array($login,$pass,$mail));

if(isset($_POST['connect'])){
  // header('Location: index.php?');
  $sql = "SELECT id FROM user WHERE login=?";
  $query = $pdo->prepare($sql);
  $query->execute(array($login));
  $line = $query->fetch();
  $_SESSION['id'] = $line['id'];
  $_SESSION['login'] = $login;
  message("Bienvenue Avengers! Voici votre email : ".$mail." et votre login :".$login);
  header('Location: index.php');
  exit();
}
else{
  message("Bienvenue Avengers! Voici votre email : ".$mail." et votre login :".$login);
  header('Location: index.php?action=login');
};

 /////////////////////////GERER MEME LOGIN
?>
