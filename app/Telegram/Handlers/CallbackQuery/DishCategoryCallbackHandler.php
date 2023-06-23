<?php

namespace App\Telegram\Handlers\CallbackQuery;

use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\DishCategoriesSender;
use Longman\TelegramBot\Entities\CallbackQuery;

class DishCategoryCallbackHandler
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var DishCategoriesSender */
    private $dishCategorySender;
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
        $this->dishCategorySender = $dishCategorySender;
        $this->cartSender = $cartSender;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $message = $callbackQuery->getMessage();
        $chatId = $message->getChat()->getId();
        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $addressId = $data['id'];

        $cart = $this->telegramMessageCartResolver->resolve($message);
        $cartCompanyId = $cart->getCompanyId();

        $this->cartService->setCompanyAddressId($cart, $addressId);

        return $this->dishCategorySender->send($chatId, $cartCompanyId);
    }

}
