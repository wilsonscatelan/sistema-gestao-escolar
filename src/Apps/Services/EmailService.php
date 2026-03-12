<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Configuração Gmail SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'wilsinhocatelan@gmail.com';   // <-- ALTERAR
        $this->mail->Password = 'maow blwl ehdz jkqf';          // <-- ALTERAR
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        $this->mail->setFrom('SEU_EMAIL@gmail.com', 'Sistema Escolar');
        $this->mail->isHTML(true);
    }

    public function enviarEmail($destinatario, $assunto, $mensagemHtml, $mensagemTexto = '')
    {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = $assunto;
            $this->mail->Body = $mensagemHtml;
            $this->mail->AltBody = $mensagemTexto;

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            return false;
        }
    }
}
