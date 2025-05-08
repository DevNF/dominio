<?php

namespace NFService\Services;

use GuzzleHttp\Exception\GuzzleException;
use NFService\Dominio;

class Auth
{
    protected Dominio $dominio;

    public function __construct(Dominio $dominio)
    {
        $this->dominio = $dominio;
    }

    public function gerarToken(): string | array | null
    {
        return $this->dominio->client->requisicaoToken();
    }

    public function gerarKeyIntegracao(): string | array | null
    {
        return $this->dominio->client->requisicao('/integration/v1/activation/enable', 'POST');
    }

    public function confirmarCliente()
    {
        return $this->dominio->client->requisicao('/integration/v1/activation/info', 'GET');
    }
}
