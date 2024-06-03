<?php

namespace Fv\Back\Controllers;

use Fv\Back\Models\TypeModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class TypeController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getAllTypes(Request $request, Response $response, $args)
    {
        try {
            $types = TypeModel::all();
            $payload = json_encode($types);
            
            $this->logger->info("GET /types/select", [
                'count' => count($types)
            ]);
            
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Throwable $th) {
            $this->logger->error("GET /types/select", [
                'error' => $th->getMessage()
            ]);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
