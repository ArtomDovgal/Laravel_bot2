<?php

namespace App\Http\Controllers\Bots;

use App\Http\Controllers\Controller;
use Longman\TelegramBot\Telegram;

class TelegramController extends Controller
{
    /** @var Telegram  */
    private $telegramBot;

    public function __construct(
        Telegram $telegramBot
    )
    {
        $this->telegramBot = $telegramBot;
    }

    public function updates()
    {
        return $this->telegramBot->handleGetUpdates();
    }

}
