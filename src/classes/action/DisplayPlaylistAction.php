<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        // Utilise DeefyRepository pour récupérer toutes les playlists depuis la base de données
        $repo = DeefyRepository::getInstance();
        $playlists = $repo->getAllPlaylists(); // retourne un tableau de playlists

        if (count($playlists) > 0) {
            // Utiliser un renderer pour afficher chaque playlist
            $output = '';
            foreach ($playlists as $playlist) {
                $renderer = new AudioListRenderer($playlist); // Passe chaque playlist individuellement
                $output .= $renderer->render(1); // Mode compact pour chaque playlist
            }
            return $output; // Retourne toutes les playlists rendues
        } else {
            return "Aucune playlist trouvée.";
        }
    }
}




