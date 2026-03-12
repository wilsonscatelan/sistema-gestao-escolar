<?php
require_once __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function send_mail($to, $subject, $body, $options = []) {
$cfg = require __DIR__ . '/../config.php';
$smtp = $cfg['smtp'];


$mail = new PHPMailer(true);
try {
$mail->isSMTP();
$mail->Host = $smtp['host'];
$mail->SMTPAuth = true;
$mail->Username = $smtp['user'];
$mail->Password = $smtp['pass'];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = $smtp['port'];


$from = $options['from'] ?? $smtp['from'];
$from_name = $options['from_name'] ?? $smtp['from_name'];


$mail->setFrom($from, $from_name);
$mail->addAddress($to);


$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = $body;


$mail->send();
return ['success' => true];
} catch (Exception $e) {
return ['success' => false, 'error' => $mail->ErrorInfo];
}
}