<?php

namespace App\Tests\Api;

use App\Tests\Util\ApiBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * @method ApiBrowser browser()
 */
class LoginTest extends KernelTestCase
{
    use Factories, HasBrowser;

    public function testUserCanLogIn(): void {
        $this->browser()
            ->loginAs('user', 'user_password')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson()
            ->json()
            ->assertHas('token')
        ;
    }

    public function testUserCanNotLoginWithIncorrectPassword(): void {
        $this->browser()
            ->loginAs('user', 'wrong_password')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson()
            ->json()
        ;
    }
}
