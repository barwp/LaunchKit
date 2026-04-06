<?php

namespace App\Services;

class AudienceResolver
{
    public function options(): array
    {
        return config('audience_options.options', []);
    }

    public function normalize(array|string|null $audience): array
    {
        if (is_array($audience)) {
            return array_values(array_filter(array_map('trim', $audience)));
        }

        if (! is_string($audience) || trim($audience) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $audience))));
    }

    public function summary(array|string|null $audience): string
    {
        $items = $this->normalize($audience);

        return $items === [] ? 'Target market umum' : implode(', ', array_slice($items, 0, 3));
    }
}
