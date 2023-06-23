<?php

namespace App\Telegram\Senders;

use App\Services\Dots\DotsService;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;

class CompaniesSender extends TelegramSender
{
    /** @var DotsService */
    private $dotsService;

    public function __construct(
        DotsService $dotsService
    )
    {
        $this->dotsService = $dotsService;
    }

    public function send(int $chatId, string $cityId)
    {
        $inlineKeyboard = $this->getCompaniesKeyboard($cityId);
        if(!$inlineKeyboard){
            $data = [
                'chat_id' => $chatId,
                'text' => trans('bots.companiesNotFound'),
            ];
            return $this->sendData($data);
        }
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.pleaseChooseCompany'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    /**
     * @return InlineKeyboard
     */
    private function getCompaniesKeyboard(string $cityId): ?InlineKeyboard
    {
        $items = $this->getCompanyItems($cityId);
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
    private function getCompanyItems(string $cityId): ?array
    {
        $companies = $this->dotsService->getCompanies($cityId);

        if(!array_key_exists('items', $companies)){
            return null;
        }

        $items = [];
        foreach ($companies['items'] as $company) {
            $items[] = [[
                'text' => $this->generateCompanyText($company),
                'callback_data' => '{"type": "company", "id":"'. $company['id'] . '"}',
            ]];
        }
        return $items;
    }
    private function generateCompanyText(array $company): string
    {
        return $company['name'] . '(' . $company['schedule'][date("N")-1]['start'].' - '
            . $company['schedule'][date("N") - 1]['end'] . ')';
    }
}
