<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;

class OutputHelper
{
    public function getLogString(string $commandName, string $message): string
    {
        return sprintf(
            '%s;%s;%s',
            (new DateTime())->format('H:i:s.u'),
            $commandName,
            $message
        );
    }
}
