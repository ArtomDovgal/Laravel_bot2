<?php

namespace App\Telegram\Senders;

use App\Http\Controllers\Bots\TelegramController;
use App\Services\Cart\CartService;
use App\Services\Dots\DotsService;
use App\Services\Users\UsersService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class DishesSender extends TelegramSender
{
    /** @var DotsService */
    protected $dotsService;

    /** @var CartService */
    private $cartService;
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    public function __construct(
        DotsService $dotsService,
        CartService $cartService,
        TelegramMessageCartResolver $telegramMessageCartResolver,
    )
    {
        $this->dotsService = $dotsService;
        $this->cartService = $cartService;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
    }

    public function send(CallbackQuery $callbackQuery){
        $telegram_id = $callbackQuery->getMessage()->getChat()->getId();
        $message_id = $callbackQuery->getMessage()->getMessageId();
        $message = $callbackQuery->getMessage();

        $cart = $this->telegramMessageCartResolver->resolve($message);
        $companyId = $cart->getCompanyId();

        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $categoryId = $data['id'];
        $replyMarkup = $this->getDishesKeyboard($companyId, $categoryId);
        $data = $this->getReplyMarkupData($telegram_id, $message_id, $replyMarkup);
        return Request::editMessageReplyMarkup($data);
    }

    private function getReplyMarkupData(int $telegram_id, int $message_id, InlineKeyboard $replyMarkup): array
    {
        $data = [
            'chat_id' => $telegram_id,
            'message_id'   => $message_id,
            'reply_markup' => $replyMarkup
        ];
        return $data;
    }

    /**
     * @return InlineKeyboard
     */
    private function getDishesKeyboard(string $companyId, string $categoryId): InlineKeyboard
    {
        $items = $this->getDishItems($companyId, $categoryId);
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

    /**
     * @param string $dishCategoryId
     * @return array
     */
    private function getDishItems(string $companyId, string $categoryId): array
    {
        $dishes = $this->dotsService->getDishes($companyId);
        $items = [];
        foreach ($dishes['items'] as $dishCategory) {
            $items[] = [[
                'text' => $this->generateDishCategoryText($dishCategory),
                'callback_data' => '{"type":"cat","id":"' . $dishCategory['id'] . '"}',
            ]];
            if($dishCategory['items'] == null){
                continue;
            }
            if($categoryId == $dishCategory['id'] ){
                foreach ($dishCategory['items'] as $dish) {
                    $items[] = [[
                        'text' => $this->generateDishText($dish),
                        'callback_data' => '{"type":"dish","id":"'. $dish['id'] .'"}',
                    ]];
                }
            }
        }
        return $items;
    }

    /**
     * @param array $dish
     * @return string
     */
    private function generateDishText(array $dish): string
    {
        return sprintf('%s - %s%s', $dish['name'], $dish['price'], 'UAH');
    }

    /**
     * @param array $dish
     * @return string
     */
    protected function generateDishCategoryText(array $dish): string
    {
        return $dish['name'];
    }
}
