<?php
/**
 * Description of DishByIdResolver.php
 * @copyright Copyright (c) MISTER.AM, LLC
 * @author    Egor Gerasimchuk <egor@mister.am>
 */

namespace App\Services\Dots\Resolvers;


use App\Services\Dots\Providers\DotsProvider;

class DishByIdResolver
{
    /** @var DotsProvider */
    private $dotsProvider;

    public function __construct(
        DotsProvider $dotsProvider
    )
    {
        $this->dotsProvider = $dotsProvider;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function resolve(string $id, string $companyId): ?array
    {
        $dishes = $this->dotsProvider->getMenuList($companyId);
        //$dishes = $this->sortByNameLength($dishes);

        foreach ($dishes['items'] as $dishCategory) {

            foreach ($dishCategory['items'] as $dish){

                if ($dish['id'] == $id) { return $dish; }
            }

        }
        return null;
    }

    /**
     * @param array $dishes
     * @return array
     */
    private function sortByNameLength(array $dishes): array
    {
        usort($dishes, function ($a, $b) {
            if (mb_strlen($a['name']) === mb_strlen($b['name'])) {
                return 0;
            }
            return mb_strlen($a['name']) < mb_strlen($b['name']) ? 1 : -1;
        });
        return $dishes;
    }

}
