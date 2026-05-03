<?php

namespace App\Enums;

enum FgbContextTag: string
{
    case BEFORE_MEAL = 'before_meal';
    case AFTER_MEAL = 'after_meal';
    case END_OF_FAST = 'end_of_fast';
    case MORNING = 'morning';
    case OTHER = 'other';
}
