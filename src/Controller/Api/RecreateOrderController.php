<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Service\RecreateOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecreateOrderController extends AbstractController
{
    private RecreateOrderService $recreateOrderService;

    public function __construct(RecreateOrderService $recreateOrderService)
    {
        $this->recreateOrderService = $recreateOrderService;
    }

    public function __invoke(Order $data): Order
    {
        return $this->recreateOrderService->recreateOrder($data);
    }
}
