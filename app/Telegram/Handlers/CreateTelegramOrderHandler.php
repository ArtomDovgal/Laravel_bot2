<?php

namespace App\Telegram\Handlers;

use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Dots\DotsService;
use App\Telegram\Handlers\Senders\SendAdminOrderMessageHandler;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\MessageSender;
use Longman\TelegramBot\Entities\Message;

class CreateTelegramOrderHandler
{

    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    /** @var DotsService */
    private $dotsService;
    /** @var MessageSender */
    private $messageSender;
    /** @var CartService */
    private $cartService;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
        MessageSender $messageSender,
        CartService $cartService,
        DotsService $dotsService
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->dotsService = $dotsService;
        $this->messageSender = $messageSender;
        $this->cartService = $cartService;
    }

    /**
     * @param Message $message
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function handle(Message $message)
    {

        $cart = $this->telegramMessageCartResolver->resolve($message);

        if ($cart->getItems() == []) {
            return $this->sendCartEmptyMessageToUser($message);
        }

        $order = $this->cartService->createOrder($message, $cart);

        if(!$order){
            return $this->sendOrderNotCreate($message);
        }

        return $this->sendSuccessMessageToUser($message, $order);
    }

    /**
     * @param Message $message
     */
    private function sendCartEmptyMessageToUser(Message $message)
    {
        $text = trans('bots.cartEmpty');

        return $this->messageSender->send($message->getChat()->getId(), $text);
    }

    /**
     * @param Message $message
     */
    private function sendOrderNotCreate(Message $message)
    {
        $text = trans('bots.orderNotCreate');

        return $this->messageSender->send($message->getChat()->getId(), $text);
    }

    /**
     * @param Message $message
     * @param Order $order
     */
    private function sendSuccessMessageToUser(Message $message, Order $order)
    {
        $text = $this->generateSuccessMessage($order);

        return $this->messageSender->send($message->getChat()->getId(), $text);
    }

    /**
     * @param Order $order
     * @return string
     */
    private function generateSuccessMessage(Order $order): string
    {
        $message = trans('bots.yourOrderSuccessfullyCreated');
        if ($order->paymentUrl) {
            $message .= ' ';
            $message .= sprintf(trans('bots.payItOnline'), $order->paymentUrl);
        }
        return $message;
    }

}
