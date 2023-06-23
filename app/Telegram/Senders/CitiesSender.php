<?php

namespace App\Telegram\Senders;

use App\Services\Dots\DotsService;
use Longman\TelegramBot\Entities\InlineKeyboard;


class CitiesSender extends TelegramSender
{

    /** @var DotsService */
    private $dotsService;

    public function __construct(
        DotsService $dotsService
    )
    {
        $this->dotsService = $dotsService;
    }

    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.pleaseChooseYourCity'),
            'reply_markup' => $this->getCitiesKeyboard()
        ];
        return $this->sendData($data);
    }

    /**
     * @return InlineKeyboard
     */
    private function getCitiesKeyboard(): InlineKeyboard
    {
        $items = $this->getCityItems();
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

    /**
     * @return array
     */
    private function getCityItems(): array
    {
        $cities = $this->dotsService->getCities();

        $items = [];
        foreach ($cities['items'] as $city) {
            $items[] = [[
                'text' => $this->generateCityText($city),
                'callback_data' => '{"type": "city", "id":"'. $city['id'] . '"}',
            ]];
        }
        return $items;
    }

    /**
     * @param array $city
     * @return string
     */
    private function generateCityText(array $city): string
    {
        return $city['name'];
    }

}
