<?php

namespace App\Controller;

use App\Repository\CryptoCurrencyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CryptoCurrencyApiController extends AbstractController
{
    private $cryptoCurrencyRepository;

    public function __construct(CryptoCurrencyRepository $cryptoCurrencyRepository)
    {
        $this->cryptoCurrencyRepository = $cryptoCurrencyRepository;
    }
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Prikupi podatke za početnu stranicu
        $cryptos = $this->cryptoCurrencyRepository->findAll();

        // Renderuj šablon sa podacima
        return $this->render('crypto/index.html.twig', [
            'cryptos' => $cryptos,
        ]);
    }


    #[Route('/api/cryptos', name: 'api_cryptos', methods: ['GET'])]
    public function getCryptos(CryptoCurrencyRepository $cryptoRepo): JsonResponse
    {
        $cryptos = $cryptoRepo->findAll();
        $data = [];

        foreach ($cryptos as $crypto) {
            $data[] = [
                'id' => $crypto->getId(),
                'name' => $crypto->getName(),
                'symbol' => $crypto->getSymbol(),
                'currentPrice' => $crypto->getCurrentPrice(),
                'totalVolume' => $crypto->getTotalVolume(),
                'ath' => $crypto->getAth(),
                'athDate' => $crypto->getAthDate()->format('Y-m-d H:i:s'),
                'atl' => $crypto->getAtl(),
                'atlDate' => $crypto->getAtlDate()->format('Y-m-d H:i:s'),
                'updatedAt' => $crypto->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/crypto-currency/{symbol}', name: 'api_crypto_currency_by_symbol', methods: ['GET'])]
    public function getCryptoBySymbol(string $symbol): JsonResponse
    {
        $crypto = $this->cryptoCurrencyRepository->findOneBy(['symbol' => $symbol]);

        if (!$crypto) {
            return new JsonResponse(['error' => 'Cryptocurrency not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $crypto->getId(),
            'name' => $crypto->getName(),
            'symbol' => $crypto->getSymbol(),
            'currentPrice' => $crypto->getCurrentPrice(),
            'totalVolume' => $crypto->getTotalVolume(),
            'ath' => $crypto->getAth(),
            'athDate' => $crypto->getAthDate()->format('Y-m-d H:i:s'),
            'atl' => $crypto->getAtl(),
            'atlDate' => $crypto->getAtlDate()->format('Y-m-d H:i:s'),
            'updatedAt' => $crypto->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];

        return new JsonResponse($data);
    }

    #[Route('/api/crypto-currency', name: 'crypto_currency_list', methods: ['GET'])]
    public function isValid(Request $request, CryptoCurrencyRepository $cryptoCurrencyRepository): JsonResponse
    {
        $minPrice = $request->query->get('min');
        $maxPrice = $request->query->get('max');

        if ($minPrice !== null && is_numeric($minPrice)) {
            return $this->getByMinPrice($cryptoCurrencyRepository, (float)$minPrice);
        } elseif ($maxPrice !== null && is_numeric($maxPrice)) {
            return $this->getByMaxPrice($cryptoCurrencyRepository, (float)$maxPrice);
        } elseif ($minPrice === null && $maxPrice === null) {
            // Ako nema parametara, možete vratiti sve kriptovalute
            $currencies = $cryptoCurrencyRepository->findAll();
            $data = $this->formatCurrencyData($currencies);
            return new JsonResponse($data);
        } else {
            return new JsonResponse(['error' => 'Invalid parameters'], 400);
        }
    }

    private function getByMinPrice(CryptoCurrencyRepository $cryptoCurrencyRepository, float $minPrice): JsonResponse
    {
        $currencies = $cryptoCurrencyRepository->findByPriceGreaterThan($minPrice);
        $data = $this->formatCurrencyData($currencies);

        return new JsonResponse($data);
    }

    private function getByMaxPrice(CryptoCurrencyRepository $cryptoCurrencyRepository, float $maxPrice): JsonResponse
    {
        $currencies = $cryptoCurrencyRepository->findByPriceLessThan($maxPrice);
        $data = $this->formatCurrencyData($currencies);

        return new JsonResponse($data);
    }

    private function formatCurrencyData(array $currencies): array
    {
        $data = [];
        foreach ($currencies as $currency) {
            $data[] = [
                'id' => $currency->getId(),
                'symbol' => $currency->getSymbol(),
                'name' => $currency->getName(),
                'currentPrice' => $currency->getCurrentPrice(),
            ];
        }
        return $data;
    }

    //Dodatni proizvoljni zahtevi

    #[Route('/api/crypto-currency-detail/{id}', name: 'crypto_currency_detail', methods: ['GET'])]
    public function getCryptoCurrencyDetail(int $id): JsonResponse
    {

        $crypto = $this->cryptoCurrencyRepository->findOneBy(['id' => $id]);

        if (!$crypto) {
            return new JsonResponse(['error' => 'Cryptocurrency not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $crypto->getId(),
            'name' => $crypto->getName(),
            'symbol' => $crypto->getSymbol(),
            'currentPrice' => $crypto->getCurrentPrice(),
            'totalVolume' => $crypto->getTotalVolume(),
            'ath' => $crypto->getAth(),
            'athDate' => $crypto->getAthDate()?->format('Y-m-d H:i:s'),
            'atl' => $crypto->getAtl(),
            'atlDate' => $crypto->getAtlDate()?->format('Y-m-d H:i:s'),
            'updatedAt' => $crypto->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];

        return new JsonResponse($data);
    }

    #[Route('/api/recent-update', name: 'crypto_currency_recent_updates', methods: ['GET'])]
    public function getRecentUpdates(CryptoCurrencyRepository $cryptoCurrencyRepository): JsonResponse
    {
        error_log('Ušao u getRecentUpdates funkciju');
        // Nađi kriptovalute koje su najnovije ažurirane, sortira se po 'updatedAt' polju
        $cryptos = $cryptoCurrencyRepository->findBy([], ['updatedAt' => 'DESC'], 2); // Limitiramo na 2 najnovija

        // Ako nema ažuriranih kriptovaluta
        if (!$cryptos) {
            return new JsonResponse(['error' => 'No cryptocurrencies found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($cryptos as $crypto) {
            $data[] = [
                'id' => $crypto->getId(),
                'name' => $crypto->getName(),
                'symbol' => $crypto->getSymbol(),
                'currentPrice' => $crypto->getCurrentPrice(),
                'totalVolume' => $crypto->getTotalVolume(),
                'ath' => $crypto->getAth(),
                'athDate' => $crypto->getAthDate()?->format('Y-m-d H:i:s'),
                'atl' => $crypto->getAtl(),
                'atlDate' => $crypto->getAtlDate()?->format('Y-m-d H:i:s'),
                'updatedAt' => $crypto->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    //kriptovalute po najnizim cenama
    #[Route('/api/crypto-lowest-prices', name: 'api_crypto_lowest_prices', methods: ['GET'])]
    public function getLowestPrices(Request $request, CryptoCurrencyRepository $cryptoCurrencyRepository): JsonResponse
    {
        $limit = $request->query->get('limit', 5);

        if (!is_numeric($limit)) {
            return new JsonResponse(['error' => 'Invalid limit parameter'], Response::HTTP_BAD_REQUEST);
        }

        $cryptos = $cryptoCurrencyRepository->findLowestPrices((int)$limit);
        $data = $this->formatCurrencyData($cryptos);

        return new JsonResponse($data);
    }
}
