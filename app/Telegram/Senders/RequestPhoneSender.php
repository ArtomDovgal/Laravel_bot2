<?php

namespace App\Telegram\Senders;


use Longman\TelegramBot\Entities\Keyboard;

class RequestPhoneSender extends TelegramSender
{

    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.weNeedYouNumber'),
            'reply_markup' => $this->generateKeyboard()
        ];
        return $this->sendData($data);
    }

    /**
     * @return Keyboard
     */
    private function generateKeyboard(): Keyboard
    {
        return (new Keyboard([
            [
                'text' => trans('bots.sendMyContact'),
                'request_contact' => true,
            ],
        ]))
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

}
