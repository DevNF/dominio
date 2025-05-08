<?php

namespace NFService;

use NFService\Client\HttpClient;
use NFService\Services\Notas;
use NFService\Services\Auth;

class Dominio
{
    private string $client_id;
    private string $client_secret;
    private string $integration_key;
    private string $token;
    private string $audience;
    public HttpClient $client;

    public function __construct(
        string $client_id,
        string $client_secret,
        string $integration_key,
        string $token,
        string $audience,
        bool $debug = false
    ) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->integration_key = $integration_key;
        $this->token = $token;
        $this->audience = $audience;
        $this->client = new HttpClient($this, $debug);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    public function getIntegrationKey(): string
    {
        return $this->integration_key;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function auth(): Auth
    {
        return new Auth($this);
    }

    public function notas(): Notas
    {
        return new Notas($this);
    }
}
