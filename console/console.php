<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dot = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dot->load();
