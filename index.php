<?php
function chargerClasse($classname) {
  require $classname.'.php';
}
spl_autoload_register('chargerClasse');

$manager = new NewsManager();
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
    if (isset($_GET['id'])) {
      $newsById = $manager->getById($_GET['id']);
      echo '<p>Par <em>' . $newsById->auteur() . '</em>, le ' . $newsById->dateAjout() . '</p>
      <h3>' . $newsById->titre() . '</h3>
      <p>' . $newsById->contenu() . '</p>';
      if ($newsById->dateModif() != "00/00/0000 à 00h00min00s") {
          echo '<p style="text-align: right;"><em>Modifié le ' . $newsById->dateModif() . '</em></p>';
      }

    } else {
      echo '<h2>Liste des 5 dernières news</h2>';
      $allNews = $manager->getAll();
      if (empty($allNews)) {
        echo 'Il n\'y a aucune news';
      } else {
        foreach ($allNews as $oneNews) {
          echo '<h3><a href="index.php?id=' . $oneNews->id() . '">' . $oneNews->titre() . '</a></h3>';
          if (strlen($oneNews->contenu()) > 200) {
            echo '<p>' . substr($oneNews->contenu(), 0, 200) . '...</p>';
          } else {
            echo '<p>' . $oneNews->contenu() . '</p>';
          }
        }
      }
    }
    ?>
  </body>
</html>