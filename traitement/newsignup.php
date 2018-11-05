<?php

if(empty($_POST)
|| empty($_POST['login'])
|| empty($_POST['email'])
|| empty($_POST['password'])
|| empty($_POST['password2'])){
    header('Location: index.php?action=signup&erreur=1');
    exit;
}

//Vérifie si quelqu'un a déjà ce login
if(doExist("login", $_POST['login'])){
    header('Location: index.php?action=signup&erreur=2');
    exit;
}

//Vérifie que le mail est valide
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    header('Location: index.php?action=signup&erreur=3');
    exit;
}

//Vérifie si quelqu'un a déjà ce mail
if(doExist("mail", $_POST['email'])){
    header('Location: index.php?action=signup&erreur=4');
    exit;
}

//Vérifie si les deux mots de passe sont identiques
if($_POST['password'] != $_POST['password2']){
    header('Location: index.php?action=signup&erreur=5');
    exit;
}

//Arrivé ici 
echo "U win";

?>
