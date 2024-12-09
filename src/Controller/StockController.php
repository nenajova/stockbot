<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @Route("/stock", name="app_stock")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/StockController.php',
        ]);
    }
    

    /**
     * @Route("/api/stock", name="api_stock", methods={"POST"})
     */
    public function stock(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['startDate'], $data['endDate'])) {
            return $this->json(['error' => 'Missing required parameters'], 400);
        }

        $name = $data['name'];
        $startDate = \DateTime::createFromFormat('d/m/Y', $data['startDate']);
        $endDate = \DateTime::createFromFormat('d/m/Y', $data['endDate']);

        if (!$startDate || !$endDate) {
            return $this->json(['error' => 'Invalid date format. Use d/m/Y.'], 400);
        }
        
        $response = [
            'name' => $name,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ];

        return $this->json($response);
    }
}
