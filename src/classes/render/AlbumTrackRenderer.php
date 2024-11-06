<?php declare(strict_types=1);

namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';
require_once 'src/classes/audio/tracks/AlbumTrack.php';

class AlbumTrackRenderer extends AudioTrackRenderer {
	
    // version longue
    protected function renderLong(): string {
		$albumTrack = $this->track;
        return "
        <div>
            <h1>{$this->track->titre}</h1>
            <p>Artiste : {$this->track->auteur}</p>
            <p>Album: {$this->track->nomAlbum}</p>
            <p>Track numéro : {$this->track->numPiste}</p>
            <p>Année: {$this->track->annee}</p>
            <p>Genre: {$this->track->genre}</p>
            <p>Durée: {$this->track->duree} seconds</p>
            <audio controls>
                <source src='{$this->track->fileName}' type='audio/mpeg'>
            </audio>
        </div>";
    }
}
