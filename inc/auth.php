<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function isLogged() {
    return isset($_SESSION['user']);
}
function getUser() {
    return $_SESSION['user'] ?? null;
}
function requireRole($role) {
    if (!isLogged() || getUser()['role'] !== $role) {
        header('Location: index.php');
        exit;
    }
}
function logout() {
    session_start();
    session_destroy();
    header('Location: index.php');
    exit;
}
