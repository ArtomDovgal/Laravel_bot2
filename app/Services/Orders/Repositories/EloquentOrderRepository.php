<?php

namespace App\Services\Orders\Repositories;

use App\Models\Order;
class EloquentOrderRepository implements OrderRepositoryInterface
{

    /**
     * @param int $id
     * @return Order|null
     */
    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    /**
     * @param array $data
     * @return Order
     */
    public function createFromArray(array $data): Order
    {
        return Order::create($data);
    }

}
