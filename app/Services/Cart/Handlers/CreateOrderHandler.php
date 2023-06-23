<?php

namespace App\Services\Cart\Handlers;

use App\Models\Order;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Cart\Repositories\CartRepositoryInterface;
use App\Services\Orders\OrdersService;
use Longman\TelegramBot\Entities\Message;

class CreateOrderHandler
{

    /** @var OrdersService */
    private $ordersService;
    /** @var CartRepositoryInterface */
    private $cartRepository;

    public function __construct(
        OrdersService $ordersService,
        CartRepositoryInterface $cartRepository
    )
    {
        $this->ordersService = $ordersService;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param CartDTO $cartDTO
     * @return Order
     */
    public function handle(Message $message, CartDTO $cartDTO): ?Order
    {
        $order = $this->ordersService->createOrder($message, $cartDTO);
        $this->cartRepository->clearItems($cartDTO);
        return $order;
    }

}
