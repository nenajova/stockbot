<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Entity\Stock;
use App\Factory\ReportFactory;

class StockController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        $startDate = \DateTime::createFromFormat('m/d/Y', $data['startDate']);
        $endDate = \DateTime::createFromFormat('m/d/Y', $data['endDate']);

        if (!$startDate || !$endDate) {
            return $this->json(['error' => 'Invalid date format. Use m/d/Y.'], 400);
        }

        $companyRepository = $this->entityManager->getRepository(Company::class);
        $company =  $companyRepository->findOneBy(['stockName' => $name]);

        $stockRepository = $this->entityManager->getRepository(Stock::class);
        $stocks = $stockRepository->findByCompanyAndDates(
            $company->getId(),
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        $report = ReportFactory::create($stocks);

        $mainPeriod = $report->getMainPeriod();
        $previousPeriod = $report->getPreviousPeriod();
        $nextPeriod = $report->getNextPeriod();

        $response = [
            'companyName'=> $company->getName(),
            'mainPeriod' => [
                'startDate' => $mainPeriod->getStartDate()->format('m.d.Y'),
                'endDate' => $mainPeriod->getEndDate()->format('m.d.Y'),
                'daysCount' => $mainPeriod->getDaysCount(),
                'bestToBuy' => [
                    'date' => $mainPeriod->getBestToBuy()->getDate()->format('m.d.Y'),
                    'price' => $mainPeriod->getBestToBuy()->getPrice()
                ],
                'bestToSell' => [
                    'date' => $mainPeriod->getBestToSell()->getDate()->format('m.d.Y'),
                    'price' => $mainPeriod->getBestToSell()->getPrice()
                ],
                'profit' => $mainPeriod->getProfit(),
                'trendChanged' => $mainPeriod->getTrendChangeCount(),
                'periodProfit' => $mainPeriod->getPeriodProfit()->getProfit(),
                'transactionCount' => $mainPeriod->getPeriodProfit()->getTransactionCounter(),
                'transactions' => $mainPeriod->getPeriodProfit()->getTransactionToString(),

            ],
            'previousPeriod' => [
                'startDate' => $previousPeriod->getStartDate()->format('m.d.Y'),
                'endDate' => $previousPeriod->getEndDate()->format('m.d.Y'),
                'daysCount' => $previousPeriod->getDaysCount(),
                'bestToBuy' => [
                    'date' => $previousPeriod->getBestToBuy()->getDate()->format('m.d.Y'),
                    'price' => $previousPeriod->getBestToBuy()->getPrice()
                ],
                'bestToSell' => [
                    'date' => $previousPeriod->getBestToSell()->getDate()->format('m.d.Y'),
                    'price' => $previousPeriod->getBestToSell()->getPrice()
                ],
                'profit' => $previousPeriod->getProfit(),
                'trendChanged' => $previousPeriod->getTrendChangeCount(),
                'periodProfit' => $previousPeriod->getPeriodProfit()->getProfit(),
                'transactionCount' => $previousPeriod->getPeriodProfit()->getTransactionCounter(),
                'transactions' => $previousPeriod->getPeriodProfit()->getTransactionToString(),
            ],
            'nextPeriod' => [
                'startDate' => $nextPeriod->getStartDate()->format('m.d.Y'),
                'endDate' => $nextPeriod->getEndDate()->format('m.d.Y'),
                'daysCount' => $nextPeriod->getDaysCount(),
                'bestToBuy' => [
                    'date' => $nextPeriod->getBestToBuy()->getDate()->format('m.d.Y'),
                    'price' => $nextPeriod->getBestToBuy()->getPrice()
                ],
                'bestToSell' => [
                    'date' => $nextPeriod->getBestToSell()->getDate()->format('m.d.Y'),
                    'price' => $nextPeriod->getBestToSell()->getPrice()
                ],
                'profit' => $nextPeriod->getProfit(),
                'trendChanged' => $nextPeriod->getTrendChangeCount(),
                'periodProfit' => $nextPeriod->getPeriodProfit()->getProfit(),
                'transactionCount' => $nextPeriod->getPeriodProfit()->getTransactionCounter(),
                'transactions' => $nextPeriod->getPeriodProfit()->getTransactionToString(),
            ]
        ];

        return $this->json($response);
    }
}
