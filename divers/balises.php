<?php

function message($msg) {
    $_SESSION['info'] = $msg;
}


//Fonction qui vérifie si une valeur ($var) de type ($type)
//par exemple "mail" ou "login" existe déjà dans la base de donnée
//Utile pour le login et le mail lors de l'inscription
function doExist($type, $var) {

}


//Fonction qui regarde dans la base de donnée quel est le pseudo de l'utilisateur avec un id donné
function idToUsername($id) {

}


//Fonction qui ajoute un like à un utilisateur avec un id donné
function addLike($id) {

}


//Fonction qui ajoute quelqu'un avec un id donné comme ami
function addFriend($id) {

}


//Fonction qui vérifie si vous êtes l'ami de quelqu'un avec un id donné
function checkIfFriend($id) {
    
}