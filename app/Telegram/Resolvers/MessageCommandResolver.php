<?php
/**
 * Description of MessageCommandResolver.php
 * @copyright Copyright (c) MISTER.AM, LLC
 * @author    Egor Gerasimchuk <egor@mister.am>
 */

namespace App\Telegram\Resolvers;


use App\Telegram\Commands\Command;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class MessageCommandResolver
{

    /**
     * @param Message $message
     * @return string|null
     */
    public function resolve(Message $message): ?string
    {

        if ($message->getText() === trans('bots.changeCity')) {
            return Command::CITY;
        } elseif ($message->getText() === trans('bots.changeCompany')) {
            return Command::COMPANY;
        } elseif ($message->getText() === trans('bots.selectDishes')) {
            return Command::DISHES;
        } elseif ($message->getText() === trans('bots.showCart')) {
            return Command::CART;
        } elseif ($message->getText() === trans('bots.clearCart')) {
            return Command::CLEAR;
        } elseif ($message->getText() === trans('bots.makeOrder')) {
            return Command::ORDER;
        } elseif ($this->isCommandLocationSharing($message)) {
            return Command::LOCATION;
        }
        return null;
    }
    /**
     * @param Message $message
     * @return bool
     */
    private function isCommandContactSharing(Message $message): bool
    {
        if ($message->getContact() && $message->getContact()->getPhoneNumber()) {
            return true;
        }
        return $this->isTextPhoneNumber($message->getText(true) ?: '');
    }

    /**
     * @param string $phone
     * @return bool
     */
    private function isTextPhoneNumber(string $phone): bool
    {
        return is_numeric($phone);
    }

    /**
     * @param Message $message
     * @return bool
     */
    private function isCommandLocationSharing(Message $message): bool
    {
        return $message->getLocation() && $message->getLocation()->getLatitude();
    }

}
