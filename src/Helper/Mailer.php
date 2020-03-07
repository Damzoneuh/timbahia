<?php


namespace App\Helper;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;

trait Mailer
{
   public function createMessage($from, $to, $vue, $context = [], $cc = [], $attachment = null){
       $message = new TemplatedEmail();
       $message->from($from);
       $message->to($to);
       $message->context($context);
       $message->htmlTemplate($vue);
       if (count($cc) > 0){
           foreach ($cc as $copied){
               $message->addCc($copied);
           }
       }
       if ($attachment){
           $message->attach($attachment);
       }

       return $message;
   }
}