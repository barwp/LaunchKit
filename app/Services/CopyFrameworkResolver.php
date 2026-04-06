<?php

namespace App\Services;

class CopyFrameworkResolver
{
    public function options(): array
    {
        return config('copywriting_frameworks.frameworks', []);
    }

    public function recommend(array $input): string
    {
        $goal = strtolower((string) ($input['goal'] ?? ''));
        $awareness = strtolower((string) ($input['awareness_level'] ?? ''));
        $traffic = strtolower((string) ($input['traffic_source'] ?? ''));
        $business = strtolower((string) ($input['business_type'] ?? ''));

        return match (true) {
            str_contains($goal, 'webinar') => 'storybrand',
            str_contains($goal, 'konsultasi') => 'quest',
            str_contains($goal, 'waitlist') => 'slap',
            str_contains($goal, 'trial') => 'fab',
            str_contains($traffic, 'meta') || str_contains($traffic, 'tiktok') => 'pas',
            str_contains($awareness, 'unaware') => 'awareness-ladder',
            str_contains($awareness, 'problem') => 'problem-agitate-solve-cta',
            str_contains($business, 'jasa') => 'promise-proof-plan',
            str_contains($business, 'digital') => 'aida',
            default => 'aida',
        };
    }

    public function resolve(array $input): array
    {
        $slug = $input['copywriting_framework'] ?? 'auto-recommend';
        if ($slug === 'auto-recommend') {
            $slug = $this->recommend($input);
        }

        return collect($this->options())->firstWhere('slug', $slug)
            ?? collect($this->options())->firstWhere('slug', 'aida')
            ?? ['slug' => 'aida', 'name' => 'AIDA', 'description' => 'Attention, Interest, Desire, Action.'];
    }
}
