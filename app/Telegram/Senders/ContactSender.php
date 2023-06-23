<?php

namespace App\Telegram\Senders;

class ContactSender extends TelegramSender
{
    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.phoneSuccessfullyUpdated'),
        ];
        return $this->sendData($data);
    }
}
