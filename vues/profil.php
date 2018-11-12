<?php
    if(!isset($_SESSION["id"]) || !isset($_GET['id'])) {
        // On n est pas connecté, il faut retourner à la pgae de login
        header("Location:index.php?action=login");
    }

    // On veut affchier notre mur ou celui d'un de nos amis et pas faire n'importe quoi
    $ok = false;
    $own = false;

    if(!isset($_GET["id"]) || $_GET["id"]==$_SESSION["id"]){
        $id = $_SESSION["id"];
        // On a le droit d afficher notre mur
        $ok = true;
    } else {
        $id = $_GET["id"];
        // Verifions si on est amis avec cette personne
        $sql = "SELECT * FROM lien WHERE etat='ami'
                AND ((idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?)))";

        // les deux ids à tester sont : $_GET["id"] et $_SESSION["id"]
        // A completer. Il faut récupérer une ligne, si il y en a pas ca veut dire que lon est pas ami avec cette personne
    }
    if($ok==false) {
        echo "Vous n êtes pas encore ami, vous ne pouvez voir son mur !!";       
    } else {
    // A completer
    // Requête de sélection des éléments dun mur
        // SELECT * FROM ecrit WHERE idAmi=? order by dateEcrit DESC
        // le paramètre  est le $id
    }

    $profilUsername = idToUsername($_GET['id']);
?>


<div class="bloc">
    <h2>Profil de <?php echo $profilUsername; ?></h2>
    <p><img class="avatar" src="./img/avatar/avatar.png"></p>
</div>
<div class="bloc">
    <h2>A propos</h2>
    <p>     Bio :
        <br>Âge :
        <br>Sexe :
        <br>Travail : 
    </p>
</div>
<div class="bloc">
    <?php
    // On veut que le formulaire est été soumis et que l'année est un nombre
    if($ok) { 
        $sql = "SELECT * FROM ecrit WHERE idAuteur=?"; // 
        $query = $pdo->prepare($sql); // Etpae 1 : On prépare la requête
                                    //  et celle-ci a un paramètre optionnel
        
        
        $query->execute(array($_GET['id'])); // Etape 2 :On l'exécute. 
                                                // On remplace le ? par l'année donnée 
        
        echo "<h2>Derniers posts</h2>";

        while($line = $query->fetch()) { // Etape 3 : on parcours le résultat
            echo "<h3>".$line['titre']."</h3>";
            echo "<p>".$line['contenu'];
            if($line['idAuteur'] == $_SESSION['id']) echo "<br><a href='/index.php?action=supprPost&idPost=".$line['id']."&idAuteur=".$line['idAuteur']."'>Supprimer</a>";
            echo "</p>";
        }       
    }
    ?>
</div>