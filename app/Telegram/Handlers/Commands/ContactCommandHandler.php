<?php

namespace App\Telegram\Handlers\Commands;

use App\Services\Users\UsersService;
use App\Telegram\Senders\CitiesSender;
use App\Telegram\Senders\ContactSender;
use App\Telegram\Senders\MenuSender;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class ContactCommandHandler extends StartCommandHandler
{
    /** @var UsersService */
    private $usersService;
    /** @var ContactSender */
    private $contactSender;
    /** @var CitiesSender */
    private $citySender;
    /** @var MenuSender */
    private $telegramMenuSender;
    public function __construct(
        UsersService  $usersService,
        ContactSender $contactSender,
        CitiesSender  $citySender,
        MenuSender    $telegramMenuSender,
    )
    {
        $this->usersService = $usersService;
        $this->contactSender = $contactSender;
        $this->citySender = $citySender;
        $this->telegramMenuSender = $telegramMenuSender;
    }

    public function handle(SystemCommand $systemCommand): ?ServerResponse
    {
        $message = $systemCommand->getMessage();
        $telegramUserId = $message->getFrom()->getId();
        $user = $this->usersService->findUserByTelegramId($telegramUserId);
        if($user) {
            if($message->getContact()){
                $phoneNumber = $message->getContact()->getPhoneNumber();
                if(str_starts_with($phoneNumber, '+')){
                    $phoneNumber = substr($phoneNumber, 1);
                }
                $this->usersService->updateUser($user, [
                    'phone' => $phoneNumber,
                ]);
            }
        }
        $this->contactSender->send($user->telegram_id);
        $this->telegramMenuSender->send($user->telegram_id);
        return $this->citySender->send($user->telegram_id);
    }
}
