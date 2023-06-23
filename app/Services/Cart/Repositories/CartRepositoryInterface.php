<?php


namespace App\Services\Cart\Repositories;

use App\Services\Cart\DTO\CartDTO;

interface CartRepositoryInterface
{
    public function findByKey(string $key): ?CartDTO;

    public function store(CartDTO $cartDTO): CartDTO;

    public function clearItems(CartDTO $cartDTO);

    public function clearCity(CartDTO $cartDTO);

    public function clearCompany(CartDTO $cartDTO);

}
