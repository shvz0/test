<?php

namespace App\Services\API\ShowsAPIClient;

interface ShowsAPIClient
{
    public function shows(): array;

    public function showEvents(int $showID): array;

    public function showPlaces(int $eventID): array;

    public function reservePlaces(int $eventID, string $name, array $places): array;
}
