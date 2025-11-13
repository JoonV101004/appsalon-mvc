<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; // <-- ¡MUY IMPORTANTE AÑADIR ESTA LÍNEA!

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

         // create a new object
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
         $mail->SMTPAuth = true;
         $mail->Port = $_ENV['EMAIL_PORT'];
         $mail->Username = $_ENV['EMAIL_USER'];
         $mail->Password = $_ENV['EMAIL_PASS'];
   
         $mail->setFrom('vjoondominic@gmail.com', 'AppSalon');
         
         // === CORRECCIÓN AQUÍ ===
         $mail->addAddress($this->email, $this->nombre); 

         $mail->Subject = 'Confirma tu Cuenta';

         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';

         $contenido = '<html>';
         // === CORRECCIÓN AQUÍ (usando $this->nombre) ===
         $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Creado tu cuenta en App Salón, solo debes confirmarla presionando el siguiente enlace</p>";
         $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL']  . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";        
         $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         //Enviar el mail CON TRY...CATCH
         try {
            $mail->send();
         } catch (Exception $e) {
            // Esto nos dirá el error exacto de Domcloud
            echo "Error: El mensaje no se pudo enviar. Mailer Error: {$mail->ErrorInfo}";
            exit; // Detenemos todo para poder ver el error
         }
    }

    public function enviarInstrucciones() {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
   
        $mail->setFrom('vjoondominic@gmail.com', 'AppSalon');
        
        // === CORRECCIÓN AQUÍ ===
        $mail->addAddress($this->email, $this->nombre);
        
        $mail->Subject = 'Reestablece tu password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL']  . "/recuperar?token=" . $this->token . "'>Reestablecer Password</a>";        
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail CON TRY...CATCH
        try {
            $mail->send();
        } catch (Exception $e) {
            // Esto nos dirá el error exacto de Domcloud
            echo "Error: El mensaje no se pudo enviar. Mailer Error: {$mail->ErrorInfo}";
            exit; // Detenemos todo para poder ver el error
        }
    }
}