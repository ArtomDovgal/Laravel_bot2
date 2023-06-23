<?php

namespace App\Services\Orders\Handlers;

use App\Models\Order;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Dots\DotsService;
use App\Services\Dots\DTO\OrderDTO;
use App\Services\Orders\Repositories\OrderRepositoryInterface;
use App\Telegram\Senders\MessageSender;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class CreateOrderHandler
{

    /** @var DotsService */
    private $dotsService;
    /** @var OrderRepositoryInterface */
    private $orderRepository;
    /** @var MessageSender */
    private $messageSender;

    public function __construct(
        DotsService $dotsService,
        OrderRepositoryInterface $orderRepository,
        MessageSender $messageSender,
    ) {
        $this->dotsService = $dotsService;
        $this->orderRepository = $orderRepository;
        $this->messageSender = $messageSender;
    }

    /**
     * @param CartDTO $cartDTO
     * @return Order
     */
    public function handle(Message $message, CartDTO $cartDTO): ?Order
    {
        $dotsOrderData = $this->dotsService->makeOrder($this->generateDotsOrderData($cartDTO));

        if(!$this->checkOrderId($dotsOrderData)){
            return null;
        }

        return $this->orderRepository->createFromArray($this->generateOrderData($cartDTO, $dotsOrderData));
    }

    private function  checkOrderId(array $dotsOrderData): bool{
        if(array_key_exists('id', $dotsOrderData)) return true;

        return false;
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateDotsOrderData(CartDTO $cartDTO): array
    {
        $companyId = $cartDTO->getCompanyId();

        return [
            'cityId' => $cartDTO->getCityId(),
            'companyId' => $companyId,
            'companyAddressId' => $cartDTO->getAddressId(),
            'userName' => $cartDTO->getUser()->getName(),
            'userPhone' => $cartDTO->getUser()->getPhone(),
            'deliveryType' => OrderDTO::DELIVERY_PICKUP,
            'deliveryTime' => OrderDTO::DELIVERY_TIME_FASTEST,
            'paymentType' => OrderDTO::PAYMENT_ONLINE,
            'cartItems' => $this->generateDotsOrderCartData($cartDTO),
        ];
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateDotsOrderCartData(CartDTO $cartDTO): array
    {
        $result = [];
        foreach ($cartDTO->getItems() as $item) {
            $result[] = [
                'id' => $item->getDishId(),
                'count' => $item->getCount(),
                'price' => $item->getPrice(),
            ];
        }
        return $result;
    }

    /**
     * @param CartDTO $cartDTO
     * @param array $dotsOrderData
     * @return array
     */
    private function generateOrderData(CartDTO $cartDTO, array $dotsOrderData): array
    {
        $data = $this->generateOrderDataFromCart($cartDTO);

        return $data;
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateOrderDataFromCart(CartDTO $cartDTO): array
    {
        return [
            'userName' => $cartDTO->getUser()->getName(),
            'userPhone' => $cartDTO->getUser()->getPhone(),
            'user_id' => $cartDTO->getUser()->getId(),
            'items' => $cartDTO->getItemsArray(),
            'company_id' => $cartDTO->getCompanyId(),
        ];
    }

}
