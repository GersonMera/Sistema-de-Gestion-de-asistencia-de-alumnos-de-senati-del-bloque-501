<?php
session_start();

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAuth() {
    if (!isAuthenticated()) {
        header("Location: /asis/login.php");
        exit();
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header("Location: /asis/dashboard.php");
        exit();
    }
}

// Redirigir si ya está autenticado
function redirectIfAuthenticated() {
    if (isAuthenticated()) {
        if (isAdmin()) {
            header("Location: /asis/admin/dashboard.php");
        } else {
            header("Location: /asis/dashboard.php");
        }
        exit();
    }
}
?>