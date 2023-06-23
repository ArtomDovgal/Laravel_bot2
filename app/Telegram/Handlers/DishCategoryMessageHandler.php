<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\DishCategoriesSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class DishCategoryMessageHandler
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var DishCategoriesSender */
    private $dishCategoriesSender;
    /** @var CartSender */
    private $cartSender;
    /** @var CartService */
    private $cartService;

    public function __construct(
        DishCategoriesSender        $dishCategorySender,
        CartSender                  $cartSender,
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService                 $cartService,
    )
    {
        $this->dishCategoriesSender = $dishCategorySender;
        $this->cartSender = $cartSender;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
    }

    public function handle(Message $message)
    {
        $chatId = $message->getChat()->getId();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $companyId = $cart->getCompanyId();
        return $this->dishCategoriesSender->send($chatId, $companyId);
    }

}
