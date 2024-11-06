<?php declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;

class PodcastTrack extends AudioTrack {
    public function __construct(string $titre, string $fileName, string $genre, int $duree, string $auteur, int $annee) {
        parent::__construct($titre, $fileName, $genre, $duree, $auteur, $annee);
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new InvalidPropertyNameException("La propriété $name n'existe pas.");
        }
    }

    // Ajouter un setter pour l'ID
    public function setId(int $id): void {
        $this->id_track = $id;
    }

    // getter pour le nom dans deefyrepository, le getter magique ne fonctionnait pas
    public function getTitre(): string {
        return $this->titre;
    }
}
