<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\API\ShowsAPIClient\ShowsAPIClient;
use App\Services\API\ShowsAPIClient\V1\Client;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Shows API", version="0.1")
 */

class ShowsAPIController extends Controller
{
    private ShowsAPIClient $apiClient;

    public function __construct(Client $c)
    {
        $this->apiClient = $c;
    }

    /**
     * @OA\Get(
     *      path="/api/shows",
     *      operationId="listShows",
     *      tags={"shows"},
     *      summary="Get list of shows",
     *      description="Return list of shows",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */

    public function shows()
    {
        $shows = $this->apiClient->shows();

        if (!empty($shows["error"])) {
            return response()->json([
                "error" => $shows["error"]
            ], 400);
        }

        return response()->json([
            "data" => $shows["data"]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/shows/{showID}/events",
     *      operationId="listShowEvents",
     *      tags={"shows"},
     *      summary="Get list of shows",
     *      description="Return list of shows",
     *      @OA\Parameter(
     *          name="showID",
     *          description="Show id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */

    public function showEvents(int $showID)
    {
        $events = $this->apiClient->showEvents($showID);

        if (!empty($events["error"])) {
            return response()->json([
                "error" => $events["error"]
            ], 400);
        }

        return response()->json([
            "data" => $events["data"]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/events/{eventID}/places",
     *      operationId="listEventPlaces",
     *      tags={"event"},
     *      summary="Get list of event places",
     *      description="Return list of event places",
     *      @OA\Parameter(
     *          name="eventID",
     *          description="Event id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */

    public function showPlaces(int $eventID)
    {
        $places = $this->apiClient->showPlaces($eventID);

        if (!empty($places["error"])) {
            return response()->json([
                "error" => $places["error"]
            ], 400);
        }

        return response()->json([
            "data" => $places["data"]
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/events/{eventID}/reserve",
     *      operationId="reserveEventPlaces",
     *      tags={"event"},
     *      summary="Reserve places",
     *      description="Reserve places",
     *      @OA\RequestBody(
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="eventID",
     *          description="Event id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=406,
     *          description="Not acceptable. Trying to reserve not available places"
     *      )
     * )
     */

    public function reservePlaces(Request $r, int $eventID)
    {
        $name = $r->get("name", "");
        $places = $r->get("places", []);

        if (empty($name) || empty($places)) {
            return response()->json(['error' => 'Empty name or reserve places'], 400);
        }

        if (!is_array($places) && $places) {
            $places = [$places];
        }

        $reserveResponse = $this->apiClient->reservePlaces($eventID, $name, $places);

        if (!isset($reserveResponse["data"]->success)) {
            return response()->json(['error' => $reserveResponse['error']], 406);
        }

        return response()->json($reserveResponse);
    }
}
