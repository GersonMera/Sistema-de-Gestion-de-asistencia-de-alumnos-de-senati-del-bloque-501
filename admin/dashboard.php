<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

// Obtener estadísticas generales
$stats = $pdo->query("
    SELECT 
        COUNT(DISTINCT user_id) as total_students,
        COUNT(*) as total_attendance,
        COUNT(CASE WHEN date = CURRENT_DATE() THEN 1 END) as today_attendance
    FROM attendance_records
")->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel Administrativo - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/stylo-dasboard-admin.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <span>Panel Administrativo</span>
            </div>
            <div>
                <a href="generate_qr.php" class="nav-button">Generar QR</a>
                <a href="reports.php">Reportes</a>
                <a href="../logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="dashboard">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Estadísticas Generales</h3>
                <p>Total Estudiantes: <?php echo $stats['total_students']; ?></p>
                <p>Total Asistencias: <?php echo $stats['total_attendance']; ?></p>
                <p>Asistencias Hoy: <?php echo $stats['today_attendance']; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <h3>Acciones Rápidas</h3>
            <a href="generate_qr.php" class="button">Generar Nuevo QR</a>
            <a href="student_list.php" class="button">Lista de Estudiantes</a>
        </div>
    </div>
</body>
</html>