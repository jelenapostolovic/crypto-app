<?php

namespace App\Command;

use App\Entity\CryptoCurrency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:fetch-crypto-data',
    description: 'Fetches cryptocurrency data from the API and stores it in the database',
)]
class FetchCryptoDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=50&page=1&sparkline=false');
        $data = $response->toArray();

        foreach ($data as $item) {
            $crypto = new CryptoCurrency();
            $crypto->setName($item['name']);
            $crypto->setSymbol($item['symbol']);
            $crypto->setCurrentPrice($item['current_price']);
            $crypto->setTotalVolume($item['total_volume']);
            $crypto->setAth($item['ath']);
            $crypto->setAthDate(new \DateTime($item['ath_date']));
            $crypto->setAtl($item['atl']);
            $crypto->setAtlDate(new \DateTime($item['atl_date']));
            $crypto->setUpdatedAt(new \DateTime($item['last_updated']));

            $this->entityManager->persist($crypto);
        }

        $this->entityManager->flush();

        $output->writeln('Crypto data has been fetched and stored successfully.');

        return Command::SUCCESS;
    }
}
