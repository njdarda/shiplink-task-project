<?php

namespace App\Tests\Api;

use App\Tests\Util\ApiBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * @method ApiBrowser browser()
 */
class OrderTest extends KernelTestCase
{
    use Factories, HasBrowser;

    public function testUserCanCreateOrder(): void
    {
        $browser = $this->browser()->loginAs('user', 'user_password');

        $this->createTestOrder($browser, 3);

        $browser
            ->get('/api/orders')
            ->json()
            ->hasCount(1)
        ;
    }

    public function testUserCanChangeCancellationStatusButNotProducts(): void
    {
        $browser = $this->browser()->loginAs('user', 'user_password');

        $order = $this->createTestOrder($browser, 3);

        $patchedOrder = $browser
            ->patch("/api/orders/${order['id']}", [
                'headers' => [
                    'CONTENT_TYPE' => 'application/merge-patch+json',
                ],
                'json' => [
                    'cancelled' => false,
                    'products' => [],
                ],
            ])
            ->assertStatus(Response::HTTP_OK)
            ->json()
            ->decoded()
        ;

        self::assertFalse($patchedOrder['cancelled']);
        self::assertCount(3, $patchedOrder['products']);
    }

    public function testUserCanRecreateOrder(): void
    {
        $browser = $this->browser()->loginAs('user', 'user_password');

        $order = $this->createTestOrder($browser, 3, true);

        $browser
            ->get("/api/orders/${order['id']}/recreate")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson()
        ;
    }

    public function testUserCanNotRecreateActiveOrder(): void
    {
        $browser = $this->browser()->loginAs('user', 'user_password');

        $order = $this->createTestOrder($browser, 3);

        $browser
            ->get("/api/orders/${order['id']}/recreate")
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    private function createTestOrder(ApiBrowser $browser, int $numberOfProducts = 0, bool $cancelled = false): array
    {
        $products = $browser->get('/api/products')->getItems();
        $productsToOrder = array_column(array_slice($products, 0, $numberOfProducts), '@id');

        return $browser
            ->post('/api/orders', HttpOptions::json([
                'cancelled' => $cancelled,
                'products' => $productsToOrder,
            ]))
            ->assertStatus(Response::HTTP_CREATED)
            ->json()
            ->decoded()
        ;
    }
}
