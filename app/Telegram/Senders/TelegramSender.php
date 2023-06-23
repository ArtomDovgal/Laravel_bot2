<?php

namespace App\Telegram\Senders;

use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

abstract class TelegramSender
{
    /**
     * @param array $data
     * @return ServerResponse|null
     */
    protected function sendData(array $data): ?ServerResponse
    {
        try {
            return Request::sendMessage($data);
        } catch (TelegramException $e) {
            \Log::warning($e->getMessage(), $data);
        }
        return null;
    }
}
