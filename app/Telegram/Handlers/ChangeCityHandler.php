<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\Repositories\CartRepositoryInterface;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\CartSender;
use Longman\TelegramBot\Entities\CallbackQuery;

class ChangeCityHandler
{
    /** @var CartSender */
    private $cartSender;
    /** @var CartRepositoryInterface */
    private $cartRepository;
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    public function __construct(
        CartSender $cartSender,
        CartRepositoryInterface $cartRepository,
        TelegramMessageCartResolver $telegramMessageCartResolver,
    )
    {
        $this->cartSender = $cartSender;
        $this->cartRepository = $cartRepository;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $message = $callbackQuery->getMessage();
        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $answer = $data['value'];

        $cart = $this->telegramMessageCartResolver->resolve($message);

        if($answer == 'yes'){
            $this->cartRepository->clearItems($cart);
            $this->cartRepository->clearCity($cart);
            $this->cartRepository->clearCompany($cart);
            return $this->cartSender->sendChangeCitySuccessful($chatId);
        } elseif($answer == 'no'){
            return $this->cartSender->sendRefuseChangeCity($chatId);
        }
    }
}
