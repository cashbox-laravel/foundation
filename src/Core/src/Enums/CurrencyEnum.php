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

namespace Cashbox\Core\Enums;

use ArchTech\Enums\Options;
use Cashbox\Core\Concerns\Enums\From;

/** @see ISO-4217 */
enum CurrencyEnum: int
{
    use Options;
    use From;

    case AED = 784;
    case AFN = 971;
    case ALL = 8;
    case AMD = 51;
    case ANG = 532;
    case AOA = 973;
    case ARS = 32;
    case AUD = 36;
    case AWG = 533;
    case AZN = 944;
    case BAM = 977;
    case BBD = 52;
    case BDT = 50;
    case BGN = 975;
    case BHD = 48;
    case BIF = 108;
    case BMD = 60;
    case BND = 96;
    case BOB = 68;
    case BOV = 984;
    case BRL = 986;
    case BSD = 44;
    case BTN = 64;
    case BWP = 72;
    case BYN = 933;
    case BZD = 84;
    case CAD = 124;
    case CDF = 976;
    case CHE = 947;
    case CHF = 756;
    case CHW = 948;
    case CLF = 990;
    case CLP = 152;
    case CNY = 156;
    case COP = 170;
    case COU = 970;
    case CRC = 188;
    case CUC = 931;
    case CUP = 192;
    case CVE = 132;
    case CZK = 203;
    case DJF = 262;
    case DKK = 208;
    case DOP = 214;
    case DZD = 12;
    case EGP = 818;
    case ERN = 232;
    case ETB = 230;
    case EUR = 978;
    case FJD = 242;
    case FKP = 238;
    case GBP = 826;
    case GEL = 981;
    case GHS = 936;
    case GIP = 292;
    case GMD = 270;
    case GNF = 324;
    case GTQ = 320;
    case GYD = 328;
    case HKD = 344;
    case HNL = 340;
    case HRK = 191;
    case HTG = 332;
    case HUF = 348;
    case IDR = 360;
    case ILS = 376;
    case INR = 356;
    case IQD = 368;
    case IRR = 364;
    case ISK = 352;
    case JMD = 388;
    case JOD = 400;
    case JPY = 392;
    case KES = 404;
    case KGS = 417;
    case KHR = 116;
    case KMF = 174;
    case KPW = 408;
    case KRW = 410;
    case KWD = 414;
    case KYD = 136;
    case KZT = 398;
    case LAK = 418;
    case LBP = 422;
    case LKR = 144;
    case LRD = 430;
    case LSL = 426;
    case LYD = 434;
    case MAD = 504;
    case MDL = 498;
    case MGA = 969;
    case MKD = 807;
    case MMK = 104;
    case MNT = 496;
    case MOP = 446;
    case MRU = 929;
    case MUR = 480;
    case MVR = 462;
    case MWK = 454;
    case MXN = 484;
    case MXV = 979;
    case MYR = 458;
    case MZN = 943;
    case NAD = 516;
    case NGN = 566;
    case NIO = 558;
    case NOK = 578;
    case NPR = 524;
    case NZD = 554;
    case OMR = 512;
    case PAB = 590;
    case PEN = 604;
    case PGK = 598;
    case PHP = 608;
    case PKR = 586;
    case PLN = 985;
    case PYG = 600;
    case QAR = 634;
    case RON = 946;
    case RSD = 941;
    case RUB = 643;
    case RWF = 646;
    case SAR = 682;
    case SBD = 90;
    case SCR = 690;
    case SDG = 938;
    case SEK = 752;
    case SGD = 702;
    case SHP = 654;
    case SLL = 694;
    case SOS = 706;
    case SRD = 968;
    case SSP = 728;
    case STN = 930;
    case SVC = 222;
    case SYP = 760;
    case SZL = 748;
    case THB = 764;
    case TJS = 972;
    case TMT = 934;
    case TND = 788;
    case TOP = 776;
    case TRY = 949;
    case TTD = 780;
    case TWD = 901;
    case TZS = 834;
    case UAH = 980;
    case UGX = 800;
    case USD = 840;
    case USN = 997;
    case USS = 998;
    case UYI = 940;
    case UYU = 858;
    case UYW = 927;
    case UZS = 860;
    case VES = 928;
    case VND = 704;
    case VUV = 548;
    case WST = 882;
    case XAF = 950;
    case XAG = 961;
    case XAU = 959;
    case XBA = 955;
    case XBB = 956;
    case XBC = 957;
    case XBD = 958;
    case XCD = 951;
    case XDR = 960;
    case XOF = 952;
    case XPD = 964;
    case XPF = 953;
    case XPT = 962;
    case XSU = 994;
    case XTS = 963;
    case XUA = 965;
    case XXX = 999;
    case YER = 886;
    case ZAR = 710;
    case ZMW = 967;
    case ZWL = 932;
}
