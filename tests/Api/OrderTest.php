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

    public function testUserCanCreateOrder(): void {
        $browser = $this->browser()->loginAs('user', 'user_password');

        $products = $browser->get('/api/products')->getItems();
        $productsToOrder = array_column(array_slice($products['hydra:member'], 0, 3), '@id');

        $browser
            ->post('/api/orders', HttpOptions::json([
                'cancelled' => false,
                'products' => $productsToOrder,
            ]))
            ->assertStatus(Response::HTTP_CREATED)
        ;

        $browser
            ->get('/api/orders')
            ->json()
            ->hasCount(1)
        ;
    }

//    public function testUserCanChangeCancellationStatusButNotProducts(): void {
//        $browser = $this->browser()->loginAs('user', 'user_password');
//
//        $products = $browser->get('/api/products')->json()->decoded();
//        $productsToOrder = array_column(array_slice($products['hydra:member'], 0, 3), '@id');
//
//        $browser
//            ->patch('/api/orders', HttpOptions::json([
//                'cancelled' => false,
//                'products' => $productsToOrder,
//            ]))
//            ->assertStatus(Response::HTTP_CREATED)
//        ;
//
//        $browser
//            ->get('/api/orders')
//            ->json()
//            ->hasCount(1)
//        ;
//    }
}
