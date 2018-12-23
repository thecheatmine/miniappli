<?php
  unset($_SESSION['login']);
  unset($_SESSION['id']);
  setcookie("connexion-auto",$line['id'] , time() -5);
  header('Location: index.php');
?>