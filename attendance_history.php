<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
requireAuth();

// Obtener filtros
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Obtener asistencias filtradas
$stmt = $pdo->prepare("
    SELECT 
        date,
        check_in,
        check_out,
        CASE 
            WHEN check_in IS NOT NULL AND check_out IS NOT NULL THEN 'Completo'
            WHEN check_in IS NOT NULL THEN 'Solo Entrada'
            ELSE 'Incompleto'
        END as status
    FROM attendance_records 
    WHERE user_id = ? 
    AND MONTH(date) = ? 
    AND YEAR(date) = ?
    ORDER BY date DESC
");
$stmt->execute([$_SESSION['user_id'], $month, $year]);
$attendance_records = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historial de Asistencia</title>
    <link rel="stylesheet" href="css/historialAlumno.css">
    <link rel="icon" href="img/Senati_logo.png" type="image/x-icon">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="dashboard.php">← Volver al Dashboard</a>
            </div>
            <div>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Historial de Asistencia</h2>
        
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

        <div class="table-container">
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
                    <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['check_in'] ?? '-'; ?></td>
                        <td><?php echo $record['check_out'] ?? '-'; ?></td>
                        <td>
                            <span class="status-<?php echo strtolower(str_replace(' ', '-', $record['status'])); ?>">
                                <?php echo $record['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>