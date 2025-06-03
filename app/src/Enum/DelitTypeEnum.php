<?php

declare(strict_types=1);

namespace App\Enum;

enum DelitTypeEnum: string
{
    case CORRUPTION = 'corruption';
    case FRAUDE = 'fraude';
    case VOLE = 'vole';
}
