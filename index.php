<?php

  include("config/config.php");
  include("config/bd.php"); // commentaire
  include("divers/balises.php");
  include("config/actions.php");
  session_start();
  ob_start(); // Je démarre le buffer de sortie : les données à afficher sont stockées

  if(isset($_COOKIE["connexion-auto"]) && !isset($_SESSION['id'])){
    $sql = "SELECT login FROM user WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($_COOKIE["connexion-auto"]));
    $line = $query->fetch();
    $_SESSION['id'] = $_COOKIE["connexion-auto"];
    $_SESSION['login'] = $line['login'];
    message("Vous avez été reconnecté avec succès");
    header('Location: index.php');
  }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Portail Avengers</title>

    <!-- Bootstrap core CSS -->
    <!-- <link href="./css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Ma feuille de style à moi -->
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet"> 
    <!-- <script src="js/jquery-3.2.1.min.js"></script> -->
    <!-- <script src="js/bootstrap.min.js"></script> -->
</head>

<body>

<?php
if(isset($_SESSION['info'])){
  ?>
  <div id="informations">
    <?php echo $_SESSION['info']; ?>
  </div>
  <?php
  unset($_SESSION['info']);
}
?>


<header>
    <h1><a href="index.php">Portail <strong>AVENGERS</strong></a></h1>
</header>
<nav>
<?php
        if (isset($_SESSION['id'])) {
            echo "<a href='index.php'><img src='img/iconeaccueil.jpg' /></a>";
            echo "<a href='index.php?action=profil&id=".$_SESSION['id']."'><img src='img/iconprofil.jpg' /></a>";
            echo "<a href='index.php?action=deconnexion'><img src='img/logologout.jpg' /></a>";
        } else {
            echo "<a href='index.php'><img src='img/iconeaccueil.jpg' /></a>";
            echo "<a href='index.php?action=signup'><img src='img/iconesignup.jpg' /></a>";
            echo "<a href='index.php?action=login'><img src='img/logologin.jpg' /></a>";
        }
        ?>
</nav>

<?php
// Quelle est l'action à faire ?
if (isset($_GET["action"])) {
    $action = $_GET["action"];
} else {
    $action = "accueil";
}

// Est ce que cette action existe dans la liste des actions
if (array_key_exists($action, $listeDesActions) == false) {
    include("vues/404.php"); // NON : page 404
} else {
    include($listeDesActions[$action]); // Oui, on la charge
}

ob_end_flush(); // Je ferme le buffer, je vide la mémoire et affiche tout ce qui doit l'être
?>

</body>
</html>
