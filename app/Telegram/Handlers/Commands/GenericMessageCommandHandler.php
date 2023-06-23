<?php

namespace App\Telegram\Handlers\Commands;


use App\Telegram\Commands\Command;
use App\Telegram\Handlers\AddressHandler;
use App\Telegram\Handlers\CompanyHandler;
use App\Telegram\Handlers\DishCategoryMessageHandler;
use App\Telegram\Resolvers\MessageCommandResolver;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\CitiesSender;
use App\Telegram\Senders\CompaniesSender;
use App\Telegram\Senders\NotFoundMessageSender;
use Longman\TelegramBot\Entities\Message;
use App\Telegram\Senders\ClearCartSender;
use App\Telegram\Handlers\CartClearHandler;

class GenericMessageCommandHandler
{

    /** @var ContactCommandHandler */
    private $phoneCommandHandler;
    /** @var OrderCommandHandler */
    private $orderCommandHandler;
    /** @var NotFoundMessageSender */
    private $notFoundMessageSender;
    /** @var MessageCommandResolver */
    private $messageCommandResolver;
    /** @var CitiesSender */
    private $citySender;
    /** @var ClearCartSender */
    private $clearCartSender;
    /** @var CartSender */
    private $cartSender;

    public function __construct(
        ContactCommandHandler  $phoneCommandHandler,
        OrderCommandHandler    $orderCommandHandler,
        NotFoundMessageSender  $notFoundMessageSender,
        MessageCommandResolver $messageCommandResolver,
        CitiesSender           $citySender,
        CartSender             $cartSender,
        ClearCartSender $clearCartSender
    )
    {
        $this->phoneCommandHandler = $phoneCommandHandler;
        $this->orderCommandHandler = $orderCommandHandler;
        $this->notFoundMessageSender = $notFoundMessageSender;
        $this->messageCommandResolver = $messageCommandResolver;
        $this->citySender = $citySender;
        $this->cartSender = $cartSender;
        $this->clearCartSender=$clearCartSender;
    }

    public function handle(Message $message)
    {
        $command = $this->messageCommandResolver->resolve($message);

        switch ($command) {
            case Command::ORDER:
                return $this->orderCommandHandler->handle($message);
            case Command::CITY:
                return $this->citySender->send($message->getFrom()->getId());
            case Command::COMPANY:
                return app(CompaniesSender::class)->handleMessage($message);
            case Command::ADDRESS:
                return app(AddressHandler::class)->handleMessage($message);
            case Command::CART:
                return $this->cartSender->sendCart($message);
            case Command::CLEAR:
                return app(CartClearHandler::class)->handle($message);
            case Command::DISHES:
                return app(DishCategoryMessageHandler::class)->handle($message);
            default:
                return $this->notFoundMessageSender->send($message->getChat()->getId());
        }
    }

}
