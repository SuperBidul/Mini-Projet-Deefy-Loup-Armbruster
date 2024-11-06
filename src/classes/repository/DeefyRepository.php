<?php

declare(strict_types=1);

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use \PDO;
use PDOException;

class DeefyRepository {
    private \PDO $pdo; // Instance de connexion PDO
    private static ?DeefyRepository $instance = null; // Instance unique (singleton)
    private static array $config = []; // Tableau de configuration de connexion

    private function __construct(array $conf) {
        try {
            $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la connexion à la base de données : " . $e->getMessage());
        }
    }

    // Méthode statique pour obtenir une instance unique de la classe
    public static function getInstance(): DeefyRepository {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    // Méthode statique pour charger et définir la configuration à partir d'un fichier .ini
    public static function setConfig(string $file): void {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Erreur lors de la lecture du fichier de configuration");
        }
        self::$config = [
            'dsn' => $conf['dsn'] ?? '',
            'user' => $conf['username'] ?? '',
            'pass' => $conf['password'] ?? ''
        ];
    }

    // Méthode pour retrouver une playlist par son ID
    public function findPlaylistById(int $id): ?Playlist {
        $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $nom = $result['nom'];
            $stmt = $this->pdo->prepare("SELECT * FROM track inner JOIN playlist2track on id_track = id WHERE id_pl = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $playlist = new Playlist($nom);
            // récupérer les infos de la requete
            while ($result = $stmt->fetch()){
                $titre = $result['titre'];
                $filename = $result['filename'];
                $genre = $result['genre'];
                $duree = (int)($result['duree']);
                $auteur = $result['artiste_album'];
                $annee = (int)($result['annee_album']);
                $nomAlbum = $result['titre_album'];
                $numPiste = $result['numero_album'];
                // créer un objet audio track
                $a = new AlbumTrack($titre, $filename, $genre, $duree, $auteur, $annee, $nomAlbum, $numPiste) ;
                $playlist->ajouterPiste($a);
            }
            $id = intval($id);
            $playlist->setIdPlaylist($id);
            return $playlist;
        }
        return null;
    }

    // 1. Récupérer la liste des playlists
    public function getAllPlaylists(): array {
        $stmt = $this->pdo->query("SELECT * FROM playlist");
        $playlists = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            // Instancier Playlist en passant le nom et un tableau vide de pistes
            $playlist = new Playlist($row['nom']); // Utilisation de 'nom' au lieu de 'name'
            $playlist->setIdPlaylist((int)$row['id']); // Définir l'ID de la playlist

            $playlists[] = $playlist;
        }
        return $playlists;
    }


    // 2. Sauvegarder une playlist vide de pistes
    public function saveEmptyPlaylist(Playlist $playlist): Playlist {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $nom = $playlist->getNom();
        $stmt->bindParam(':nom', $nom, \PDO::PARAM_STR);
        $stmt->execute();
        $id = $this->pdo->lastInsertId();
        $playlist->setIdPlaylist((int)$this->pdo->lastInsertId());
        return $playlist;
    }

    // 3. Sauvegarder une piste
    public function saveTrack(AudioTrack $track): AudioTrack {
        $stmt = $this->pdo->prepare(
            "INSERT INTO track (titre, filename, genre, duree, artiste_album, annee_album, titre_album, type, numero_album) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $album="album";
        $type="A";
        $num=1;
        $stmt->bindParam(1, $track->titre, \PDO::PARAM_STR);
        $stmt->bindParam(2, $track->fileName, \PDO::PARAM_STR);
        $stmt->bindParam(3, $track->genre, \PDO::PARAM_STR);
        $stmt->bindParam(4, $track->duree, \PDO::PARAM_INT);
        $stmt->bindParam(5, $track->auteur, \PDO::PARAM_STR);
        $stmt->bindParam(6, $track->annee, \PDO::PARAM_INT);
        $stmt->bindParam(7, $album, \PDO::PARAM_STR);
        $stmt->bindParam(8, $type, \PDO::PARAM_STR);
        $stmt->bindParam(9 , $num, \PDO::PARAM_INT);

        $stmt->execute();
        $track->setId((int)$this->pdo->lastInsertId()); // Utilisation du setter pour définir l'ID

        return $track;
    }

    // 4. Ajouter une piste existante à une playlist existante
    public function addTrackToPlaylist(int $trackId, int $playlistId): void {
        $stmt = $this->pdo->prepare("INSERT INTO playlist2track VALUES (:playlist_id, :track_id, :num)");
        $stmt->bindParam(':playlist_id', $playlistId, \PDO::PARAM_INT);
        $stmt->bindParam(':track_id', $trackId, \PDO::PARAM_INT);
        $num = $this->findPlaylistById($playlistId);
        $num = $num->pistes;
        $num = count($num);
        $stmt->bindParam(':num', $num);
        $stmt->execute();
    }
}