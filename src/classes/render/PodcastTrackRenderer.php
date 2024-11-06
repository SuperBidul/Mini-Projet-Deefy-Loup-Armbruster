<?php declare(strict_types=1);

namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';
require_once 'src/classes/audio/tracks/PodcastTrack.php';

class PodcastTrackRenderer extends AudioTrackRenderer {
	//version longue
    protected function renderLong(): string {
		$podcastTrack = $this->track;
        return "
        <div>
            <h1>{$this->track->titre}</h1>
            <p>Auteur : {$this->track->auteur}</p>
            <p>Genre: {$this->track->genre}</p>
            <p>Durée: {$this->track->duree} secondes</p>
            <audio controls>
				<source src='{$this->track->fileName}' type='audio/mpeg'>
            </audio>
        </div>";
    }
}