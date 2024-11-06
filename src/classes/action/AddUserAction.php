<?php

namespace iutnc\deefy\action;

class AddUserAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            // Affiche le formulaire pour ajouter un utilisateur
            return $this->renderForm();
        } elseif ($this->http_method === 'POST') {
            // Traite le formulaire
            return $this->handlePost();
        }
        return "Méthode HTTP non supportée.";
    }

    private function renderForm(): string {
        // Formulaire pour l'inscription de l'utilisateur
        return <<<HTML
        <h2>Inscription</h2>
        <form method="post" action="?action=add-user">
            <label for="user_name">Nom :</label>
            <input type="text" id="user_name" name="user_name" required>
            <label for="user_email">Email :</label>
            <input type="email" id="user_email" name="user_email" required>
            <label for="user_age">Âge :</label>
            <input type="number" id="user_age" name="user_age" required min="1">
            <button type="submit">Connexion</button>
        </form>
        HTML;
    }

    private function handlePost(): string {
        // Vérifie si les données de l'utilisateur sont présentes
        if (isset($_POST['user_name'], $_POST['user_email'], $_POST['user_age'])) {
            // Nettoie et filtrer les données
            $userName = filter_var(trim($_POST['user_name']), FILTER_SANITIZE_STRING);
            $userEmail = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
            $userAge = filter_var(trim($_POST['user_age']), FILTER_VALIDATE_INT);

            // Vérifie si l'âge est valide
            if ($userAge === false || $userAge < 1) {
                return "L'âge doit être un nombre positif.";
            }

            // Affiche les valeurs dans un message
            return "Nom: $userName, Email: $userEmail, Age: $userAge ans";
        }
        return "Données de l'utilisateur manquantes.";
    }
}
