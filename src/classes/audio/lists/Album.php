<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

require_once 'AudioList.php';

class Album extends AudioList {
    protected string $artiste;
    protected string $dateSortie;

    public function __construct(string $nom, array $pistes, string $artiste, string $dateSortie) {
        parent::__construct($nom, $pistes);
        $this->artiste = $artiste;
        $this->dateSortie = $dateSortie;
    }

    public function setArtiste(string $artiste): void {
        $this->artiste = $artiste;
    }

    public function setDateSortie(string $dateSortie): void {
        $this->dateSortie = $dateSortie;
    }
}
