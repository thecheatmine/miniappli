<?php

function message($msg) {
    $_SESSION['info'] = $msg;
}


//Fonction qui vérifie si une valeur ($var) de type ($type)
//par exemple "mail" ou "login" existe déjà dans la base de donnée
//Utile pour le login et le mail lors de l'inscription
function doExist($type, $valeur, $pdo) {
  $sql = "SELECT * FROM user WHERE $type=?";
  $query = $pdo->prepare($sql);
  $line = $query->fetch();
  $query->execute(array($valeur));
  if (!$line) {
    return false;
  }
  else {
    return true;
  }
}

//Fonction qui ajoute un post sur un un mur avec un id donné avec un auteur avec un id donné
function addPost($idAuteur, $idAmi, $contenu, $titre, $pdo) {
  $sql = "INSERT INTO ecrit (id, titre, contenu, dateEcrit, image, idAuteur, idAmi) VALUES (NULL, :titre, :contenu, current_timestamp(), NULL, :idAuteur, :idAmi)";
  $query = $pdo->prepare($sql);
  $query->execute(array(
    'titre' => $titre,
    'contenu' => $contenu,
    'idAuteur' => $idAuteur,
    'idAmi' => $idAmi
  ));
}

function removePost($idPost, $pdo) {
  $sql = "DELETE from ecrit WHERE id=?";
  $query = $pdo->prepare($sql);
  $query->execute(array($idPost));
  
}


//Fonction qui regarde dans la base de donnée quel est le pseudo de l'utilisateur avec un id donné
function idToUsername($id, $pdo) {
    $sql = "SELECT login FROM user WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($id)); 
    $line = $query->fetch();
    return $line['login'];
}

//Fonction qui regarde les demandes de liens en attentes pour un id1 donné
function checkRequest($id,$pdo) {
  $sql = 'SELECT * FROM lien WHERE idUtilisateur1=? AND etat=?';
    $query = $pdo->prepare($sql);
    $query->execute(array($id, 'attente'));
    $result = "";
    $ligne = $query->fetchall();
    if(!$ligne){
      $result = "Vous n'avez aucune demande d'amis en cours";
    }
    else{
      foreach($ligne as $line){
        $result .= idToUsername($line['idUtilisateur2'], $pdo)."<br>";
      }
    }
    return $result;
}

//Fonction qui regarde les demandes de liens en attentes pour un id2 donné
function checkRequest2($id,$pdo) {
  $sql = 'SELECT * FROM lien WHERE idUtilisateur2=? AND etat=?';
    $query = $pdo->prepare($sql);
    $query->execute(array($id, 'attente'));
    $result = "";
    $ligne = $query->fetchall();
    if(!$ligne){
      $result = "Vous n'avez aucune demande d'amis en cours";
    }
    else{
      foreach($ligne as $line){
        $result .= idToUsername($line['idUtilisateur1'], $pdo)."<a href='index.php?action=update&id2=".$_SESSION['id']."&id=".$line['idUtilisateur1']."&etat=ami'>Accepter</a><a href='index.php?action=update&id2=".$_SESSION['id']."&id=".$line['idUtilisateur1']."&etat=ami'>Refuser</a><a href='index.php?action=update&id2=".$_SESSION['id']."&id=".$line['idUtilisateur1']."&etat=banni'>Bloquer</a><br>";
      }
    }
    return $result;
}

function updateStatus($id, $id2, $etat, $pdo){
  if(checkStatus($id, $id2, $pdo)){
    $sql = "UPDATE lien
    SET etat = ?
    WHERE idUtilisateur1=? AND idUtilisateur2=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($etat, $id, $id2));
  }
  else {
    $sql = "INSERT INTO lien(id, idUtilisateur1, idUtilisateur2, etat) VALUES (NULL, :id, :id2, :etat)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'id' => $id,
      'id2' => $id2,
      'etat' => $etat
    ));
  }
}

//Fonction qui ajoute quelqu'un avec un id donné comme ami
function addFriend($id, $id2, $pdo) {

  if(checkStatus($id, $id2, $pdo) != 'ami'){
    $sql = "INSERT INTO lien(id, idUtilisateur1, idUtilisateur2, etat) VALUES (NULL, :id, :id2, :etat)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'id' => $id,
      'id2' => $id2,
      'etat' => "ami"
    ));
  }
  else {
    message("Vous êtes déjà amis.");
  }
}

function addBlock($id, $id2, $pdo) {

  if(checkStatus($id, $id2, $pdo) != 'ami'){
    $sql = "INSERT INTO lien(id, idUtilisateur1, idUtilisateur2, etat) VALUES (NULL, :id, :id2, :etat)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'id' => $id,
      'id2' => $id2,
      'etat' => "banni"
    ));
  }
  else {
    message("Vous avez déjà bloqué cette personne");
  }
}



//Fonction qui retourne le lien entre deux ids
function checkStatus($id, $id2, $pdo) {
    $sql = "SELECT etat FROM lien WHERE (idUtilisateur1=? AND idUtilisateur2=?) OR (idUtilisateur2=? AND idUtilisateur1=?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($id,$id2,$id2,$id));
    $ligne = $query->fetch();
    return $ligne['etat'];
}

function searchUser($recherche,$pdo) {
  if(strlen($recherche) < 3){
    message("Veuillez indiquer au moins 3 caractères lors de votre recherche.");
  }
  else{
    $id_array = [];
    $sql = "SELECT id FROM user WHERE login LIKE ? LIMIT 10";
    $query = $pdo->prepare($sql);
    $query->execute(array('%'.$recherche.'%'));
    while($ligne = $query->fetch())
      {
        array_push($id_array, $ligne['id']);
      }
    return $id_array;
  }
}

  function checklike($idEcrit, $idU, $pdo) {
    $sql = "SELECT * FROM aime WHERE idEcrit=? AND idUtilisateur=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($idEcrit, $idU));
    $line = $query->fetch();
    if($line){
      return false;
    }
    else{
      return true;
    }
  }

function like($idEcrit, $idU, $pdo) {
  if(checklike($idEcrit, $idU, $pdo)){
    $sql = "UPDATE ecrit SET nblike=(nblike+1) WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($idEcrit));

    $sql = "INSERT INTO aime(id, idEcrit, idUtilisateur) VALUES (NULL, :idEcrit, :id)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'idEcrit' => $idEcrit,
      'id' => $idU
    ));
    message("Vous aimez le post");
  }
  else{
    $sql = "UPDATE ecrit SET nblike=(nblike-1) WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($idEcrit));


    $sql = "DELETE FROM aime WHERE idEcrit=? AND idUtilisateur=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($idEcrit, $idU));
    message("Vous n'aimez plus le post");
  }
}

//Table lien : id / idUtilisateur1 / idUtilisateur2 / etat

//Table ecrit : id / titre / contenu / dateEcrit / image / idAuteur / idAmi / nblike

//Table user : id / login / mdp / email / remember / avatar

//Table aime : id / idEcrit / idUtilisateur