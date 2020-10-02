<?php

namespace Dizy\FlarumCustomSMTP;

use Flarum\Mail\DriverInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\MessageBag;
use Swift_SmtpTransport;
use Swift_Transport;

class CustomSMTPDriver implements DriverInterface
{

    public function availableSettings(): array
    {
        return [
            'mail_host' => '', // a hostname, IPv4 address or IPv6 wrapped in []
            'mail_port' => '', // a number, defaults to 25
            'mail_encryption' => '', // "tls" or "ssl"
            'mail_username' => '',
            'mail_password' => '',
        ];
    }

    public function validate(SettingsRepositoryInterface $settings, Factory $validator): MessageBag
    {
        return $validator->make($settings->all(), [
            'mail_host' => 'required',
            'mail_port' => 'nullable|integer',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_username' => 'required',
            'mail_password' => 'required',
        ])->errors();
    }

    public function canSend(): bool
    {
        return true;
    }

    public function buildTransport(SettingsRepositoryInterface $settings): Swift_Transport
    {
        $transport = new Swift_SmtpTransport(
            $settings->get('mail_host'),
            $settings->get('mail_port'),
            $settings->get('mail_encryption')
        );

        $transport->setStreamOptions([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $transport->setUsername($settings->get('mail_username'));
        $transport->setPassword($settings->get('mail_password'));

        return $transport;
    }
}