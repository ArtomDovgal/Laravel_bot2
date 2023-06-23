<?php

namespace App\Telegram\Senders;

use Longman\TelegramBot\Entities\Keyboard;

class MenuSender extends TelegramSender
{
    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.selectAction'),
            'reply_markup' => $this->getTelegramMenuKeyboard()
        ];
        return $this->sendData($data);
    }

    /**
     * @return Keyboard
     */
    private function getTelegramMenuKeyboard(): Keyboard
    {
        $items = [
            [
                ['text' => trans('bots.showCart')],
                ['text' => trans('bots.clearCart')],
            ],
            [
                ['text' => trans('bots.changeCity')],
                ['text' => trans('bots.changeCompany')],
            ],
            [
                ['text' => trans('bots.selectDishes')]
            ],
            [
                ['text' => trans('bots.makeOrder')]
            ]
        ];
        $keyboard = new Keyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false)
            ->setOneTimeKeyboard(false);
    }
}
