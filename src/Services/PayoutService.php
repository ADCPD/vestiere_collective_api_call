<?php

namespace App\Services;

use App\Entity\Payout;
use App\Repository\ItemRepository;
use App\Repository\SellerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PayoutService
 * @package App\Services
 */
class PayoutService
{
    const PAYMENT_METHOD = ['card'];
    const PAYMENT_MODE = 'payment';
    const LIMIT_PAYMENT_AMOUNT = 1000000;

    /** @var EntityManager */
    protected $entityManager;

    /** @var ItemRepository */
    private $itemRepository;

    /** @var SellerRepository */
    private $sellerRepository;

    /** @var CurrencyApiService */
    protected $currencyService;

    /** @var StripeService */
    protected $stripeService;

    /** @var HistoriqueService */
    protected $historyService;

    /**
     * PayoutService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ItemRepository $itemRepository
     * @param SellerRepository $sellerRepository
     * @param CurrencyApiService $currencyApiService
     * @param StripeService $stripeService
     * @param HistoriqueService $historiqueService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ItemRepository $itemRepository,
        SellerRepository  $sellerRepository,
        CurrencyApiService $currencyApiService,
        StripeService  $stripeService,
        HistoriqueService $historiqueService
    )
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
        $this->sellerRepository = $sellerRepository;
        $this->currencyService = $currencyApiService;
        $this->stripeService = $stripeService;
        $this->historyService = $historiqueService;
    }

    /**
     * @param $data
     */
    public function paymentProcess($data): array
    {
        $response = [];
        $totalCurrencyAmount = 0;
        if (key_exists('totalPriceCurrency', $this->getTotalAmount($data))){
            $totalCurrencyAmount = $this->getTotalAmount($data)['totalPriceCurrency'];
        }

        if ($totalCurrencyAmount < self::LIMIT_PAYMENT_AMOUNT) {
            $stripe = $this->stripeService->createStripeSession(
                 self::PAYMENT_METHOD,
                 strtolower($data['currency']),
                 ['name' => 'Commande Vestiaire Collective'],
                 $totalCurrencyAmount,
                 1,
                 self::PAYMENT_MODE,
                  $data['paths']
             );
        }

        if ($stripe['payment_status'] === 'unpaid') {
            $response = [
                'status'  => Response::HTTP_BAD_REQUEST,
                'message' => 'unpaid transaction'
            ];

            $this->prepareHistoriqueData($data['seller_id'],'unpaid');

        } elseif ($stripe['payment_status'] === 'paid') {
            $response = $this->paidProcessTransaction($data, $totalCurrencyAmount, 'paid');
        }

        return $response;
    }

    /**
     * @param array $data
     * @param float $totalCurrencyAmount
     * @param string $transactionStatus
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function paidProcessTransaction(array $data, float $totalCurrencyAmount, string $transactionStatus) {
        $payout = new Payout();
        $payout->setAmount($totalCurrencyAmount);
        $payout->setCurrency(strtolower($data['currency']));
        $this->entityManager->persist($payout);
        $this->entityManager->flush();

        $this->prepareHistoriqueData($data['seller_id'], $transactionStatus);

        return [
            'status'  => Response::HTTP_OK,
            'message' => 'paid transaction'
        ];
    }

    /**
     * Preparing api call Data
     *
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {
        if (key_exists('seller_id', $data) && key_exists('items', $data)) {
          $items  = $this->itemRepository->findItemByCriteria($data['seller_id']);
        }

        if (key_exists('currencyAmount', $data)){
            $currency = $data['currencyAmount'];
        }

        return [
            'sellerId' =>  $data['seller_id'],
            'items' => $items,
            'currency' => $currency
        ];
    }

    /**
     * Return total price of the order
     *
     * @param $data
     * @return array
     */
    private function getTotalAmount($data): array
    {
        $selledItem =  $this->prepareData($data);

        $totalAmount = 0;
        $totalCurrencyAmount = 0;
        if (key_exists('currency', $selledItem)){
            if ($selledItem['currency'] == 1) {
                foreach ($selledItem['items'] as $index => $item) {
                    $totalAmount += $item['priceAmount'];
                }
                $totalCurrencyAmount = $totalAmount;
            } else {
                foreach ($selledItem['items'] as $index => $item) {
                    $totalAmount += $item['priceAmount'];
                    $totalCurrencyAmount += $item['priceAmount'] * $selledItem['currency'];
                }
            }
        }

        return [
            'totalPriceAmount'   => $totalAmount,
            'totalPriceCurrency' => $totalCurrencyAmount
        ];
    }

    /**
     * @param $sellerReference
     * @param string $transactionStatus
     */
    private function prepareHistoriqueData($sellerReference,string $transactionStatus)
    {
        $items = $this->itemRepository->findItemByCriteria($sellerReference);
        $data = [
            'items' => $items,
            'transaction_status' => $transactionStatus
        ];

        return $this->historyService->saveData($data);
    }
}
