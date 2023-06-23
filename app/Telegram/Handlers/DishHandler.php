<?php

namespace App\Telegram\Handlers;

use App\Telegram\Senders\DishesSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class DishHandler
{
    /** @var DishesSender */
    private $dishesSender;

    public function __construct(DishesSender $dishSender)
    {
        $this->dishesSender = $dishSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        return $this->dishesSender->send($callbackQuery);
    }
}
