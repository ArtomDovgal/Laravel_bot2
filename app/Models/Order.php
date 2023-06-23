<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Order
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property array $items
 * @property array $location
 * @property string $address
 * @property string $userName
 * @property string $userPhone
 * @property string $paymentUrl
 */

class Order extends Model
{
    protected $casts = [
        'items'=>'array',
        'location'=>'array'
    ];

    protected $fillable = [
        'user_id',
        'items',
        'company_id',
        'locations',
        'address',
        'userName',
        'userPhone',
        'paymentUrl',
    ];
}
