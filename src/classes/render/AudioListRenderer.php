<?php declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer {
    private AudioList $audioList;

    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }

    // La méthode render prend maintenant un paramètre $selector
    public function render(int $selector): string {
        // Début de l'affichage HTML
        $output = "<h2>{$this->audioList->nom}</h2>";
        $output .= "<ul>";

        // Affichage de chaque piste en mode compact
        foreach ($this->audioList->pistes as $piste) {
            $output .= "<li>Titre: " . htmlspecialchars($piste->titre) . ", Durée: " . $piste->duree . " secondes</li>";
        }

        $output .= "</ul>";
        $output .= "<p>Nombre de pistes: {$this->audioList->nombrePistes}</p>";
        $output .= "<p>Durée totale: {$this->audioList->dureeTotale} secondes</p>";

        // Retourner le HTML généré
        return $output;
    }
}
