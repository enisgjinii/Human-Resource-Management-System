<?php
$config = require 'config.php';

try {
    $pdo = new PDO("mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8", $config['db']['user'], $config['db']['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit;
}
