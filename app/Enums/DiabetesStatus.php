<?php

namespace App\Enums;

enum DiabetesStatus: string
{
    case T2DM = 't2dm';
    case PREDIABETES = 'prediabetes';
    case HEALTHY = 'healthy';
}
