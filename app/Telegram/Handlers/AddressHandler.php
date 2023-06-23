<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\AddDishSender;
use App\Telegram\Senders\AddressCompanySender;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\CompaniesSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;

class AddressHandler
{
    /** @var AddressCompanySender */
    private $addressSender;
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    /** @var CartService */
    private $cartService;
    /** @var CartSender */
    private $cartSender;

    public function __construct(
        AddressCompanySender             $addressSender,
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService                 $cartService,
        CartSender                  $cartSender,
    )
    {
        $this->addressSender= $addressSender;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->cartSender = $cartSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $message = $callbackQuery->getMessage();
        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $companyId = $data['id'];
        $chatId = $message->getChat()->getId();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $this->cartService->setCompanyId($cart,  $companyId,);

        return $this->addressSender->send($chatId, $companyId);
    }

    public function handleMessage(Message $message)
    {
        $cart = $this->telegramMessageCartResolver->resolve($message);

        $companyId = $cart->getCompanyId();
        $chatId = $message->getChat()->getId();

        return $this->addressSender->send($chatId, $companyId);
    }
}
