<?php
// Carga el autoload de Composer (está en vendor al mismo nivel)
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Crea la instancia de Dotenv indicando la raíz del proyecto (donde está .env)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
