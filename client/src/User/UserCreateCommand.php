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
        private string  $username,
        private string $email,
        private string $password,
    ) {
    }

    public function execute(): void
    {
        try {
            $response = $this->client->getRequest()
                ->post('/api/users', [
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $this->password,
                ])
                ->call();

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
