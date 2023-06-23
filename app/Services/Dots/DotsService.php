<?php

namespace App\Services\Dots;

use App\Services\Dots\Providers\DotsProvider;
use App\Services\Dots\Resolvers\DishByIdResolver;

class DotsService
{

    /** @var DotsProvider */
    private $dotsProvider;
    /** @var DishByIdResolver */
    private $dishByIdResolver;

    public function __construct(
        DotsProvider $dotsProvider,
        DishByIdResolver $dishByIdResolver
    ) {
        $this->dotsProvider = $dotsProvider;
        $this->dishByIdResolver = $dishByIdResolver;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function findDishById(string $id, string $companyId): ?array
    {
        return $this->dishByIdResolver->resolve($id, $companyId);
    }

    /**
     * @return array
     */
    public function getDishes(string $companyId): array
    {
        return $this->dotsProvider->getMenuList($companyId);
    }

    /**
     * @return array
     */
    public function getCities(): array
    {

        return $this->dotsProvider->getCityList();
    }

    /**
     * @return array
     */
    public function getCompanies(string $cityId): array
    {
        return $this->dotsProvider->getCompanyList($cityId);
    }

    /**
     * @return array
     */
    public function getCompanyInfo(string $companyId): array
    {
        return $this->dotsProvider->getCompanyInfo($companyId);
    }

    /**
     * @param array $data
     * @return array
     */
    public function makeOrder(array $data): array
    {
        return $this->dotsProvider->makeOrder($data);
    }

    /**
     * @return array
     */
    public function getAddresses(string $companyId): array
    {
        return $this->dotsProvider->getAddressList($companyId);
    }
}
