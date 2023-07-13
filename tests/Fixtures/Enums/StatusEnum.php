<?php

declare(strict_types=1);

namespace Tests\Fixtures\Enums;

enum StatusEnum: int
{
    case new        = 0;
    case success    = 1;
    case waitRefund = 2;
    case refund     = 3;
    case failed     = 4;
}
