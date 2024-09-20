<?php

namespace App\Controller;

use App\Entity\CryptoCurrency;
use App\Form\CryptoCurrencySearchType;
use App\Repository\CryptoCurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoCurrencyController extends AbstractController
{
    private CryptoCurrencyRepository $cryptoCurrencyRepository;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(CryptoCurrencyRepository $cryptoCurrencyRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->cryptoCurrencyRepository = $cryptoCurrencyRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/crypto/create', name: 'crypto_create', methods: ['POST'])]
    public function createCrypto(Request $request): Response
    {
        // Preuzimanje podataka iz zahteva
        $name = $request->request->get('name');
        $symbol = $request->request->get('symbol');
        $currentPrice = $request->request->get('price');
        $totalVolume = $request->request->get('totalVolume');
        $ath = $request->request->get('ath');
        $athDateString = $request->request->get('athDate');
        $atl = $request->request->get('atl');
        $atlDateString = $request->request->get('atlDate');

        // Validacija unosa
        if (!$name || !$symbol || !$currentPrice || !$totalVolume || !$ath || !$athDateString || !$atl || !$atlDateString) {
            return new Response('All fields are required!', 400);
        }

        // Kreiranje novog CryptoCurrency entiteta
        $crypto = new CryptoCurrency();
        $crypto->setName($name);
        $crypto->setSymbol($symbol);
        $crypto->setCurrentPrice((float)$currentPrice);
        $crypto->setTotalVolume((float)$totalVolume);
        $crypto->setAth((float)$ath);

        // Konvertovanje stringova u DateTime objekte i postavljanje vrednosti
        try {
            $athDate = new \DateTime($athDateString);
            $crypto->setAthDate($athDate);
        } catch (\Exception $e) {
            return new Response('Invalid athDate format!', 400);
        }

        try {
            $atlDate = new \DateTime($atlDateString);
            $crypto->setAtlDate($atlDate);
        } catch (\Exception $e) {
            return new Response('Invalid atlDate format!', 400);
        }

        $crypto->setAtl((float)$atl);
        $crypto->setUpdatedAt(new \DateTime()); // Postavljanje trenutnog datuma i vremena

        // Persistovanje i čuvanje u bazi
        $this->entityManagerInterface->persist($crypto);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute('home');
    }

    #[Route('/crypto/update/{id}', name: 'crypto_update', methods: ['GET', 'POST'])]
    public function update(Request $request, int $id): Response
    {
        $crypto = $this->cryptoCurrencyRepository->find($id);

        if (!$crypto) {
            throw $this->createNotFoundException('No crypto found for id ' . $id);
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $symbol = $request->request->get('symbol');
            $currentPrice = $request->request->get('price');
            $totalVolume = $request->request->get('totalVolume');
            $ath = $request->request->get('ath');
            $athDate = $request->request->get('athDate');
            $atl = $request->request->get('atl');
            $atlDate = $request->request->get('atlDate');

            $crypto->setName($name);
            $crypto->setSymbol($symbol);
            $crypto->setCurrentPrice((float)$currentPrice);
            $crypto->setTotalVolume((float)$totalVolume);
            $crypto->setAth((float)$ath);
            $crypto->setAtl((float)$atl);

            if ($athDate) {
                try {
                    $athDate = new \DateTime($athDate);
                    $crypto->setAthDate($athDate);
                } catch (\Exception $e) {
                    return new Response('Invalid athDate format!', 400);
                }
            }

            if ($atlDate) {
                try {
                    $atlDate = new \DateTime($atlDate);
                    $crypto->setAtlDate($atlDate);
                } catch (\Exception $e) {
                    return new Response('Invalid atlDate format!', 400);
                }
            }
            $crypto->setUpdatedAt(new \DateTime());


            $this->entityManagerInterface->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('crypto/update.html.twig', [
            'crypto' => $crypto,
        ]);
    }

    #[Route('/crypto/delete/{id}', name: 'crypto_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $crypto = $this->cryptoCurrencyRepository->find($id);

        if (!$crypto) {
            throw $this->createNotFoundException('No crypto found for id ' . $id);
        }

        $this->entityManagerInterface->remove($crypto);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute('home');
    }
}
