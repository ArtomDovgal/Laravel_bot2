<?php

namespace App\Services\Cart\Repositories;


use Illuminate\Support\Facades\Redis;
use App\Services\Cart\DTO\CartDTO;
use Longman\TelegramBot\Request;

class CacheCartRepository implements CartRepositoryInterface
{

    const CART_KEY_PREFIX = 'cart-key-';

    /**
     * @param string $key
     * @return ?CartDTO
     */
    public function findByKey(string $key): ?CartDTO
    {
        $data = $this->get($key);
        if (!$data) {
            return null;
        }
        return CartDTO::fromArray($data);
    }

    /**
     * @param CartDTO $cartDTO
     * @return CartDTO
     */
    public function store(CartDTO $cartDTO): CartDTO
    {
        $this->set($cartDTO->getKey(), $cartDTO->toArray());
        return $cartDTO;
    }

    /**
     * @param CartDTO $cartDTO
     */
    public function clearCity(CartDTO $cartDTO)
    {
        $cartDTO->clearCity();
        $this->store($cartDTO);
    }

    /**
     * @param CartDTO $cartDTO
     */
    public function clearCompany(CartDTO $cartDTO)
    {
        $cartDTO->clearCompany();
        $this->store($cartDTO);
    }

    /**
     * @param CartDTO $cartDTO
     */
    public function clearItems(CartDTO $cartDTO)
    {
        $cartDTO->clearItems();
        $this->store($cartDTO);
    }

    /**
     * @param string $key
     * @return array|null
     */
    private function get(string $key): ?array
    {
        $data = \Cache::get($this->generateCartId($key));
        return json_decode($data, true);
    }

    /**
     * @param string $key
     * @param array|null $data
     */
    private function set(string $key, ?array $data)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        \Cache::put($this->generateCartId($key), $data);
    }

    /**
     * @param string $key
     * @return string
     */
    private function generateCartId(string $key): string
    {
        return md5(self::CART_KEY_PREFIX . $key);
    }
}
