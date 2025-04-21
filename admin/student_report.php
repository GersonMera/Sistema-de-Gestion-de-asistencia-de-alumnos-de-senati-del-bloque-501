<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

$student_id = isset($_GET['id']) ? $_GET['id'] : 0;
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Obtener información del estudiante
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'student'");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: student_list.php");
    exit();
}

// Obtener asistencias del mes
$stmt = $pdo->prepare("
    SELECT 
        date,
        check_in,
        check_out,
        TIMEDIFF(check_out, check_in) as total_time
    FROM attendance_records 
    WHERE user_id = ? 
    AND MONTH(date) = ? 
    AND YEAR(date) = ?
    ORDER BY date DESC
");
$stmt->execute([$student_id, $month, $year]);
$attendance = $stmt->fetchAll();

// Calcular estadísticas
$total_days = count($attendance);
$complete_days = 0;
$total_hours = 0;

foreach ($attendance as $record) {
    if ($record['check_in'] && $record['check_out']) {
        $complete_days++;
        $time_parts = explode(':', $record['total_time']);
        $total_hours += $time_parts[0] + ($time_parts[1]/60);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Estudiante - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/Senati_logo.png" type="image/x-icon">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="student_list.php">← Volver a la Lista</a>
            </div>
            <div>
                <button onclick="window.print()" class="button">Imprimir Reporte</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Reporte de Asistencia</h2>
        
        <div class="dashboard-card">
            <h3>Información del Estudiante</h3>
            <p>ID: <?php echo htmlspecialchars($student['institutional_id']); ?></p>
            <p>Nombre: <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
        </div>

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

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Resumen del Mes</h3>
                <p>Total Días: <?php echo $total_days; ?></p>
                <p>Días Completos: <?php echo $complete_days; ?></p>
                <p>Horas Totales: <?php echo number_format($total_hours, 2); ?></p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Tiempo Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['check_in'] ?? '-'; ?></td>
                        <td><?php echo $record['check_out'] ?? '-'; ?></td>
                        <td><?php echo $record['total_time'] ?? '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>