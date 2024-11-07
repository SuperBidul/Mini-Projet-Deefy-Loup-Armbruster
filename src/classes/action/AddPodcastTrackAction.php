<?php

namespace iutnc\deefy\action;

// Import des classes nécessaires
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method === 'GET') {
            // Affiche le formulaire pour ajouter une track
            return $this->renderForm();
        } elseif ($this->http_method === 'POST') {
            // Traite le formulaire
            return $this->handlePost();
        }
        return "Méthode HTTP non supportée.";
    }

    private function renderForm(): string
    {
        return <<<HTML
        <h2>Ajouter une Piste Audio</h2>
        <form method="post" action="?action=add-track" enctype="multipart/form-data">
            <label for="track_title">Titre de la Piste :</label>
            <input type="text" id="track_title" name="track_title" required>
            <label for="track_duration">Durée de la Piste (en secondes) :</label>
            <input type="number" id="track_duration" name="track_duration" min="1" required>
            <label for="track_genre">Genre :</label>
            <input type="text" id="track_genre" name="track_genre" required>
            <label for="track_author">Auteur :</label>
            <input type="text" id="track_author" name="track_author" required>
            <label for="track_year">Année :</label>
            <input type="text" id="track_year" name="track_year" required>
            <label for="userfile">Fichier Audio (.mp3) :</label>
            <input type="file" id="userfile" name="userfile" accept=".mp3" required>
            <button type="submit">Ajouter</button>
        </form>
        HTML;
    }

    private function handlePost(): string
    {
        // Vérifie si les données de la piste sont présentes
        if (isset($_POST['track_title']) && isset($_POST['track_duration'])) {
            // Nettoie et filtre les données de la piste
            $trackTitle = filter_var(trim($_POST['track_title']), FILTER_SANITIZE_STRING);
            $trackDuration = filter_var(trim($_POST['track_duration']), FILTER_VALIDATE_INT);

            // Vérifie le fichier uploadé
            if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
                // Vérifie le type et l'extension du fichier
                $fileTmpPath = $_FILES['userfile']['tmp_name'];
                $fileName = $_FILES['userfile']['name'];
                $fileSize = $_FILES['userfile']['size'];
                $fileType = $_FILES['userfile']['type'];
                $fileNameComponents = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameComponents));

                if ($fileExtension === 'mp3' && $fileType === 'audio/mpeg') {
                    // Génére un nom aléatoire pour le fichier
                    $newFileName = uniqid('track_', true) . '.mp3';
                    $uploadFileDir = __DIR__ . '/../../audio/';
                    $destPath = $uploadFileDir . $newFileName;

                    // Déplace le fichier vers le répertoire souhaité
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        // Vérifier que la playlist existe dans la session
                        if (isset($_SESSION['playlists'])) {
                            $r = DeefyRepository::getInstance();
                            $id = $_SESSION['playlists']->id_playlist;
                            $playlist = $r->findPlaylistById($id);

                            // Instancie une nouvelle PodcastTrack
                            $track = new PodcastTrack($trackTitle, $newFileName, 'Genre', $trackDuration, 'Auteur', 2000);

                            $r->saveTrack($track);

                            $r->addTrackToPlaylist($track->id_track, $id);

                            $playlist->ajouterPiste($track);
                            $_SESSION['playlists']=$playlist;

                            // Instancie le renderer avec l'objet AudioList
                            $renderer = new AudioListRenderer($playlist); // Passer l'objet AudioList au constructeur

                            // Affiche la liste avec le renderer
                            return $renderer->render(1) . "<br><a href='?action=add-track'>Ajouter encore une piste</a>"; // 1 pour le mode compact
                        }
                        return "Aucune playlist trouvée dans la session.";
                    } else {
                        return "Erreur lors de l'upload du fichier.";
                    }
                } else {
                    return "Le fichier doit être au format .mp3.";
                }
            }
            return "Aucune piste audio uploadée.";
        }
        return "Données de la piste manquantes.";
    }

    private function renderPlaylist(string $playlistName): string
    {
        // Récupére la playlist de la session
        $tracks = $_SESSION['tracks'][$playlistName] ?? [];
        $audioListRenderer = new AudioListRenderer(); //TODO vérifier constructeur

        // Affiche la playlist
        $output = "<h2>Playlist : $playlistName</h2>";
        $output .= $audioListRenderer->render($tracks); // Méthode pour rendre les pistes

        // Lien pour ajouter encore une piste
        $output .= '<a href="?action=add-track">Ajouter encore une piste</a>';
        return $output;
    }
}
