<?php
   require 'vendor/autoload.php';
   use PHPMailer\PHPMailer\PHPMailer;
   $mail = new PHPMailer;
   $mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'edgartestupao2024@gmail.com';
$mail->Password = 'Choco2024**'; // Usa una contraseña de aplicación si tienes la verificación en dos pasos
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->addAddress('edgarhuber2011@gmail.com', 'Recipient Name');
$mail->Body = 'Este es el contenido del mensaje en texto plano.';
$mail->AltBody = 'Este es el contenido alternativo en texto plano, si no se puede ver el HTML.';
   //$mail->addAttachment('attachment.txt');
   if (!$mail->send()) {
       echo 'Mailer Error: ' . $mail->ErrorInfo;
   } else {
       echo 'The email message was sent.';
   }
?>
