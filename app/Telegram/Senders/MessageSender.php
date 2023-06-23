<?php

namespace App\Telegram\Senders;

use Longman\TelegramBot\Entities\ServerResponse;

class MessageSender extends TelegramSender
{
    /**
     * @param int $chatId
     * @param string $message
     * @return ServerResponse|null
     */
    public function send(int $chatId, string $message)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => $message,
        ];
        return $this->sendData($data);
    }
}
