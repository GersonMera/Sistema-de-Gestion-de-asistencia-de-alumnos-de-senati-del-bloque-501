<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

// Obtener lista de estudiantes con sus estadísticas
$students = $pdo->query("
    SELECT 
        u.id,
        u.institutional_id,
        u.first_name,
        u.last_name,
        u.created_at,
        COUNT(DISTINCT a.date) as total_days,
        COUNT(a.check_in) as total_check_ins,
        COUNT(a.check_out) as total_check_outs
    FROM users u
    LEFT JOIN attendance_records a ON u.id = a.user_id
    WHERE u.role = 'student'
    GROUP BY u.id
    ORDER BY u.last_name, u.first_name
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Estudiantes - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/lista-estudiantes.css">
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

    <div class="container">
        <h2>Lista de Estudiantes</h2>
        
        <div class="filters">
            <input type="text" id="searchStudent" placeholder="Buscar estudiante..." onkeyup="filterTable()">
        </div>

        <div class="table-container">
            <table id="studentsTable">
                <thead>
                    <tr>
                        <th>ID Institucional</th>
                        <th>Nombre</th>
                        <th>Fecha Registro</th>
                        <th>Días Asistidos</th>
                        <th>Total Entradas</th>
                        <th>Total Salidas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['institutional_id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($student['created_at'])); ?></td>
                        <td><?php echo $student['total_days']; ?></td>
                        <td><?php echo $student['total_check_ins']; ?></td>
                        <td><?php echo $student['total_check_outs']; ?></td>
                        <td>
                            <a href="student_report.php?id=<?php echo $student['id']; ?>" class="button">Ver Reporte</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function filterTable() {
        var input = document.getElementById("searchStudent");
        var filter = input.value.toLowerCase();
        var table = document.getElementById("studentsTable");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td");
            var visible = false;
            
            for (var j = 0; j < 2; j++) { // Solo busca en ID y nombre
                var cell = td[j];
                if (cell) {
                    var text = cell.textContent || cell.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
            }
            tr[i].style.display = visible ? "" : "none";
        }
    }
    </script>
</body>
</html>