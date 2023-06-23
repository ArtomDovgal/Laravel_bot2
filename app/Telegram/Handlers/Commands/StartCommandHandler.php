<?php

namespace App\Telegram\Handlers\Commands;

use App\Telegram\Senders\CitiesSender;
use App\Telegram\Senders\MenuSender;
use App\Telegram\Senders\RequestPhoneSender;
use App\Telegram\Handlers\CreateTelegramUserHandler;
use Longman\TelegramBot\Commands\SystemCommand;

class StartCommandHandler
{
    /** @var CreateTelegramUserHandler  */
    private $createTelegramUserHandler;
    /** @var CitiesSender */
    private $citySender;
    /** @var MenuSender */
    private $telegramMenuSender;
    /** @var RequestPhoneSender */
    private $requestPhoneSender;

    public function __construct(
        CreateTelegramUserHandler $createTelegramUserHandler,
        CitiesSender              $citySender,
        MenuSender                $telegramMenuSender,
        RequestPhoneSender        $requestPhoneSender
    )
    {
        $this->createTelegramUserHandler = $createTelegramUserHandler;
        $this->citySender = $citySender;
        $this->telegramMenuSender = $telegramMenuSender;
        $this->requestPhoneSender = $requestPhoneSender;
    }

    public function handle(SystemCommand $systemCommand)
    {
        $user = $this->createTelegramUserHandler->handle($systemCommand->getMessage());
        if (!$user->phone) {
            return $this->requestPhoneSender->send($user->telegram_id);
        }

        return $this->telegramMenuSender->send($user->telegram_id);
    }
}
