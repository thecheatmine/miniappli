<?php
  if(isset($_GET['idPost']))
  {
    removePost($_GET['idPost'], $pdo);
    message("Le message a été supprimée avec succès.");
  }
  else {
    message("Une erreur est survenue");
  }
header('Location: index.php?action=profil&id='.$_SESSION['id']);

?>
