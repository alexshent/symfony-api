<?php

declare(strict_types=1);

namespace Alexshent\ApiClient\Group;

use Alexshent\ApiClient\Client;
use Alexshent\ApiClient\Command;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;

readonly class GroupCreateCommand implements Command
{
    public function __construct(
        private Client $client,
    ) {
    }

    public function execute(): void
    {
        try {
            $uniqueId = uniqid();

            $response = $this->client->getRequest()
                ->post('/api/groups', [
                    'name' => "New group $uniqueId",
                ])
                ->call()
            ;

            $status = $response->getStatus();
            if (200 === $status) {
                $body = $response->getBody();
                $this->client->setLastId($body['id']);
                print_r($body);
            }
        } catch (ApiRequestException|ApiResponseException $e) {
            $message = $e->getMessage();
            print_r($message);
        }
    }
}
