<?php
require 'dl.php';
$stato = "registrazione";
$errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $azione = $_POST['azione'];

    if ($azione == 'register') {
        if (!str_ends_with($_POST['email'], MAIL_DOMINIO)) {
            $errore = "Usa l'email istituzionale!";
        } else {
            $codice = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            try {
                $stmt = $pdo->prepare("INSERT INTO UTENTE (Nome, Cognome, Classe, Email, Password, Codice_Verifica) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$_POST['nome'], $_POST['cognome'], $_POST['classe'], $_POST['email'], password_hash($_POST['pass'], PASSWORD_DEFAULT), $codice]);
                
                if (inviaEmail($_POST['email'], $codice)) {
                    $_SESSION['temp_email'] = $_POST['email'];
                    $stato = "verifica";
                } else { $errore = "Errore invio email. Controlla SMTP."; }
            } catch (Exception $e) { $errore = "Email già registrata!"; }
        }
    }

    if ($azione == 'verify') {
        $stmt = $pdo->prepare("SELECT * FROM UTENTE WHERE Email = ? AND Codice_Verifica = ?");
        $stmt->execute([$_SESSION['temp_email'], $_POST['codice']]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE UTENTE SET Ver=1, Codice_Verifica=NULL WHERE Email=?")->execute([$_SESSION['temp_email']]);
            $_SESSION['user'] = $_SESSION['temp_email'];
            header("Location: area/index.php");
        } else { $errore = "Codice errato!"; $stato = "verifica"; }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="glass-card animate__animated animate__zoomIn">
        <?php if($errore) echo "<div class='alert alert-danger p-2 small animate__animated animate__shakeX'>$errore</div>"; ?>
        
        <?php if($stato == "registrazione"): ?>
            <form method="POST">
                <input type="hidden" name="azione" value="register">
                <h3 class="text-center mb-4">Registrazione</h3>
                <input type="text" name="nome" class="form-control mb-2" placeholder="Nome" required>
                <input type="text" name="cognome" class="form-control mb-2" placeholder="Cognome" required>
                <input type="text" name="classe" class="form-control mb-2" placeholder="Classe" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email @galileiostiglia.edu.it" required>
                <input type="password" name="pass" class="form-control mb-4" placeholder="Password" required>
                <button class="btn btn-custom w-100">REGISTRATI</button>
            </form>
        <?php else: ?>
            <form method="POST" class="text-center animate__animated animate__fadeIn">
                <input type="hidden" name="azione" value="verify">
                <h3>Verifica Email</h3>
                <p class="small">Codice inviato a: <?php echo $_SESSION['temp_email']; ?></p>
                <input type="text" name="codice" class="form-control mb-4 text-center fs-2" maxlength="6" placeholder="000000" required>
                <button class="btn btn-custom w-100">VERIFICA</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>