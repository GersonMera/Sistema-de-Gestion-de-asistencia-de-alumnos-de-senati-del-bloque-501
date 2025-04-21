<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Estadísticas generales del mes
$stmt = $pdo->prepare("
    SELECT 
        COUNT(DISTINCT user_id) as total_students,
        COUNT(DISTINCT date) as total_days,
        COUNT(*) as total_records,
        COUNT(check_in) as total_check_ins,
        COUNT(check_out) as total_check_outs
    FROM attendance_records
    WHERE MONTH(date) = ? AND YEAR(date) = ?
");
$stmt->execute([$month, $year]);
$stats = $stmt->fetch();

// Reporte diario del mes
$stmt = $pdo->prepare("
    SELECT 
        date,
        COUNT(DISTINCT user_id) as total_students,
        COUNT(check_in) as check_ins,
        COUNT(check_out) as check_outs
    FROM attendance_records
    WHERE MONTH(date) = ? AND YEAR(date) = ?
    GROUP BY date
    ORDER BY date DESC
");
$stmt->execute([$month, $year]);
$daily_records = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reportes - Sistema de Asistencia</title>

    <link rel="stylesheet" href="../css/report.css">
    
    <link rel="icon" href="../img/Senati_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/reporte-de-alumnos.css">

</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="dashboard.php">← Volver al Panel</a>
            </div>
            <div>
                <button onclick="window.print()" class="button">Imprimir Reporte</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Reportes del Sistema</h2>

        <div class="filters">
            <form method="GET" action="">
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
                <p>Total Estudiantes Activos: <?php echo $stats['total_students']; ?></p>
                <p>Total Días con Registros: <?php echo $stats['total_days']; ?></p>
                <p>Total Entradas: <?php echo $stats['total_check_ins']; ?></p>
                <p>Total Salidas: <?php echo $stats['total_check_outs']; ?></p>
            </div>
        </div>

        <div class="table-container">
            <h3>Reporte Diario</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiantes</th>
                        <th>Entradas</th>
                        <th>Salidas</th>
                        <th>% Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daily_records as $record): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['total_students']; ?></td>
                        <td><?php echo $record['check_ins']; ?></td>
                        <td><?php echo $record['check_outs']; ?></td>
                        <td>
                            <?php 
                            $percentage = ($record['check_ins'] / $stats['total_students']) * 100;
                            echo number_format($percentage, 1) . '%';
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