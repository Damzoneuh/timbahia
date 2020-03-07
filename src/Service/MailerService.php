<?php


namespace App\Service;

use App\Helper\Mailer;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $_mailer;
    use Mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->_mailer = $mailer;
    }

    /**
     * @param $email
     * @param $token
     * @return bool
     * @throws Exception
     */
    public function sendRegistrationMail($email, $token){
        $message = $this->createMessage('noreply@timbahia.fr', $email, 'email/registration.html.twig', ['token' => $token]);
        try {
            $this->_mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e);
        }
        return true;
    }
}