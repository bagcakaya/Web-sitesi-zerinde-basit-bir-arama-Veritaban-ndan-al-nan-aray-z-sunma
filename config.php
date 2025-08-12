<?php
// Veritabanı bağlantı ayarları
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Veritabanı bağlantısını oluştur
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // PDO hata modunu ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
