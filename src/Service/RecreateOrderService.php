<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecreateOrderService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function recreateOrder(Order $order): Order
    {
        if (!$order->isCancelled()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Can not recreate active order.");
        }

        $newOrder = new Order();
        $newOrder->setOwner($order->getOwner());
        $newOrder->setCancelled(false);
        $newOrder->setProducts($order->getProducts());

        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        return $newOrder;
    }
}
