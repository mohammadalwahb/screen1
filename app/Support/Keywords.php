<?php

namespace App\Support;

class Keywords
{
    public static function fromString(?string $keywords): array
    {
        if (! $keywords) {
            return [];
        }

        $parts = array_map(static fn (string $value): string => trim($value), explode(',', $keywords));
        $parts = array_filter($parts, static fn (string $value): bool => $value !== '');

        return array_values(array_unique($parts));
    }
}
