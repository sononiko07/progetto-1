<?php
require '../dl.php';
if (!isset($_SESSION['user'])) { header("Location: ../register.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="glass-card text-center animate__animated animate__fadeInDown">
        <h1>🎄 Benvenuto!</h1>
        <p>Email: <?php echo $_SESSION['user']; ?></p>
        <hr>
        <p>Qui vedrai l'elenco degli eventi dell'assemblea di Natale.</p>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</body>
</html>
