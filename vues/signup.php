<?php 
$erreur = 0;
if(isset($_GET['erreur'])){
    $erreur = $_GET['erreur'];
}
?>
<div class="bloc">
    <h2>Créer un compte</h2>
    <form action="index.php?action=newsignup" method="POST">
        <label for="login">Nom d'utilisateur</label>
        <input id="login" type="text" name="login" placeholder="Spider-Man">

        <label for="mail">Adresse mail</label>
        <input id="mail" type="mail" name="email" placeholder="spidey@mail.com">

        <label for="password">Mot de passe
        <?php if($erreur == 5) echo "<h4 id='red'>Mots de passe incorrects ou différents</h4>"; ?></label>
        <input id="password" type="password" name="password" placeholder="&bull;&bull;&bull;&bull;">

        <label for="password2">Confirmation du mot de passe</label>
        <input id="password2" type="password" name="password2" placeholder="&bull;&bull;&bull;&bull;">

        <label class="container">Me connecter directement
            <input class="button-direct" type="checkbox" name="connect">
            <span class="checkmark"></span>
        </label>

        <input type="submit">
    </form>
</div>