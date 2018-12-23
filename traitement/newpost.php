<?php
  if(isset($_POST['idAuteur'])
  && isset($_POST['idAmi'])
  && isset($_POST['contenu'])
  && isset($_POST['titre'])){
    addPost($_POST['idAuteur'], $_POST['idAmi'], $_POST['contenu'], $_POST['titre'], $pdo);
    message("Votre message a été envoyé avec succès");
  }
  else {
    message("Il y a eu une erreur lors de l'envoi de votre message");
  }
header('Location: index.php?action=profil&id='.$_POST['idAmi']);

?>
