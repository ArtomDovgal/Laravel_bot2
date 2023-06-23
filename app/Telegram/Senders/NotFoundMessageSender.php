<?php

namespace App\Telegram\Senders;

class NotFoundMessageSender
{

    /** @var MessageSender */
    private $messageSender;

    public function __construct(
        MessageSender $messageSender
    )
    {
        $this->messageSender = $messageSender;
    }

    public function send(int $chatId)
    {
        return $this->messageSender->send(
            $chatId,
            trans('bots.commandNotFound')
        );
    }

}
