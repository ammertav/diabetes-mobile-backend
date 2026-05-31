<?php

namespace App\Enums;

enum CmsContentType: string
{
    case Motivation = 'motivation';
    case Reflection = 'reflection';
    case Education = 'education';
    case Nutrition = 'nutrition';
    case SafetyGuide = 'safety_guide';

    public function label(): string
    {
        return match ($this) {
            self::Motivation => 'Motivation',
            self::Reflection => 'Reflection',
            self::Education => 'Education',
            self::Nutrition => 'Nutrition',
            self::SafetyGuide => 'Safety Guide',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Motivation => 'bg-blue-500',
            self::Reflection => 'bg-purple-500',
            self::Education => 'bg-green-500',
            self::Nutrition => 'bg-orange-500',
            self::SafetyGuide => 'bg-red-500',
        };
    }

    public function textColor(): string
    {
        return match ($this) {
            self::Motivation => 'text-blue-700',
            self::Reflection => 'text-purple-700',
            self::Education => 'text-green-700',
            self::Nutrition => 'text-orange-700',
            self::SafetyGuide => 'text-red-700',
        };
    }
}
