<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Telegram\Handlers\Commands\ContactCommandHandler;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class ContactCommand extends BaseCommand
{
    protected $name = 'contact';
    protected $usage = '/contact';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $contact = $message->getContact();

        $data = [
            'chat_id' => $chat_id,
            'text' => '',
        ];
        Request::sendMessage($data);
        return app(ContactCommandHandler::class)->handle($this);
    }
}
