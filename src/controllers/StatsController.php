<?php

namespace Fv\Back\Controllers;

use Fv\Back\Models\StatsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class StatsController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getAllStats(Request $request, Response $response, $args)
    {
        try {
            $stats = StatsModel::all();
            $payload = json_encode($stats);
            
            $this->logger->info("GET /stats/select", [
                'count' => count($stats)
            ]);
            
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Throwable $th) {
            $this->logger->error("GET /stats/select", [
                'error' => $th->getMessage()
            ]);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
