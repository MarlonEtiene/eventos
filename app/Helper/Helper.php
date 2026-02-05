<?php

namespace App\Helper;

use Illuminate\Support\Str;

class Helper
{
    public function toSnakeCase(array $array): array
    {
        return collect($array)->mapWithKeys(function ($value, $key) {
            $snakeKey = Str::snake($key);

            if ($value === null) {
                return [$snakeKey => null];
            }

            if (is_string($value) && ($key !== 'email')) {
                return [$snakeKey => Str::upper($value)];
            }

            return [$snakeKey => $value];
        })->toArray();
    }
}
