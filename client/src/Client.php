<?php

declare(strict_types=1);

namespace Alexshent\ApiClient;

use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;
use Piggly\ApiClient\Request;

class Client
{
    private Configuration $config;
    private Request $request;
    private int $lastId;

    public function __construct(string $host)
    {
        $this->config = new Configuration();
        $this->config->host($host);
        $this->config->headers()->add('Content-Type', 'application/json');
        $this->config->debug(true);

        $this->request = new Request($this->config);
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getLastId(): int
    {
        return $this->lastId;
    }

    public function setLastId(int $lastId): void
    {
        $this->lastId = $lastId;
    }

    public function login(): bool
    {
        try {
            $response = $this->request
                ->post(
                    '/api/login_check',
                    [
                        'username' => 'apiuser',
                        'password' => '1',
                    ],
                )
                ->call()
            ;

            $status = $response->getStatus();
            if (200 === $status) {
                $body = $response->getBody();
                $token = $body['token'];
                $this->config->apiKey('jwt', $token, 'Bearer');
                $this->request->authorization('jwt');

                return true;
            }
        } catch (ApiRequestException|ApiResponseException $e) {
            $message = $e->getMessage();
            print_r($message);
        }

        return false;
    }
}
