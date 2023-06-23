<?php

namespace App\Telegram\Commands;


use App\Telegram\Handlers\Commands\GenericMessageCommandHandler;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class GenericmessageCommand extends SystemCommand
{
    protected $name = 'genericmessage';

    public function execute(): ServerResponse
    {
        return app()->make(GenericMessageCommandHandler::class)->handle($this->getMessage());
    }
}
