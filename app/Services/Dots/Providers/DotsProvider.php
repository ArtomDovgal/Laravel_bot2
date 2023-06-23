<?php

namespace App\Services\Dots\Providers;

use App\Services\Http\HttpClient;
use Longman\TelegramBot\Request;

class DotsProvider extends HttpClient
{
    const ORDERS_URL = '/api/v2/orders';
    const CITIES_URL = '/api/v2/cities';
    const COMPANIES_URL = '/api/v2/cities/%s/companies';
    const ITEMS_CATEGORIES_URL_TEMPLATE = '/api/v2/companies/%s/items-by-categories';
    const COMPANY_INFO_URL_TEMPLATE = '/api/v2/companies/%s';
    const ONLINE_PAYMENT_URL = '/api/v2/orders/%s/online-payment-data';

    public function getServiceHost()
    {
        return config('services.dots.host');
    }

    public function getParams(): array
    {
        return [
            'headers' => [
                'Api-Auth-Token' => config('services.dots.api_auth_token'),
                'Api-Token' => config('services.dots.api_token'),
                'Api-Account-Token' => config('services.dots.api_account_token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'v' => '2.0.0',
            ],
            'json' => true,
        ];
    }

    public function makeOrder(array $data): array
    {
        $orderData['orderFields'] = $data;
        return $this->post($this->generateOrderUrl(), $orderData, $this->getParams());
    }

    public function getCityList(): array
    {
        return $this->get($this->generateCityUrl(), $this->getParams()) ?: [];
    }

    public function getCompanyList(string $cityId): array
    {
        return $this->get($this->generateCompanyUrl($cityId), $this->getParams()) ?: [];
    }
    public function getAddressList(string $companyId){
        return $this->get($this->generateCompanyInfoUrl($companyId), $this->getParams()) ?: [];
    }

    public function getCompanyInfo(string $companyId): array
    {
        return $this->get($this->generateCompanyInfoUrl($companyId), $this->getParams()) ?: [];
    }

    public function getMenuList(string $companyId): array
    {
        return $this->get($this->generateMenuUrl($companyId), $this->getParams()) ?: [];
    }

    private function generateCityUrl(): string
    {
        return config('services.dots.host') . self::CITIES_URL;
    }

    private function generateOrderUrl(): string
    {
        return config('services.dots.host') . self::ORDERS_URL;
    }
    private function generateCompanyUrl(string $cityId): string
    {
        return config('services.dots.host') . sprintf(self::COMPANIES_URL, $cityId);
    }

//--------------------
    public function getOnlinePaymentData(string $orderID): array
    {
        return $this->get($this->generateOnlinePaymentUrl($orderID), $this->getParams()) ?: [];
    }



    private function generateCompanyInfoUrl(string $companyId): string
    {
        return config('services.dots.host') . sprintf(self::COMPANY_INFO_URL_TEMPLATE, $companyId);
    }

    private function generateMenuUrl(string $companyId): string
    {
        return config('services.dots.host') . sprintf(self::ITEMS_CATEGORIES_URL_TEMPLATE, $companyId);
    }
//------------
    private function generateOnlinePaymentUrl(string $orderId): string
    {
        return config('services.dots.host') . sprintf(self::ONLINE_PAYMENT_URL, $orderId);
    }
}
