<?php

namespace App\Telegram\Commands;

use App\Telegram\Handlers\CallbackQuery\CallbackQueryHandler;
use Illuminate\Contracts\Container\BindingResolutionException;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackQueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';

    /**
     * @throws BindingResolutionException
     */
    public function execute(): ServerResponse
    {
        $response = app()->make(CallbackQueryHandler::class)->handle($this);
        $this->getCallbackQuery()->answer();
        return $response;
    }
}
