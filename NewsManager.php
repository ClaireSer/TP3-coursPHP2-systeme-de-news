<?php
class NewsManager {
// 	attributs
	private $_db;
// 	setDb  
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
// 	constructeur
	public function __construct($db) {
		$this->setDb($db);
	}
// 	ajoute news
	public function add(News $news) {
		$req = $this->_db->prepare('INSERT INTO news(auteur, titre, contenu, dateAjout) VALUES (:auteur, :titre, :contenu, NOW())');
		$req->bindValue(':auteur', $news->auteur());
		$req->bindValue(':titre', $news->titre());
		$req->bindValue(':contenu', $news->contenu());
		$req->execute();
		
		$news->hydrate([
			'id' => $this->_db->lastInsertId()
		]);
	}
// récupère UNE news par l'id
    public function getById($id) {
        $req = $this->_db->prepare('SELECT id, auteur, titre, contenu, DATE_FORMAT(dateAjout, "%d/%m/%Y à %Hh%imin%ss") AS dateAjout, DATE_FORMAT(dateModif, "%d/%m/%Y à %Hh%imin%ss") AS dateModif FROM news WHERE id = :id');
        $req->execute([':id' => $id]);
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new News($donnees);
    }
// récupère TOUTES les news
    public function getAll() {
        $news = [];
        $req = $this->_db->query('SELECT id, auteur, titre, contenu, DATE_FORMAT(dateAjout, "%d/%m/%Y à %Hh%i") AS dateAjout, DATE_FORMAT(dateModif, "%d/%m/%Y à %Hh%i") AS dateModif FROM news');
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $news[] = new News($donnees);
        }
        return $news;
    }
// modifie news
    public function upDate(News $news) {
        $req = $this->_db->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = :dateModif WHERE id = :id');
        $req->bindValue(':auteur', $news->auteur(), PDO::PARAM_INT);
        $req->bindValue(':titre', $news->titre(), PDO::PARAM_INT);
        $req->bindValue(':contenu', $news->contenu(), PDO::PARAM_INT);
        $req->bindValue(':dateModif', NOW(), PDO::PARAM_INT);
        $req->bindValue(':id', $news->id(), PDO::PARAM_INT);
        $req->execute();
    }
// supprime news
    public function delete(News $news) {
        $req = $this->_db->exec('DELETE FROM news WHERE id = ' . $news->id());
    }
}