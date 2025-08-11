<?php
session_start();
require_once 'inc/db.php';
$role = $_POST['role'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];
$key = 'login_' . md5($ip . '_' . strtolower($email));
if (!isset($_SESSION['logr'])) $_SESSION['login_blocked'] = [];
// Bloqueio por 10 minutos após 5 tentativas
if (isset($_SESSION['login_blocked'][$key]) && $_SESSION['login_blocked'][$key] > time()) {
    $wait = $_SESSION['login_blocked'][$key] - time();
    die('Muitas tentativas. Tente novamente em ' . $wait . ' segundos.');
}
if ($role === 'company') {
    $stmt = $pdo->prepare('SELECT * FROM companies WHERE email = ?');
} elseif ($role === 'worker') {
    $stmt = $pdo->prepare('SELECT * FROM workers WHERE email = ?');
} else {
    die('Tipo de usuário inválido.');
}
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'role' => $role,
        'name' => $user['name'],
        'email' => $user['email'],
    ];
    unset($_SESSION['login_attempts'][$key]);
    unset($_SESSION['login_blocked'][$key]);
    header('Location: index.php');
    exit;
} else {
    if (!isset($_SESSION['login_attempts'][$key])) $_SESSION['login_attempts'][$key] = 0;
    $_SESSION['login_attempts'][$key]++;
    if ($_SESSION['login_attempts'][$key] >= 5) {
        $_SESSION['login_blocked'][$key] = time() + 600; // 10 minutos
        die('Muitas tentativas. Tente novamente em 10 minutos.');
    }
    echo 'Email ou senha inválidos.';
}
