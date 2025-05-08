<?php

namespace NFService\Services;

use GuzzleHttp\Psr7\Utils;
use NFService\Dominio;

class Notas
{
    protected Dominio $dominio;

    public function __construct(Dominio $dominio)
    {
        $this->dominio = $dominio;
    }


    public function envioNota(string $pathNota, bool $boxeFile = false, ?string $pathComplemento = null )
    {
        $form = [
            [
                'name' => 'file[]',
                'contents' => Utils::tryFopen($pathNota, 'r'),
                'filename' => $pathNota,
                'headers'  => [ 'Content-Type' => 'application/xml']
            ],
            [
                'name' => 'query',
                'contents' => '{"boxe/File": ' . $boxeFile . '}',
                'headers'  => [ 'Content-Type' => 'application/json']
            ]
        ];

        if (!empty($pathComplemento)) {
            $formComplemento = [
                [
                    'name' => 'fileComplement[]',
                    'contents' => Utils::tryFopen($pathComplemento, 'r'),
                    'filename' => $pathComplemento,
                    'headers'  => [ 'Content-Type' => 'application/xml']
                ]
            ];

            $form = array_merge($form, $formComplemento);
        }

        return $this->dominio->client->requisicao("/invoice/v3/batches", 'POST', $form);
    }

    public function consultaEnvioNota(string $id)
    {
        return $this->dominio->client->requisicao("/invoice/v3/batches/" . $id, 'GET');
    }
}
