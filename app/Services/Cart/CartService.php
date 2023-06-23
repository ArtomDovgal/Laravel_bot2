<?php
namespace App\Services\Cart;


use App\Models\Order;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Cart\Handlers\CreateOrderHandler;
use App\Services\Cart\Repositories\CartRepositoryInterface;
use Longman\TelegramBot\Entities\Message;

class CartService
{

    /** @var CreateOrderHandler */
    private $createOrderHandler;
    /** @var CartRepositoryInterface */
    private $cartRepository;

    public function __construct(
        CreateOrderHandler $createOrderHandler,
        CartRepositoryInterface $cartRepository
    )
    {
        $this->createOrderHandler = $createOrderHandler;
        $this->cartRepository = $cartRepository;
    }

    public function getOrCreateCart(string $key, array $data = []): CartDTO
    {
        $cart = $this->cartRepository->findByKey($key);
        if (!$cart) {
            $cart = $this->create(CartDTO::fromArray(array_merge([
                'key' => $key,
            ], $data)));
        }
        return $cart;
    }

    /**
     * @param CartDTO $cartDTO
     * @return CartDTO
     */
    public function create(CartDTO $cartDTO): CartDTO
    {
        return $this->storeCart($cartDTO);
    }

    /**
     * @param CartDTO $cartDTO
     * @param array $item
     * @return CartDTO
     */
    public function addItem(CartDTO $cartDTO, array $item): CartDTO
    {
        $cartDTO->addItem($item);
        return $this->storeCart($cartDTO);
    }

    /**
     * @param CartDTO $cartDTO
     * @return CartDTO
     */
    public function storeCart(CartDTO $cartDTO): CartDTO
    {
        return $this->cartRepository->store($cartDTO);
    }
    /**
     * @param CartDTO $cartDTO
     */
    public function clearCart(CartDTO $cartDTO)
    {
       $this->cartRepository->clearItems($cartDTO);
    }
    /**
     * @param CartDTO $cartDTO
     * @return Order
     */
    public function createOrder(Message $message, CartDTO $cartDTO): ?Order
    {
        return $this->createOrderHandler->handle($message, $cartDTO);
    }


    /**
     * @param CartDTO $cartDTO
     * @param string $cityId
     * @return CartDTO
     */
    public function setCityId(CartDTO $cartDTO, string $cityId): CartDTO
    {
        $cartDTO->setCityId($cityId);
        return $this->storeCart($cartDTO);
    }

    /**
     * @param CartDTO $cartDTO
     * @param string $companyId
     * @return CartDTO
     */
    public function setCompanyId(CartDTO $cartDTO, string $companyId): CartDTO
    {
        $cartDTO->setCompanyId($companyId);
        return $this->storeCart($cartDTO);
    }
    public function setCompanyAddressId(CartDTO $cartDTO,string $addressId): CartDTO
    {
        $cartDTO->setAddressId($addressId);
        return $this->storeCart($cartDTO);
    }

}
