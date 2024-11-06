<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class Playlist extends AudioList {

    protected int $id_playlist; // Clé primaire pour la playlist

    public function __construct(string $nom, array $pistes = []) {
        parent::__construct($nom, $pistes);
        $this->id_playlist = 0;
    }

    public function ajouterPiste(AudioTrack $piste): void {
        $this->pistes[] = $piste;
        $this->nombrePistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste(int $index): void {
        if (isset($this->pistes[$index])) {
            $this->dureeTotale -= $this->pistes[$index]->duree;
            unset($this->pistes[$index]);
            $this->pistes = array_values($this->pistes); // Réindexer le tableau
            $this->nombrePistes = count($this->pistes);
        } else {
            throw new InvalidPropertyNameException("Piste à l'index $index inexistante.");
        }
    }

    public function ajouterPistes(array $nouvellesPistes): void {
        foreach ($nouvellesPistes as $piste) {
            if (!in_array($piste, $this->pistes, true)) { // Vérification des doublons
                $this->ajouterPiste($piste);
            }
        }
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new InvalidPropertyNameException($name);
        }
    }

    // Setter pour id_playlist avec gestion d'erreur
    public function setIdPlaylist(int $id): void {
        if ($id < 0) {
            throw new InvalidPropertyValueException("id_playlist", $id);
        }
        $this->id_playlist = $id;
    }
}