<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

// Process delete request
if(isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['delete']]);
    header("Location: manage_users.php");
    exit();
}

// Get all users
$stmt = $pdo->query("
    SELECT id, institutional_id, first_name, last_name, role, created_at 
    FROM users 
    ORDER BY role DESC, last_name ASC
");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Usuarios - Sistema de Asistencia</title>
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

    <div class="container">
        <h2>Gestión de Usuarios</h2>
        
        <div class="filters">
            <input type="text" id="searchUser" placeholder="Buscar usuario..." onkeyup="filterTable()">
        </div>

        <div class="table-container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID Institucional</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['institutional_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="button">Editar</a>
                            <?php if($user['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                                <button type="submit" name="delete" value="<?php echo $user['id']; ?>" class="button delete">Eliminar</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function filterTable() {
        var input = document.getElementById("searchUser");
        var filter = input.value.toLowerCase();
        var table = document.getElementById("usersTable");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td");
            var visible = false;
            
            for (var j = 0; j < 2; j++) {
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