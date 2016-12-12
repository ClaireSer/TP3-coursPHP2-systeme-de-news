<?php
function chargerClasse($classname) {
  require $classname.'.php';
}
spl_autoload_register('chargerClasse');

$db = new PDO('mysql:host=localhost;dbname=news', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new NewsManager($db);
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
        <?php if (isset($_GET['modifier'])) $getNews = $manager->getById($_GET['modifier']); ?>
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
        <p>Il y a actuellement <?php ?> news. En voici la liste : </p>

        <table style="margin:auto;">
            <tr>
                <th>Auteur</th>
                <th>Titre</th>
                <th>Date d'ajout</th>
                <th>Dernière modification</th>
                <th>Action</th>
            </tr>
            <?php
            if (isset($_POST['ajouter']) AND !empty($_POST['auteur']) AND !empty($_POST['titre']) AND !empty($_POST['contenu'])) {
                $news = new News(['auteur' => $_POST['auteur'], 'titre' => $_POST['titre'], 'contenu' => $_POST['contenu']]); 
                $newsAdded = $manager->add($news);
                echo 'bonjour';
            } else {
                $message = '<p>Veuillez remplir tous les champs.</p>';
                echo $message;
            }

            if (isset($_POST['modifier']) AND isset($_GET['modifier'])) {
                $getNews = $manager->getById($_GET['modifier']);
                $newsUpDated = $manager->upDate($getNews);
            }

            $getAllNews = $manager->getAll();
            foreach ($getAllNews as $uneNews) {
                echo '<tr>';
                echo '<td>' . $uneNews->auteur() . '</td>';
                echo '<td>' . $uneNews->titre() . '</td>'; 
                echo '<td>' . $uneNews->dateAjout() . '</td>'; 
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
