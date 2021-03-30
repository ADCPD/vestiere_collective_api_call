<?php

namespace App\Services;


use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class HistoriqueService
 * @package App\Services
 */
class HistoriqueService {

    private $entityManager;

    /**
     * HistoriqueService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Save history for transaction
     * @param array $data
     */
    public function saveData(array $data) {
        $history = new History();
        $history->setData((array)$this->serializeData($data));
        $history->setCreatedAt();
        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

    /**
     * @param array $data
     * @param string $format
     * @return string
     */
    private function serializeData(array $data,string $format = 'json')
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

       return $serializer->serialize($data, $format);
    }
}
