<?php declare(strict_types=1);

namespace iutnc\deefy\render;

require_once 'Renderer.php';

use \iutnc\deefy\audio\tracks\AudioTrack;

abstract class AudioTrackRenderer implements Renderer {
	protected AudioTrack $track;
	
	public function __construct(AudioTrack $track) {
        $this->track = $track;
    }
	
    public function render(int $selector): string {
        if ($selector === self::COMPACT) {
            return $this->renderCompact();
        } elseif ($selector === self::LONG) {
            return $this->renderLong();
        } else {
            return "Sélecteur Invalide";
        }
    }
	
    protected function renderCompact(): string {
        return "
        <div>
            <p><strong>{$this->track->titre}</strong> - {$this->track->auteur}</p>
            <audio controls>
                <source src='{$this->track->fileName}' type='audio/mpeg'>
            </audio>
        </div>";
    }
	
	abstract protected function renderLong(): string;
}
