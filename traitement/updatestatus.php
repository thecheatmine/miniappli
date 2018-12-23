<?php
  if(isset($_GET['id']) && isset($_GET['id2']) && isset($_GET['etat'])) {
    updateStatus($_GET['id'], $_GET['id2'], $_GET['etat'], $pdo);
  }
  else {
    message("Une erreur est survenue");
  }
  header('Location: index.php?action=profil&id='.$_SESSION['id']);
?>