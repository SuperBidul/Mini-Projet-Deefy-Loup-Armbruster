<?php declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack {
    private string $nomAlbum;
    private int $numPiste;

    public function __construct(string $titre, string $fileName, string $genre, int $duree, string $auteur, int $annee, string $nomAlbum, int $numPiste) {
        parent::__construct($titre, $fileName, $genre, $duree, $auteur, $annee);
        $this->nomAlbum = $nomAlbum;
        $this->numPiste = $numPiste;
    }

    //méthode magique __get()
    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new \Exception("Invalid property: " . $name);
        }
    }
}
