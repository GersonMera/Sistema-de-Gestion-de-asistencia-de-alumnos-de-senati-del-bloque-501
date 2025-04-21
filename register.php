<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $institutional_id = $_POST['institutional_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (institutional_id, first_name, last_name, password) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$institutional_id, $first_name, $last_name, $password]);
        header("Location: login.php");
        exit();
    } catch(PDOException $e) {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Attendance System</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="icon" href="img/Senati_logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
    <img src="img/Senati_logo.png" alt="Logo" />
        <h2>Registrate</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Institutional ID:</label>
                <input type="text" name="institutional_id" required>
            </div>
            
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <p>Ya tienes una cuenta? <a href="login.php">Inicia sesion aqui</a></p>
    </div>
</body>
</html>