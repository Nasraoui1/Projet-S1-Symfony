<?php

declare(strict_types=1);

namespace App\Enum;

enum PaiementTypeEnum: string
{
    case CASH = 'cash';
    case CHEQUE = 'cheque';
    case VIREMENT = 'virement';
    case CARTE_BANCAIRE = 'carte_bancaire';
    case CRYPTO_MONNAIE = 'crypto_monnaie';
    case AUTRE = 'autre';
}
