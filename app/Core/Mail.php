<?php

namespace  App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class Mail
{
    /** @var PHPMailer */
    private $mail;

    private $bootstrap;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->setLanguage($_ENV['CONFIG_MAIL_LANGUAGE']);
        $this->mail->isHTML($_ENV['CONFIG_MAIL_IS_HTML']);
        $this->mail->SMTPAuth   = $_ENV['CONFIG_MAIL_AUTH'];
        $this->mail->SMTPSecure = $_ENV['CONFIG_MAIL_SECURE'];
        $this->mail->CharSet    = $_ENV['CONFIG_MAIL_CHARSET'];

        $this->mail->Host       = $_ENV['CONFIG_MAIL_HOST'];
        $this->mail->Username   = $_ENV['CONFIG_MAIL_USERNAME'];
        $this->mail->Password   = $_ENV['CONFIG_MAIL_PASSWORD'];
        $this->mail->Port       = $_ENV['CONFIG_MAIL_PORT'];
    }

    public function bootstrap(string $subject, string $message, string $toEmail, string $toName): Mail
    {
        $this->bootstrap = new \stdClass();
        $this->bootstrap->subject = $subject;
        $this->bootstrap->message = $message;
        $this->bootstrap->toEmail = $toEmail;
        $this->bootstrap->toName = $toName;
        return $this;
    }

    public function send($fromEmail = 'noreply@promofarma.com.br', $fromName = 'NoReply'): bool
    {
        if (empty($this->bootstrap)) {
            $this->message = "Error ao Enviar o e-mail por favor verifique os dados.";
            return false;
        }

        if (!filter_var($this->bootstrap->toEmail, FILTER_VALIDATE_EMAIL)) {
            $this->message = "Por favor verifique o endereço de e-mail para qual está tentando enviar.";
            return false;
        }

        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $this->message = "Por favor verifique seu endereço de e-mail.";
            return false;
        }

        try {
            $this->mail->setFrom($fromEmail, $fromName);
            $this->mail->Subject = $this->bootstrap->subject;
            $this->mail->msgHTML($this->bootstrap->message);
            $this->mail->addAddress($this->bootstrap->toEmail, $this->bootstrap->toName);

            $this->mail->send();
            return true;
        } catch (MailException $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    public function eMail(): PHPMailer
    {
        return $this->mail;
    }
}
