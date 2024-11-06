<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddTrackToPlaylistAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;

class Dispatcher {
    private string $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default'; // Défaut à 'default'
    }

    public function run(): void {
        switch ($this->action) {
            case 'default':
                $action = new DefaultAction();
                break;
            case 'playlist':
                $action = new DisplayPlaylistAction();
                break;
            case 'add-playlist':
                $action = new AddPlaylistAction();
                break;
            case 'add-track':
                $action = new AddPodcastTrackAction();
                break;
            case 'add-user':
                $action = new AddUserAction();
                break;
            case 'add-existTrackToExistPlaylist':
                $action = new AddTrackToPlaylistAction();
                break;
            default:
                $action = new DefaultAction(); // Pour gérer les actions non reconnues
                break;
        }

        // Exécution de l'action et affichage de la page
        $htmlContent = $action->execute();
        $this->renderPage($htmlContent);
    }

    private function renderPage(string $html): void {
        // HTML de la page complète
        $fullPage = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>DeefyApp</title>
            <link rel="stylesheet" href="style.css"> <!-- Lien vers votre feuille de style -->
        </head>
        <body>
            <header>
                <h1>Bienvenue sur DeefyApp</h1>
                <nav>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=add-user">Inscription</a></li>
                        <li><a href="?action=add-playlist">Créer une Playlist</a></li>
                        <li><a href="?action=add-track">Ajouter une Piste</a></li>
                        <li><a href="?action=playlist">Afficher la Playlist</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                $html
            </main>
            <footer>
                <p>&copy; 2024 DeefyApp. Tous droits réservés.</p>
            </footer>
        </body>
        </html>
        HTML;

        echo $fullPage; // Affichage de la page complète
    }
}
