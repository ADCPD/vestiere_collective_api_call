<?php


namespace App\Services;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class CurrencyApiService
 * @package App\Services
 */
class CurrencyApiService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    private $client;

    /**
     * @var array
     */
    public $currency;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $client
    )
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param string $currency
     * @return float|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCurrencyAmount(string $url, string $currency): ?float
    {
        $result = 1;
        $data = $this->getApiData($url);
        if (key_exists('rates', $data)) {
           if ($currency == 'EUR') {
               $result = 1;
           } else {
               $result = floatval($data['rates'][$currency]);
           }
        }
        return $result;
    }

    /**
     * Exemple : [▼
            "rates" => array:2 [
            "USD" => 1.1741
            "GBP" => 0.85378
            ]
            "base" => "EUR"
            "date" => "2021-03-30"
            ]
     * @param string $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getApiData(string $url) {
          if ($content = $this->callApi($url)) {
              $this->currency = $content;
          }

          return $this->currency;
    }

    /**
     * @param string $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function callApi(string $url): ?array
    {
        $response = $this->client->request(
            'GET',
            $url
        );
        return $response->toArray();
    }
}