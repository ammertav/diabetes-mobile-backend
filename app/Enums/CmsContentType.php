<?php

namespace App\Enums;

enum CmsContentType: string
{
    case Motivation = 'motivation';
    case Reflection = 'reflection';
    case Education = 'education';
    case Nutrition = 'nutrition';
    case SafetyGuide = 'safety_guide';
}
