<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de que composer autoload esté configurado

//Cargar las variables de entorno desde el archivo .env
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

//Obtener las variables de entorno
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

//Crear la conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
