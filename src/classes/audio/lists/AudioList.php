<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList {
    protected string $nom;
    protected int $nombrePistes;
    protected int $dureeTotale;
    protected array $pistes;

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nombrePistes = count($pistes);
        $this->dureeTotale = array_sum(array_map(fn($piste) => $piste->duree, $pistes));
    }

    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException($name);
    }

    // getter pour le nom dans deefyrepository, le getter magique ne fonctionnait pas
    public function getNom(): string {
        return $this->nom;
    }
}
