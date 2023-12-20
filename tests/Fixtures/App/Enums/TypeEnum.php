<?php

/**
 * This file is part of the "cashbox/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://cashbox.city
 */

declare(strict_types=1);

namespace Tests\Fixtures\App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string Cash()
 * @method static string Outside()
 * @method static string TinkoffCredit()
 * @method static string TinkoffOnline()
 * @method static string TinkoffQrCode()
 * @method static string SberQrCode()
 * @method static string TemplateAuth()
 * @method static string TemplateDriver()
 */
enum TypeEnum: string
{
    use InvokableCases;

    case Cash           = 'cash';
    case Outside        = 'outside';
    case TinkoffCredit  = 'tinkoff_credit';
    case TinkoffOnline  = 'tinkoff_online';
    case TinkoffQrCode  = 'tinkoff_qr';
    case SberQrCode     = 'sber_qr';
    case TemplateAuth   = 'template_auth';
    case TemplateDriver = 'template_driver';
}
