<?php

namespace Fv\Back\Controllers;

use Fv\Back\Models\HabitatModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class HabitatController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getAllHabitats(Request $request, Response $response, $args)
    {
        try {
            $habitats = HabitatModel::all();
            $payload = json_encode($habitats);
            
            $this->logger->info("GET /habitats/select", [
                'count' => count($habitats)
            ]);
            
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Throwable $th) {
            $this->logger->error("GET /habitats/select", [
                'error' => $th->getMessage()
            ]);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
