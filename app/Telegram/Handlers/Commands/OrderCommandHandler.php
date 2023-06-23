<?php

namespace App\Telegram\Handlers\Commands;


use App\Telegram\Handlers\AddCartItemHandler;
use App\Telegram\Handlers\CreateTelegramOrderHandler;
use App\Telegram\Resolvers\MessageDishResolver;
use Longman\TelegramBot\Entities\Message;

class OrderCommandHandler
{

    /** @var CreateTelegramOrderHandler */
    private $createTelegramOrderHandler;

    public function __construct(
        CreateTelegramOrderHandler $createTelegramOrderHandler
    )
    {
        $this->createTelegramOrderHandler = $createTelegramOrderHandler;
    }

    public function handle(Message $message)
    {
        return $this->createTelegramOrderHandler->handle($message);
    }

}
