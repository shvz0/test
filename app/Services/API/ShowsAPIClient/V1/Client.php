<?php

namespace App\Services\API\ShowsAPIClient\V1;

use App\Services\API\AbstractAPIClient;
use App\Services\API\ShowsAPIClient\ShowsAPIClient;
use Psr\Http\Message\ResponseInterface;

class Client extends AbstractAPIClient implements ShowsAPIClient
{
    private string $baseURL = "https://leadbook.ru/test-task-api";
    private array $httpClientOptions;

    public function __construct()
    {
        parent::__construct();
        $this->httpClientOptions = [
            'headers' => [
                "Authorization" => env("SHOWS_API_TOKEN", "Bearer pmN3TQFQalcOhCwZc18KcPMWZyG2EQHz8al9sCYw"),
            ],
        ];
    }

    public function shows(): array
    {
        $response = $this->httpClient->get($this->baseURL . "/shows", $this->httpClientOptions);
        return $this->formResult($response);
    }

    public function showEvents(int $showID): array
    {
        $response = $this->httpClient->get($this->baseURL . "/shows/$showID/events", $this->httpClientOptions);
        return $this->formResult($response);
    }

    public function showPlaces(int $eventID): array
    {
        $response = $this->httpClient->get($this->baseURL . "/events/$eventID/places", $this->httpClientOptions);
        return $this->formResult($response);
    }

    public function reservePlaces(int $eventID, string $name, array $places): array
    {
        $response = $this->httpClient->post($this->baseURL . "/events/$eventID/reserve", $this->httpClientOptions + [
            'form_params' => [
                'name' => $name,
                'places' => $places
            ]
        ]);

        return $this->formResult($response);
    }

    private function formResult(ResponseInterface $r)
    {
        $content = $r->getBody()->getContents();
        $statusCode = $r->getStatusCode();
        
        $jdec = json_decode($content);

        if (!$jdec) {
            return [
                "statusCode" => $statusCode,
                "data" => [],
                "error" => "Client returned unreadable json",
            ];
        }

        $data = $jdec->response ?? [];
        $error = $jdec->error ?? "";

        return [
            "statusCode" => $statusCode,
            "data" => $data,
            "error" => $error,
        ];
    }
}
