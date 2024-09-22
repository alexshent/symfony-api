<?php

declare(strict_types=1);

namespace Alexshent\ApiClient;

interface Command
{
    public function execute(): void;
}
