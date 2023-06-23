<?php

namespace App\Telegram\Senders;

use App\Services\Dots\DotsService;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class DishCategoriesSender extends TelegramSender
{

    /** @var DotsService */
    protected $dotsService;

    public function __construct(
        DotsService $dotsService
    )
    {
        $this->dotsService = $dotsService;
    }

    public function send(int $chatId, string $companyId)
    {
        $inlineKeyboard = $this->getDishesKeyboard($companyId);
        if(!$inlineKeyboard){
            $data = [
                'chat_id' => $chatId,
                'text' => trans('bots.dishesNotFound'),
            ];
            return $this->sendData($data);
        }
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.pleaseChooseYourDish'),
            'reply_markup' => $inlineKeyboard
        ];
        return $this->sendData($data);
    }

    /**
     * @return InlineKeyboard
     */
    private function getDishesKeyboard(string $companyId): ?InlineKeyboard
    {
        $items = $this->getDishItems($companyId);

        if(!$items){
           return null;
        }
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

    /**
     * @return array
     */
    private function getDishItems(string $companyId): ?array
    {
        $dishes = $this->dotsService->getDishes($companyId);

        if(!array_key_exists('items', $dishes)){
            return null;
        }

        $items = [];
        foreach ($dishes['items'] as $dishCategory) {
            $items[] = [[
                'text' => $this->generateDishCategoryText($dishCategory),
                'callback_data' => '{"type":"cat","id":"' . $dishCategory['id'] . '"}',
                'parse_mode' => 'HTML',
            ]];
        }
        return $items;
    }

    /**
     * @param array $dish
     * @return string
     */
    protected function generateDishCategoryText(array $dish): string
    {
        return $dish['name'];
    }
//    protected function sendSelectedCity(string $companyId,string $chatId): string
//    {
//        $data = [
//            'chat_id' => $chatId,
//            'text' => $this
//        ];
//        return $this->sendData($data);
//    }
}
