<?php

namespace App\Mail;

use Resend;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * ResendTransport is a custom mail transport for sending emails using the Resend API.
 * It extends the AbstractTransport class and implements the doSend method to handle
 * the actual sending of the email.
 */
class ResendTransport extends AbstractTransport
{
    public function __construct(
        private string $apiKey,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $resend = Resend::client($this->apiKey);

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $payload = [
            'from' => $email->getFrom()[0]->toString(),
            'to' => array_map(fn($address) => $address->toString(), $email->getTo()),
            'subject' => $email->getSubject(),
        ];

        if ($email->getHtmlBody()) {
            $payload['html'] = $email->getHtmlBody();
        }

        if ($email->getTextBody()) {
            $payload['text'] = $email->getTextBody();
        }

        $resend->emails->send($payload);
    }

    public function __toString(): string
    {
        return 'resend';
    }
}