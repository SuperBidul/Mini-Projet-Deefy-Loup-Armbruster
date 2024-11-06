<?php declare(strict_types=1);

namespace iutnc\deefy\exception;

class InvalidPropertyValueException extends \Exception {
    public function __construct(string $propertyName, $value) {
        parent::__construct("Mauvaise valeur : '{$value}' pour l'argument : {$propertyName}");
    }
}