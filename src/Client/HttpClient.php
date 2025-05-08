<?php

namespace NFService\Client;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NFService\Dominio;
use NFService\Options\EnvironmentUrls;
use stdClass;


class HttpClient
{
    private bool $debug;
    private string $base_url;
    private Dominio $dominio;
    private Client $client;

    public function __construct(Dominio $dominio, bool $debug = false)
    {
        $this->dominio = $dominio;
        $this->base_url = EnvironmentUrls::production_url;
        $this->debug = $debug;
        $this->client = new Client([ 'http_errors' => false ]);
    }

    public function requisicao(string $uri, string $metodo, ?array $corpo = null, ?array $params = null ): string | GuzzleException | array | stdClass | null
    {
        $response = $this->client->request($metodo, EnvironmentUrls::production_url . $uri, [
            'debug' => $this->debug,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->dominio->getToken(),
                'x-integration-key' => $this->dominio->getIntegrationKey(),
            ],
            'multipart' => $corpo
        ]);


        return json_decode($response->getBody(), true);
    }

    public function requisicaoToken()
    {
        $response = $this->client->request('POST', EnvironmentUrls::auth_url, [
            'debug' => $this->debug,
            'auth' => [
                $this->dominio->getClientId(),
                $this->dominio->getClientSecret()
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => 'did=s%3Av0%3A145b8a90-ea57-11eb-ae8a-877f15a4a518.QhUcTCGsMP28yWAB%2BYsUUZ5Gw4Srxf%2F0IDRkKPUQQHs; did_compat=s%3Av0%3A145b8a90-ea57-11eb-ae8a-877f15a4a518.QhUcTCGsMP28yWAB%2BYsUUZ5Gw4Srxf%2F0IDRkKPUQQHs',
            ],
            'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->dominio->getClientId(),
                    'client_secret' => $this->dominio->getClientSecret(),
                    'audience' => $this->dominio->getAudience(),
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
