<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

class BaseCommand extends SystemCommand
{
    public function __construct(
        Telegram $telegram, Update $update = null
    )
    {
        parent::__construct($telegram, $update);
    }


}
