<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

$student_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Obtener información del estudiante
$stmt = $pdo->prepare("
    SELECT * FROM users 
    WHERE id = ? AND role = 'student'
");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: dashboard.php");
    exit();
}

// Obtener estadísticas del estudiante
$stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total_attendance,
        COUNT(check_in) as total_check_ins,
        COUNT(check_out) as total_check_outs
    FROM attendance_records 
    WHERE user_id = ?
")->execute([$student_id]);
$stats = $stmt->fetch();

// Obtener historial de asistencia con filtros
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$attendance = $pdo->prepare("
    SELECT * FROM attendance_records 
    WHERE user_id = ? 
    AND MONTH(date) = ? 
    AND YEAR(date) = ?
    ORDER BY date DESC
");
$attendance->execute([$student_id, $month, $year]);
$records = $attendance->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Estudiante - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/Senati_logo.png" type="image/x-icon">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="dashboard.php">← Volver al Panel</a>
            </div>
            <div>
                <a href="../logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="dashboard">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Información del Estudiante</h3>
                <p>ID Institucional: <?php echo htmlspecialchars($student['institutional_id']); ?></p>
                <p>Nombre: <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
                <p>Fecha de Registro: <?php echo $student['created_at']; ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Estadísticas de Asistencia</h3>
                <p>Total Asistencias: <?php echo $stats['total_attendance']; ?></p>
                <p>Total Entradas: <?php echo $stats['total_check_ins']; ?></p>
                <p>Total Salidas: <?php echo $stats['total_check_outs']; ?></p>
            </div>
        </div>

        <div class="table-container">
            <h3>Historial de Asistencia</h3>
            <div class="filters">
                <form method="GET" action="">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    <select name="month">
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $month ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <select name="year">
                        <?php for($i = date('Y'); $i >= date('Y')-2; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $year ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['check_in'] ?? '-'; ?></td>
                        <td><?php echo $record['check_out'] ?? '-'; ?></td>
                        <td>
                            <?php 
                            if ($record['check_in'] && $record['check_out']) {
                                echo '<span class="status-complete">Completo</span>';
                            } else if ($record['check_in']) {
                                echo '<span class="status-partial">Solo Entrada</span>';
                            } else {
                                echo '<span class="status-missing">Incompleto</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>