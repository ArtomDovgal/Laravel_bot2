<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Telegram\Handlers\Commands\StartCommandHandler;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends BaseCommand
{
    protected $name = 'start';
    protected $usage = '/start';

    public function execute(): ServerResponse
    {
        return app(StartCommandHandler::class)->handle($this);
    }
}
