<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Orders\Handlers\CreateOrderHandler;
use App\Services\Orders\Repositories\OrderRepositoryInterface;
use Longman\TelegramBot\Entities\Message;

class OrdersService
{

    /** @var CreateOrderHandler */
    private $createOrderHandler;
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        CreateOrderHandler $createOrderHandler,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->createOrderHandler = $createOrderHandler;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param CartDTO $cartDTO
     * @return Order
     */
    public function createOrder(Message $message, CartDTO $cartDTO): ?Order
    {
        return $this->createOrderHandler->handle($message, $cartDTO);
    }

}
