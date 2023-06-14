<?php

namespace App\Controller\Api;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecreateOrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Order $data): Order
    {
        if (!$data->isCancelled()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Can not recreate active order.");
        }

        $newOrder = new Order();
        $newOrder->setOwner($data->getOwner());
        $newOrder->setCancelled(false);
        $newOrder->setProducts($data->getProducts());

        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        return $newOrder;
    }
}
