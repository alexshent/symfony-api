<?php

declare(strict_types=1);

namespace Alexshent\ApiClient\User;

use Alexshent\ApiClient\Client;
use Alexshent\ApiClient\Command;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;

readonly class UserUpdateCommand implements Command
{
    public function __construct(
        private Client  $client,
        private int     $id,
        private ?string  $username = null,
        private ?string $email = null,
    ) {
    }

    public function execute(): void
    {
        $body = [];
        if (null != $this->username) {
            $body['username'] = $this->username;
        }
        if (null != $this->email) {
            $body['email'] = $this->email;
        }

        try {
            $response = $this->client->getRequest()
                ->put("/api/users/$this->id", $body)
                ->call();

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
