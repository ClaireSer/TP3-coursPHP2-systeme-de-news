<?php
session_start();

function chargerClasse($classname) {
  require $classname.'.php';
}
spl_autoload_register('chargerClasse');

$manager = new NewsManager();

if (isset($_GET['modifier'])) {
    $getNews = $manager->getById($_GET['modifier']);
    $_SESSION['modifier'] = $_GET['modifier'];
}

if (isset($_POST['ajouter']) AND !empty($_POST['auteur']) AND !empty($_POST['titre']) AND !empty($_POST['contenu'])) {
    $news = new News(['auteur' => $_POST['auteur'], 'titre' => $_POST['titre'], 'contenu' => $_POST['contenu']]); 
    $newsAdded = $manager->add($news);
} else {
    $message = '<p>Veuillez remplir tous les champs.</p>';
}

if (isset($_POST['modifier'])) {
    $getNews = $manager->getById($_SESSION['modifier']);
    $getNews->setAuteur($_POST['auteur']);
    $getNews->setTitre($_POST['titre']);
    $getNews->setContenu($_POST['contenu']);
    $newsUpDated = $manager->upDate($getNews);
}

if (isset($_GET['supprimer'])) {
    $getNewsSupprimer = $manager->getById($_GET['supprimer']);
    $deleteNews = $manager->delete($getNewsSupprimer);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Système de news</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <a href="index.php">Accéder à l'accueil du site</a>
    <div style="text-align:center;">
        <form method="POST" action="admin.php">
        
            <p>Auteur : <input type="text" name="auteur" <?php if (isset($_GET['modifier'])) echo 'value="' . $getNews->auteur() . '"'; ?> /></p>
            <p>Titre : <input type="text" name="titre" <?php if (isset($_GET['modifier'])) echo 'value="' . $getNews->titre() . '"'; ?> /></p>
            <p>Contenu : <br /> 
            <textarea name="contenu" cols="70" rows="10"><?php if (isset($_GET['modifier'])) echo $getNews->contenu(); ?></textarea></p>
            <?php if(isset($message)) echo $message; ?>
            <?php if (!isset($_GET['modifier'])) {
                echo '<p><input type="submit" name="ajouter" value="Ajouter" /></p>';
            } else {
                echo '<p><input type="submit" name="modifier" value="Modifier" /></p>';
            }
            ?>
            
        </form>    
        <p>Il y a actuellement <?php echo $manager->count(); ?> news. En voici la liste : </p>

        <table style="margin:auto;">
            <tr>
                <th>Auteur</th>
                <th>Titre</th>
                <th>Date d'ajout</th>
                <th>Dernière modification</th>
                <th>Action</th>
            </tr>
            
            <?php
            $getAllNews = $manager->getAll();
            foreach ($getAllNews as $uneNews) {
                echo '<tr>
                <td>' . $uneNews->auteur() . '</td>
                <td>' . $uneNews->titre() . '</td> 
                <td>' . $uneNews->dateAjout() . '</td>'; 
                if ($uneNews->dateModif() != "00/00/0000 à 00h00") {
                    echo '<td>' . $uneNews->dateModif() . '</td>'; 
                } else {
                    echo '<td>-</td>';
                }
                echo '<td><a href="admin.php?modifier=' . $uneNews->id() . '">Modifier</a> | <a href="admin.php?supprimer=' . $uneNews->id() . '">Supprimer</a></td>';                  
                echo '</tr>';
            }
            ?>
        </table>
    </div>
  </body>
</html>
