<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
requireAuth();

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Obtener asistencias del mes actual
$stmt = $pdo->prepare("
    SELECT * FROM attendance_records 
    WHERE user_id = ? 
    AND MONTH(date) = MONTH(CURRENT_DATE())
    ORDER BY date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$recent_attendance = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Sistema de Asistencia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
            <div>
                <a href="attendance_history.php">Historial</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="dashboard">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Información Personal</h3>
                <p>ID: <?php echo htmlspecialchars($user['institutional_id']); ?></p>
                <p>Nombre: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
            </div>
            
            <div class="dashboard-card">
                <h3>Asistencia de Hoy</h3>
                <?php
                $today_attendance = $pdo->prepare("
                    SELECT * FROM attendance_records 
                    WHERE user_id = ? AND date = CURRENT_DATE()
                ");
                $today_attendance->execute([$_SESSION['user_id']]);
                $today = $today_attendance->fetch();
                
                if ($today) {
                    echo "<p>Entrada: " . ($today['check_in'] ? $today['check_in'] : 'No registrada') . "</p>";
                    echo "<p>Salida: " . ($today['check_out'] ? $today['check_out'] : 'No registrada') . "</p>";
                } else {
                    echo "<p>No hay registros para hoy</p>";
                }
                ?>
            </div>
        </div>

        <div class="qr-container">
            <h3>Escanear Código QR</h3>
            <div id="qr-reader"></div>
            <button onclick="iniciarEscaneo()">Escanear QR</button>
        </div>

        <div class="table-container">
            <h3>Asistencias Recientes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_attendance as $record): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['check_in'] ?? '-'; ?></td>
                        <td><?php echo $record['check_out'] ?? '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="js/qr-scanner.js"></script>
</body>
</html>