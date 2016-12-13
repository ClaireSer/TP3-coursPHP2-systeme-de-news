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
    <a href="admin.php">Accéder à l'espace d'administration</a>
    <?php 
    if (!isset($_GET['id'])) {
      echo '<h2>Liste des 5 dernières news</h2>';
      $allNews = $manager->getAll();
      if (empty($allNews)) {
        echo 'Il n\'y a aucune news';
      } else {
        foreach ($allNews as $oneNews) {
          echo '<h3><a href="index.php?id=' . $oneNews->id() . '">' . $oneNews->titre() . '</a></h3>';
          echo '<p>' . substr($oneNews->contenu(), 0, 200) . '...</p>';
        }
      }

    } else {
        $newsById = $manager->getById($_GET['id']);

        echo '<p>Par <em>' . $newsById->auteur() . '</em>, le ' . $newsById->dateAjout() . '</p>';
        echo '<h3>' . $newsById->titre() . '</h3>';
        echo '<p>' . $newsById->contenu() . '</p>';
        if ($newsById->dateModif() != "00/00/0000 à 00h00min00s") {
            echo '<p style="text-align: right;"><em>Modifié le ' . $newsById->dateModif() . '</em></p>';
        }
    }
    ?>
  </body>
</html>

 