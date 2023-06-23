<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\CompaniesSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;

class CompanyHandler
{
    /** @var CompaniesSender */
    private $companySender;
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var CartService */
    private $cartService;
    /** @var CartSender */
    private $cartSender;

    public function __construct(
        CompaniesSender             $companySender,
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService                 $cartService,
        CartSender                  $cartSender,
    )
    {
        $this->companySender = $companySender;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->cartSender = $cartSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $message = $callbackQuery->getMessage();
        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $cityId = $data['id'];
        $chatId = $message->getChat()->getId();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $cartCityId = $cart->getCityId();
        $dishes = $cart->getItems();

        if($cartCityId != $cityId && $cartCityId != ' ' && $dishes != []){

            return $this->cartSender->sendRequireChangeCity($chatId);
        }
        $this->cartService->setCityId($cart, $cityId);

        return $this->companySender->send($chatId, $cityId);
    }

    public function handleMessage(Message $message)
    {
        $cart = $this->telegramMessageCartResolver->resolve($message);

        $cityId = $cart->getCityId();
        $chatId = $message->getChat()->getId();

        return $this->companySender->send($chatId, $cityId);
    }
}
