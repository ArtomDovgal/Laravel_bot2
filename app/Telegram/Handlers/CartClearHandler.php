<?php

namespace App\Telegram\Handlers;
use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\ClearCartSender;
use Longman\TelegramBot\Entities\Message;
class CartClearHandler
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var CartService */
    private $cartService;
    /** @var ClearCartSender */
    private $clearCartSender;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService $cartService,
        ClearCartSender $clearCartSender
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->clearCartSender=$clearCartSender;
    }

    public function handle(Message $message)
    {
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $chatId = $message->getChat()->getId();
        $this->cartService->clearCart($cart);

        return $this->clearCartSender->send($chatId);;
    }

}