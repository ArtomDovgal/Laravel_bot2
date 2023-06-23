<?php

namespace App\Telegram\Senders;

use App\Services\Cart\CartService;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Cart\DTO\CartItemDTO;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Message;
use PhpParser\Node\Expr\Isset_;

class CartSender extends TelegramSender
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    /** @var CartService*/
    private $cartService;
    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,CartService $cartService
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
    }

    public function sendCart(Message $message){
        $chatId = $message->getFrom()->getId();

        $cart = $this->telegramMessageCartResolver->resolve($message);

        if($this->getCartInfo($cart)){
            $text = $this->getCartInfo($cart);
        } else {
            $text = trans('bots.cartEmpty');
        }

        $data = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];
        return $this->sendData($data);
    }

    private function getCartInfo(CartDTO $cart): string
    {
        return $this->generateCarItemsMessage($cart);
    }
    /**
     * @param CartDTO $cart
     * @return string
     */
    private function generateCarItemsMessage(CartDTO $cart): string
    {
        $totalCost = 0;
        foreach ($cart->getItems() as $item) {
            $totalCost += $item->getPrice();
        }
        $result = [];
        foreach ($cart->getItems() as $item) {
            $result[] = $this->generateCartItemMessage($item);
        }
        if($totalCost > 0 )
            return implode(PHP_EOL, $result) . "\n" .'Total cost : ' . $totalCost . 'UAH';
        else
            return implode(PHP_EOL, $result);
    }

    /**
     * @param array $item
     * @return string
     */
    private function generateCartItemMessage(CartItemDTO $item): string
    {
        return sprintf(
            '%s - %s',
            $item->getName(),
            $item->getPrice()
        );
    }

    public function sendRequireChangeCompany(int $chatId, string $companyId)
    {
        $inlineKeyboard = $this->getChangeCompanyApproveKeyboard($companyId);

        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.ChangeCompany'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    public function sendRequireChangeCity(int $chatId)
    {
        $inlineKeyboard = $this->getChangeCityApproveKeyboard();

        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.ChangeCity'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    public function sendChangeCompanySuccessful(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.changeCompanySuccessful'),
        ];
        return $this->sendData($data);
    }

    public function sendChangeCitySuccessful(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.changeCitySuccessful'),
        ];
        return $this->sendData($data);
    }

    private function getChangeCompanyApproveKeyboard(string $companyId): InlineKeyboard
    {
        $items = [];
        $items[] = [[
                'text' => 'Yes',
                'callback_data' => '{"type": "changeCompany", "value": "yes"}',
            ],
            [
                'text' => 'No',
                'callback_data' => '{"type": "changeCompany", "value": "no"}',
            ]];
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

    private function getChangeCityApproveKeyboard(): InlineKeyboard
    {
        $items = [];
        $items[] = [[
            'text' => 'Yes',
            'callback_data' => '{"type": "changeCity", "value": "yes"}',
        ],
            [
                'text' => 'No',
                'callback_data' => '{"type": "changeCity", "value": "no"}',
            ]];
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }
    public function sendRefuseChangeCompany(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('Зміна компанії відмінена'),
        ];
        return $this->sendData($data);
    }
    public function sendRefuseChangeCity(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('The city change request has been declined'),
        ];
        return $this->sendData($data);
    }
}
