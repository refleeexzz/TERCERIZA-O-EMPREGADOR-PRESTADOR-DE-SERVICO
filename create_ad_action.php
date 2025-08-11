<?php
require_once 'inc/auth.php';
requireRole('company');
require_once 'inc/db.php';
$company_id = getUser()['id'];
$title = $_POST['title'] ?? '';
$price = $_POST['price'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
if ($title && $price && $date && $time) {
    $stmt = $pdo->prepare('INSERT INTO ads (company_id, title, price, date, time) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$company_id, $title, $price, $date, $time]);
    header('Location: dashboard_company.php');
    exit;
} else {
    echo "Preencha todos os campos!";
}
