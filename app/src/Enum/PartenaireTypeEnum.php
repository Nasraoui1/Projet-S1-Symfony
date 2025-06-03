<?php

declare(strict_types=1);

namespace App\Enum;

enum PartenaireTypeEnum: string
{
    case JUGE = 'juge';
    case MAGISTRAT = 'magistrat';
    case PROCUREUR = 'procureur';
    case AVOCAT = 'avocat';
    case POLICE = 'police';
    case GENDARME = 'gendame';
    case MILITAIRE = 'militaire';
    case AUTRE = 'autre';
    case INCONNU = 'inconnu';
}
