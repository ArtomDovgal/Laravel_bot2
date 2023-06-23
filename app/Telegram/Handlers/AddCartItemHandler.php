<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Dots\DotsService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\AddDishSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use App\Telegram\Senders\TelegramSender;

class AddCartItemHandler
{

    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var CartService */
    private $cartService;
    /** @var DotsService */
    private $dotsService;
    /** @var AddDishSender */
    private $addDishToCartSender;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService                 $cartService,
        DotsService                 $dotsService,
        AddDishSender               $addDishToCartSender,
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->dotsService = $dotsService;
        $this->addDishToCartSender = $addDishToCartSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $message = $callbackQuery->getMessage();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $data = $callbackQuery->getData();
        $companyId = $cart->getCompanyId();
        $chatId = $message->getChat()->getId();
        $data = json_decode($data, true);
        $dish = $this->dotsService->findDishById($data['id'], $companyId);

        if(!$dish){
            return $this->addDishToCartSender->DishNotFound($chatId);
        }

        $this->cartService->addItem($cart, [
            'dish_id' => $dish['id'],
            'name' => $dish['name'],
            'price' => $dish['price'],
        ]);

        return $this->addDishToCartSender->send($chatId);
    }

}
