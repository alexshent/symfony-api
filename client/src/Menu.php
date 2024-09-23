<?php

declare(strict_types=1);

namespace Alexshent\ApiClient;

class Menu
{
    /** @var array <string, callable> */
    private array $items = [];

    public function __construct()
    {
        $this->addItem('exit', function () {
            exit(0);
        });
    }

    public function addItem(string $name, callable $callback): void
    {
        $this->items[$name] = $callback;
    }

    public function printItems(): void
    {
        foreach ($this->items as $name => $callback) {
            echo "$name\n";
        }
    }

    public function readLine(): string
    {
        $line = readline('>> ');
        if (is_string($line)) {
            return $line;
        }

        return '';
    }

    public function start(): void
    {
        // @phpstan-ignore-next-line
        while (true) {
            $this->printItems();
            $command = $this->readLine();
            if (key_exists($command, $this->items)) {
                $this->items[$command]();
                echo "---------------------------------------------------------------\n\n";
            } else {
                echo "unknown command\n\n";
            }
        }
    }
}
