<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\lists\Playlist;

class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            // Affiche le formulaire pour ajouter une playlist
            return $this->renderForm();
        } elseif ($this->http_method === 'POST') {
            // Traite le formulaire
            return $this->handlePost();
        }
        return "Méthode HTTP non supportée.";
    }

    private function renderForm(): string {
        // Formulaire pour la création de la playlist
        return <<<HTML
        <h2>Créer une Playlist</h2>
        <form method="post" action="?action=add-playlist">
            <label for="playlist_name">Nom de la Playlist :</label>
            <input type="text" id="playlist_name" name="playlist_name" required>
            <button type="submit">Créer</button>
        </form>
        HTML;
    }

    private function handlePost(): string {
        // Vérifie si le nom de la playlist est présent dans les données POST
        if (isset($_POST['playlist_name'])) {
            // Nettoie et filtrer le nom de la playlist
            $playlistName = filter_var(trim($_POST['playlist_name']), FILTER_SANITIZE_STRING);

            // Créée une nouvelle instance de Playlist avec le nom fourni
            $playlist = new Playlist($playlistName); // Fournir le nom de la playlist au constructeur

            // Sauvegarde la playlist en base de données
            $deefyRepository = DeefyRepository::getInstance();
            $savedPlaylist = $deefyRepository->saveEmptyPlaylist($playlist);

            $_SESSION['playlists'] = $savedPlaylist;

            // Rendu de la playlist
            return "<h2>Playlist '$playlistName' créée avec succès !</h2>";
        }
        return "Nom de la playlist manquant.";
    }
}

