<?php

namespace App\Enums;

enum AlertType: string
{
    case HYPO_SEVERE = 'hypo_severe';
    case HYPO_MILD = 'hypo_mild';
    case HYPER_SEVERE = 'hyper_severe';
    case HYPER_MILD = 'hyper_mild';

    public function threshold(): int
    {
        return match ($this) {
            AlertType::HYPO_SEVERE => 70,
            AlertType::HYPO_MILD => 80,
            AlertType::HYPER_SEVERE => 250,
            AlertType::HYPER_MILD => 180,
        };
    }
}
