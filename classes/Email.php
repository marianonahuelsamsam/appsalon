<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '34cb2e5516747f';
        $mail->Password = '2e5a1af7462871';

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Confirma tu Cuenta";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p> <strong> Hola " .  $this->nombre . "</strong>. Has creado una cuenta en App Salon, presiona el
                        siguiente enlace para confirmarla. </p>";
        $contenido .= "<p> Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta.<a/> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar mail

        $mail->send();
    }

    public function enviarInstruccionesDeRecuperacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '34cb2e5516747f';
        $mail->Password = '2e5a1af7462871';

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $mail->Subject = "Reestblace tu contraseña";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p> <strong> Hola " .  $this->nombre . "</strong>. Has solicitado un cambio de contraseña, presiona el
                        siguiente enlace para reestablecerla. </p>";
        $contenido .= "<p> Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Reestablecer contraseña.<a/> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar mail

        $mail->send();
    }

}
