<?php

declare(strict_types=1);

namespace App\Enum;

enum DocumentTypeEnum: string
{
    case RAPPORT = 'rapport';
    case PHOTO = 'photo';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case FACTURE = 'facture';
    case CONTRAT = 'contrat';
    case AUTRE = 'autre';
}
