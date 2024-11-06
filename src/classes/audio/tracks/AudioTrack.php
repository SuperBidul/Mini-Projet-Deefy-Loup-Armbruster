<?php declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\InvalidPropertyNameException;

abstract class AudioTrack {
    protected string $titre;
    protected string $fileName;
    protected string $genre;
    protected int $duree;
    protected string $auteur;
    protected int $annee;
    protected int $id_track; // Clé primaire pour la piste audio

    public function __construct(string $titre, string $fileName, string $genre, int $duree, string $auteur, int $annee){
        $this->id_track = 0;
        $this->titre = $titre;
        $this->fileName = $fileName;
        $this->setGenre($genre); // Utilisation du setter pour genre
        $this->setDuree($duree); // Utilisation du setter pour vérifier la durée
        $this->auteur = $auteur;
        $this->annee = $annee;
    }

    // Méthode magique pour accéder aux propriétés
    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException($name);
    }

    // Setter pour la durée avec vérification
    public function setDuree(int $duree): void {
        if ($duree < 0) {
            throw new InvalidPropertyValueException('duree', $duree);
        }
        $this->duree = $duree;
    }

    public function setGenre(string $genre): void {
        if (empty($genre)) {
            throw new InvalidPropertyValueException("Le genre ne peut pas être vide.");
        }
        $this->genre = $genre;
    }

    // Méthode magique pour attribuer des valeurs aux propriétés
    public function __set(string $name, $value): void {
        if ($name === 'duree') {
            $this->setDuree($value); // Utilisation du setter pour durée
        } elseif ($name === 'genre') {
            $this->setGenre($value); // Appel du setter pour genre
        } else {
            throw new InvalidPropertyNameException($name); // Propriété inconnue
        }
    }
}
