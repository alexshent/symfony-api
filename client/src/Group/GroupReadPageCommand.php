<?php

declare(strict_types=1);

namespace Alexshent\ApiClient\Group;

use Alexshent\ApiClient\Client;
use Alexshent\ApiClient\Command;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;

readonly class GroupReadPageCommand implements Command
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function execute(): void
    {
        $page = 1;
        $itemsPerPage = 10;

        try {
            $response = $this->client->getRequest()
                ->get("/api/groups/page/{$page}/{$itemsPerPage}")
                ->call()
            ;
            $status = $response->getStatus();
            if (200 === $status) {
                $body = $response->getBody();
                print_r($body);
            }
        } catch (ApiRequestException|ApiResponseException $e) {
            $message = $e->getMessage();
            print_r($message);
        }
    }
}
