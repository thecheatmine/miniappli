<?php
  if(isset($_GET['idE']) && isset($_GET['idU'])) {
    like($_GET['idE'], $_GET['idU'], $pdo);
  }
  else {
    message("Une erreur est survenue");
  }
  header('Location: index.php?action=profil&id='.$_SESSION['id']);
?>