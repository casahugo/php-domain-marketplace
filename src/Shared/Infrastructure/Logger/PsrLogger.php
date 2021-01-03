<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Logger;

use App\Shared\Domain\Logger\Logger;
use Psr\Log\LoggerInterface as PrsLogger;

final class PsrLogger implements Logger
{
    public function __construct(private PrsLogger $psrLog)
    {
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->psrLog->emergency($message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->psrLog->alert($message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->psrLog->critical($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->psrLog->error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->psrLog->warning($message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->psrLog->notice($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->psrLog->info($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->psrLog->debug($message, $context);
    }

    public function log(int $level, string $message, array $context = []): void
    {
        $this->psrLog->log($level, $message, $context);
    }
}
