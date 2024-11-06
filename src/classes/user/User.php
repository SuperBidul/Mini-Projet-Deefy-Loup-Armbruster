<?php

namespace iutnc\deefy\user;

class User {
    protected int $id_user; // Clé primaire de la table "user"
    protected string $name;
    protected string $email;
    protected int $age;
    protected string $mdp;

    public function __construct(string $name, string $email, int $age, string $mdp) {
        $this->id_user = 0;
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
        $this->mdp = $mdp;
    }

    // Getters
    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException($name);
    }
}
