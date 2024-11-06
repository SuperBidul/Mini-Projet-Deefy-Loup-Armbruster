<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action {

    public function execute(): string {
        return "Bienvenue !"; // Retourne le texte pour la page d'accueil
    }
}
