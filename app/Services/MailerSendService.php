<?php

namespace App\Services;

use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;

class MailerSendService
{
    private MailerSend $client;

    public function __construct()
    {
        $config = config('mailersend-driver');

        $this->client = new MailerSend([
            'api_key' => $config['api_key'],
            'host' => $config['host'],
            'protocol' => $config['protocol'],
            'api_path' => $config['api_path'],
        ]);
    }

    public function client(): MailerSend
    {
        return $this->client;
    }

    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $html,
        ?string $text = null,
        ?string $fromEmail = null,
        ?string $fromName = null,
    ): void {
        $recipients = [
            new Recipient($toEmail, $toName),
        ];

        $emailParams = (new EmailParams())
            ->setFrom($fromEmail ?? config('mail.from.address'))
            ->setFromName($fromName ?? config('mail.from.name'))
            ->setRecipients($recipients)
            ->setSubject($subject)
            ->setHtml($html);

        if ($text !== null) {
            $emailParams->setText($text);
        }

        $this->client->email->send($emailParams);
    }
}
