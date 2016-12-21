<?php
class News { 
//attributs    
    private $_id,
            $_titre,
            $_auteur,
            $_contenu,
            $_dateAjout,
            $_dateModif;
// constructeur
    public function __construct(array $donnees) {
        $this->hydrate($donnees);
    }
// fonction hydrate
    public function hydrate(array $donnees) {
        foreach($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
// getters
    public function id() {
        return $this->_id;
    }
    public function titre() {
        return $this->_titre;
    }
    public function auteur() {
        return $this->_auteur;
    }
    public function contenu() {
        return $this->_contenu;
    }
    public function dateAjout() {
        return $this->_dateAjout;
    }
    public function dateModif() {
        return $this->_dateModif;
    }
// setters
    public function setId($id) {
        $id = (int) $id;
        if ($id > 0) $this->_id = $id;
    }
    public function setTitre($titre) {
        if (is_string($titre)) $this->_titre = $titre;
    }
    public function setAuteur($auteur) {
        if (is_string($auteur)) $this->_auteur = $auteur;
    }
    public function setContenu($contenu) {
        if (is_string($contenu)) $this->_contenu = $contenu;
    }
    public function setDateAjout($dateAjout) {
        $this->_dateAjout = $dateAjout;
    }
    public function setDateModif($dateModif) {
        $this->_dateModif = $dateModif;
    }
}