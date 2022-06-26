<?php

declare(strict_types=1);

namespace App\Services\Dummy;

use App\Models\Dummy;
use Illuminate\Database\Eloquent\Collection;

class DummyService
{
    /**
     * @param int $count
     * @param bool $unique
     * @return Dummy[]
     */
    public function getDummies(int $count, bool $unique = false): array
    {
        $dummyList = Dummy::all();

        if (!$unique) {
            while (count($dummyList) < $count) {
                foreach ($dummyList as $dummy) {
                    $dummyList []= $dummy;
                }
            }
        }

        return array_slice($dummyList->all(), 0, $count);
    }
}
