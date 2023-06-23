<?php

namespace App\Telegram\Senders;

use App\Services\Dots\DotsService;
use Longman\TelegramBot\Entities\InlineKeyboard;

class AddressCompanySender extends TelegramSender
{
    /** @var DotsService */
    private $dotsService;

    public function __construct(
        DotsService $dotsService
    )
    {
        $this->dotsService = $dotsService;
    }

    public function send(int $chatId, string $companyId)
    {
        $inlineKeyboard = $this->getAddressesKeyboard($companyId);
        if(!$inlineKeyboard){
            $data = [
                'chat_id' => $chatId,
                'text' => trans('bots.addressesNotFound'),
            ];
            return $this->sendData($data);
        }
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.pleaseChooseAddress'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    /**
     * @return InlineKeyboard
     */
    private function getAddressesKeyboard(string $companyId): ?InlineKeyboard
    {
        $items = $this->getAddressItems($companyId);
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
    private function getAddressItems(string $companyId): ?array
    {
        $companies = $this->dotsService->getCompanyInfo($companyId);

        if(!array_key_exists('addresses', $companies)){
            return null;
        }
        $addresses = $companies['addresses'];

        $items = [];
        foreach ($addresses  as $address) {
            $items[] = [[
                'text' => $this->generateAddressText($address),
                'callback_data' => '{"type": "address", "id":"'. $address['id'] . '"}',
            ]];
        }
        return $items;
    }
    private function generateAddressText(array $address): string
    {
        return $address['title'];
    }
}