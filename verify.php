<?php
require 'dl.php';
$messaggio = "";
$email = $_GET['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $codice = $_POST['codice'];
    $stmt = $pdo->prepare("SELECT Codice_Verifica FROM UTENTE WHERE Email = ? AND Ver = 0");
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && $u['Codice_Verifica'] === $codice) {
        $pdo->prepare("UPDATE UTENTE SET Ver = 1, Codice_Verifica = NULL WHERE Email = ?")->execute([$email]);
        $successo = true;
    } else {
        $messaggio = "Codice non valido!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica Codice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="style.css">
    <style>
        .otp-input {
            letter-spacing: 15px;
            font-size: 2rem;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="glass-card animate__animated animate__zoomIn">
                <?php if(isset($successo)): ?>
                    <div class="animate__animated animate__bounceIn">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h3>Ottimo!</h3>
                        <p>Account attivato con successo.</p>
                        <a href="login.php" class="btn btn-custom w-100">VAI AL LOGIN</a>
                    </div>
                <?php else: ?>
                    <h2 class="fw-bold">Verifica Email</h2>
                    <p class="small text-light">Abbiamo inviato un codice a <b><?php echo htmlspecialchars($email); ?></b></p>
                    
                    <?php if($messaggio): ?>
                        <div class="alert alert-warning py-1 small"><?php echo $messaggio; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <div class="mb-4 mt-4">
                            <input type="text" name="codice" maxlength="6" class="form-control otp-input" placeholder="000000" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">ATTIVA ACCOUNT</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
