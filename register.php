<?php
require 'dl.php';
$errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $azione = $_POST['azione'];

    if ($azione == 'register') {
        // Controllo dominio email
        if (!str_ends_with($_POST['email'], MAIL_DOMINIO)) {
            $errore = "Per favore, usa l'email istituzionale (@" . MAIL_DOMINIO . ")";
        } else {
            try {
                // Inserimento diretto con Ver = 1 (rimossa la verifica codice)
                $stmt = $pdo->prepare("INSERT INTO UTENTE (Nome, Cognome, Classe, Email, Password, Ver) VALUES (?,?,?,?,?,1)");
                $stmt->execute([
                    $_POST['nome'], 
                    $_POST['cognome'], 
                    $_POST['classe'], 
                    $_POST['email'], 
                    password_hash($_POST['pass'], PASSWORD_DEFAULT)
                ]);
                
                // Login automatico e redirect
                $_SESSION['user'] = $_POST['email'];
                header("Location: area/index.php");
                exit();
            } catch (Exception $e) { 
                $errore = "Questa email è già registrata nel sistema!"; 
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione | Portale Studenti</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome per icone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #a855f7;
            --glass-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #0f172a, #1e293b, #312e81, #581c87);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px 0 0 10px;
        }

        .btn-custom {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .alert {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
        }

        .footer-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="glass-card animate__animated animate__fadeInUp">
        <div class="text-center mb-4">
            <i class="fas fa-user-plus fa-3x mb-3 text-primary"></i>
            <h2 class="title">Crea Account</h2>
            <p class="text-white-50 small">Unisciti alla nostra community scolastica</p>
        </div>

        <?php if($errore): ?>
            <div class='alert alert-danger p-3 mb-3 animate__animated animate__headShake'>
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errore; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="azione" value="register">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" name="cognome" class="form-control" placeholder="Cognome" required>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                    <input type="text" name="classe" class="form-control" placeholder="Classe (es. 5A INF)" required>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Email @istituto.it" required>
                </div>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="pass" class="form-control" placeholder="Password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-custom w-100 mb-3">
                REGISTRATI ORA <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="footer-text">
            Sviluppato con <i class="fas fa-heart text-danger"></i> per il Galilei
        </div>
    </div>

</body>
</html>