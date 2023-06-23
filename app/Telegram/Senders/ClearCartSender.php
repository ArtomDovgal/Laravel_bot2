<?php
namespace App\Telegram\Senders;

class ClearCartSender extends TelegramSender
{

    public function send(int $chatId)
    {
        $message = 'The cart has been cleared';

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        return $this->sendData($data);
    }
}