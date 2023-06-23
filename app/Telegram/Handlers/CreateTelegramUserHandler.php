<?php

namespace App\Telegram\Handlers;

use App\Models\User;
use App\Services\Users\UsersService;
use Longman\TelegramBot\Entities\Message;

class CreateTelegramUserHandler
{
    /** @var UsersService */
    private $usersService;
    public function __construct(
        UsersService $usersService
    )
    {
        $this->usersService = $usersService;
    }

    /**
     * @param Message $message
     * @return User
     */
    public function handle(Message $message) : User
    {
        $telegramUserId = $message->getFrom()->getId();
        $user = $this->usersService->findUserByTelegramId($telegramUserId);

        if($user) {

            if ($message->getContact()) {

                $this->usersService->updateUser($user, [
                    'phone' => $message->getContact()->getPhoneNumber(),
                ]);
            }
            return $user;
        }

        return $this->usersService->createUser([
            'telegram_id' => $telegramUserId,
            'name' => $message->getFrom()->getFirstName(),
            'phone' => $message->getContact() ? $message->getContact()->getPhoneNumber() : null
        ]);
    }
}
