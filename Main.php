<?php declare(strict_types=1);

// ################################
//       AUTOLOADER (COMPOSER)
// ################################

require_once 'vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

session_start();

DeefyRepository::setConfig(__DIR__ . '/config.db.ini');

$dispatcher = new Dispatcher(); // Création d'une instance de Dispatcher
$dispatcher->run(); // Exécution du dispatcher
