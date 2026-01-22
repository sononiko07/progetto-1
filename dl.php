<?php
/**
 * dl.php - File di configurazione globale
 * Database: PDO
 * Mail: PHPMailer via Zoho SMTP (SSL 465)
 */

// 1. AVVIO SESSIONE (Indispensabile per gestire registrazione e login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. CREDENZIALI DATABASE
$db_host = 'localhost';
$db_name = 'progetto-1'; // Il nome del database creato con la query SQL
$db_user = 'phpadmin';                  // Il tuo utente DB (es. root)
$db_pass = 'Zanco@2007!';                      // La tua password DB

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// 3. CREDENZIALI SMTP ZOHO (nikozanco.it)
define('SMTP_HOST', 'smtppro.zoho.eu');
define('SMTP_USER', 'no-reply@nikozanco.it');
define('SMTP_PASS', 'Zanco@2007!'); // NOTA: Se hai il 2FA attivo, genera una "App Password" su Zoho
define('SMTP_PORT', 465);
define('MAIL_DOMINIO', '@galileiostiglia.edu.it'); // Modifica se il dominio studenti è diverso

// 4. INCLUSIONE PHPMAILER
// Assicurati che la cartella PHPMailer sia nella stessa directory di questo file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/**
 * Funzione universale per inviare email tramite Zoho
 */
function inviaEmail($destinatario, $codice) {
    $mail = new PHPMailer(true);

    try {
        // Configurazione Server
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL per porta 465
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // Fix per problemi di certificati SSL su server SSH/Linux locali
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Destinatari
        $mail->setFrom(SMTP_USER, 'Assemblea di Natale');
        $mail->addAddress($destinatario);

        // Contenuto Email
        $mail->isHTML(true);
        $mail->Subject = "Codice di verifica: $codice";
        
        // Template HTML Figo per la mail
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
            <div style='background: linear-gradient(45deg, #e73c7e, #23a6d5); color: white; padding: 20px; text-align: center;'>
                <h1 style='margin: 0;'>🎄 Assemblea 2026</h1>
            </div>
            <div style='padding: 30px; text-align: center; color: #333;'>
                <p style='font-size: 18px;'>Ciao! Grazie per esserti registrato.</p>
                <p>Usa il seguente codice di 6 cifre per attivare il tuo account:</p>
                <div style='background: #f8f9fa; border: 2px dashed #e73c7e; padding: 15px; font-size: 32px; font-weight: bold; letter-spacing: 10px; margin: 20px 0;'>
                    $codice
                </div>
                <p style='font-size: 14px; color: #777;'>Se non hai richiesto tu questo codice, ignora questa email.</p>
            </div>
            <div style='background: #f1f1f1; padding: 10px; text-align: center; font-size: 12px; color: #aaa;'>
                &copy; 2026 Associazione Culturale nikozanco.it
            </div>
        </div>";

        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Errore PHPMailer: " . $mail->ErrorInfo);
        return false;
    }
}
?>