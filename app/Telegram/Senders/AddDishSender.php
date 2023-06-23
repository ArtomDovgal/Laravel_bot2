<?php

namespace App\Telegram\Senders;

class AddDishSender extends TelegramSender
{

    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.dishIsAdded'),
        ];
        return $this->sendData($data);
    }

    public function DishNotFound(int $chatId){
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.dishNotFound'),
        ];
        return $this->sendData($data);
    }
}
