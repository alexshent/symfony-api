<?php

declare(strict_types=1);

namespace Alexshent\ApiClient\User;

use Alexshent\ApiClient\Client;
use Alexshent\ApiClient\Command;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;

readonly class UserCreateCommand implements Command
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
                ->post('/api/users', [
                    'username' => "New user $uniqueId",
                    'password' => '1',
                    'email' => "user$uniqueId@example.com",
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
