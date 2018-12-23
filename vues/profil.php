<?php
    if(!isset($_SESSION["id"])) {
        // On n est pas connecté, il faut retourner à la pgae de login
        header("Location:index.php?action=login");
    }

    // On veut affchier notre mur ou celui d'un de nos amis et pas faire n'importe quoi
    $ok = false;

    if($_GET["id"]==$_SESSION["id"]){
        $id = $_SESSION["id"];
        // On a le droit d afficher notre mur
        $ok = true;

      ?>

<div class="bloc">
  <div id="avatar">
    <h2 class="nom-profil">
      <?php echo idToUsername($id, $pdo); ?>
    </h2>
    <?php if(file_exists("img/avatar/$id.jpg")){ ?>
      <img class="avatar" src="img/avatar/<?php echo $id ?>.jpg">
    <?php } ?>
    
    <?php
    if($_GET["id"]==$_SESSION["id"]){
      ?>
      <form action="index.php?action=upload" method="post" enctype="multipart/form-data">
    Envoyez nous une nouvelle image de profil
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
    </form>
    <?php } ?>
    
  </div>
</div>

<div class="bloc">
  <h2>Demandes extérieures</h2>
  <p>
    <?php echo checkRequest2($id, $pdo); ?>
  </p>
</div>
<div class="bloc">
  <h2>Vos demandes</h2>
  <p>
    <?php echo checkRequest($id, $pdo); ?>
  </p>
</div>
<div class="bloc">
  <h2>Rechercher quelqu'un</h2>
  <form method="POST" action="index.php?action=profil&id=<?php echo $id; ?>">
    <input type="search" placeholder="Rechercher un Avengers" name="recherche">
    <input type="submit" value="OK" name="search_someone">              
  </form>
  <?php
    if(isset($_POST['search_someone']) && !empty($_POST['search_someone'])){
      echo "<p style='margin-top: 20px;'>";
      foreach(searchUser($_POST['recherche'], $pdo) as $array){
        echo idToUsername($array, $pdo)." ";
        if(checkStatus($_SESSION['id'], $array, $pdo) == 'ami'){
          echo "Ami <a href='index.php?action=update&id=".$_SESSION['id']."&id2=".$array."&etat=banni'>Bloquer</a>";
        }
        else if(checkStatus($_SESSION['id'], $array, $pdo) == 'attente'){
          echo "En attente <a href=''>Annuler</a>";
        }
        else if(checkStatus($_SESSION['id'], $array, $pdo) == 'banni') {
          echo "Bloqué <a href='index.php?action=update&id=".$_SESSION['id']."&id2=".$array."&etat=attente'>Demander en ami</a>";
        }
        else {
          echo "Non ami <a href='index.php?action=update&id=".$_SESSION['id']."&id2=".$array."&etat=attente'>Demander en ami</a> <a href='index.php?action=update&id=".$_SESSION['id']."&id2=".$array."&etat=banni'>Bloquer</a>";
        }
        echo "<br>";
      }
      echo "</p>";
    }
  ?>
</div>
<div class="bloc">
  <h2>Ecrire sur le mur</h2>
  <form action="index.php?action=newpost" method="POST">
    <label for="titre">Titre :</label>
    <input type="text" class="text--titre" name="titre">
    <label for="contenu">Contenu :</label>
    <textarea class="text--contenu" name="contenu"></textarea>
    <input type="hidden" value="<?php echo $_SESSION['id']; ?>" name="idAuteur">
    <input type="hidden" value="<?php echo $id; ?>" name="idAmi">
    <input type="submit" value="Envoyer">
  </form>
</div>
<div class="bloc">
  <?php
            // On veut que le formulaire est été soumis et que l'année est un nombre
            if($ok) { 
                $sql = "SELECT * FROM ecrit WHERE idAmi=? ORDER BY id DESC LIMIT 5"; // 
                $query = $pdo->prepare($sql); // Etpae 1 : On prépare la requête
                                            //  et celle-ci a un paramètre optionnel
                $query->execute(array($id)); // Etape 2 :On l'exécute. 
                                                        // On remplace le ? par l'année donnée 
                
                echo "<h2>Derniers posts</h2>";

                while($line = $query->fetch()) { // Etape 3 : on parcours le résultat
                    echo "<h3>".htmlentities($line['titre'])."</h3>";
                    echo "<p>".htmlentities($line['contenu']);
                    if(checklike($line['id'], $_SESSION['id'], $pdo)){
                      echo "<a href='index.php?action=like&idE=".$line['id']."&idU=".$_SESSION['id']."'>Aimer</a>";
                    }
                    else{
                      echo "<a href='index.php?action=like&idE=".$line['id']."&idU=".$_SESSION['id']."'>Ne plus aimer</a>";
                    }
                    if($line['idAuteur'] == $_SESSION['id'] || $_SESSION['id'] == $_GET['id']) echo "<br><a href='index.php?action=rmPost&idPost=".$line['id']."'>Supprimer</a>";
                    echo "</p>";
                    echo "<h4>Le ".$line['dateEcrit']." par <a href='index.php?action=profil&id=".$line['idAuteur']."'>".idToUsername($line['idAuteur'] ,$pdo)."</a></h4>";
                }       
            }
            ?>
</div>
<?php

    } else {
        $id = $_GET["id"];
        // Verifions si on est amis avec cette personne
        
        if (checkStatus($_GET['id'], $_SESSION['id'], $pdo) == 'ami' || checkStatus($_SESSION['id'], $_GET['id'], $pdo) == 'ami'){
            $ok = true;
            ?>
<div class="bloc">
  <div id="avatar">
    <h2 class="nom-profil">
      <?php echo idToUsername($id, $pdo); ?>
    </h2>

    <?php if(file_exists("img/avatar/$id.jpg")){ ?>
      <img class="avatar" src="img/avatar/<?php echo $id ?>.jpg">
    <?php } ?>
    
  </div>
</div>
<div class="bloc">
  <h2>Ecrire sur le mur</h2>
  <form action="index.php?action=newpost" method="POST">
    <label for="titre">Titre :</label>
    <input type="text" class="text--titre" name="titre">
    <label for="contenu">Contenu :</label>
    <textarea class="text--contenu" name="contenu"></textarea>
    <input type="hidden" value="<?php echo $_SESSION['id']; ?>" name="idAuteur">
    <input type="hidden" value="<?php echo $id; ?>" name="idAmi">
    <input type="submit" value="Envoyer">
  </form>
</div>
<div class="bloc">
  <?php
            // On veut que le formulaire est été soumis et que l'année est un nombre
            if($ok) { 
                $sql = "SELECT * FROM ecrit WHERE idAmi=? ORDER BY id DESC LIMIT 5"; // 
                $query = $pdo->prepare($sql); // Etpae 1 : On prépare la requête
                                            //  et celle-ci a un paramètre optionnel
                $query->execute(array($id)); // Etape 2 :On l'exécute. 
                                                        // On remplace le ? par l'année donnée 
                
                echo "<h2>Derniers posts</h2>";

                while($line = $query->fetch()) { // Etape 3 : on parcours le résultat
                    echo "<h3>".htmlentities($line['titre'])."</h3>";
                    echo "<p>".htmlentities($line['contenu']);
                    if($line['idAuteur'] == $_SESSION['id'] || $_SESSION['id'] == $_GET['id']) echo "<br><a href='index.php?action=rmPost&idPost=".$line['id']."'>Supprimer</a>";
                    echo "</p>";
                    echo "<h4>Le ".$line['dateEcrit']." par <a href='index.php?action=profil&id=".$line['idAuteur']."'>".idToUsername($line['idAuteur'] ,$pdo)."</a></h4>";
                }       
            }
            ?>
</div>
<?php
        }
        if($ok == false){
          message("Vous n'êtes pas ami avec ".idToUsername($id, $pdo).", vous ne pouvez pas voir son mur"); 
        }

        // les deux ids à tester sont : $_GET["id"] et $_SESSION["id"]
        // A completer. Il faut récupérer une ligne, si il y en a pas ca veut dire que lon est pas ami avec cette personne
    }
    if($ok==false) {
          
      header("Location:index.php?action=profil&id=".$_SESSION['id']);
      } else {
    // A completer
    // Requête de sélection des éléments dun mur
        // SELECT * FROM ecrit WHERE idAmi=? order by dateEcrit DESC
        // le paramètre  est le $id
    }
?>