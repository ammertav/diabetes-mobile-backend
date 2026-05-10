<?php

namespace App\Enums;

enum AlertType: string
{
    case HYPO_SEVERE = 'hypo_severe';
    case HYPO_MILD = 'hypo_mild';
    case HYPER_SEVERE = 'hyper_severe';
    case HYPER_MILD = 'hyper_mild';

    public function treshold(): int
    {
        return match ($this) {
            AlertType::HYPO_SEVERE => 54,
            AlertType::HYPO_MILD => 70,
            AlertType::HYPER_SEVERE => 300,
            AlertType::HYPER_MILD => 180,
        };
    }
}
