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
class UserTest extends KernelTestCase
{
    use Factories, HasBrowser;

    public function testUserCanNotSeeUsers(): void {
        $this->browser()
            ->loginAs('user', 'user_password')
            ->get('/api/users')
            ->assertStatus(Response::HTTP_FORBIDDEN)
        ;
    }

    public function testAdminCanSeeUsers(): void {
        $this->browser()
            ->loginAs('admin', 'admin_password')
            ->get('/api/users')
            ->assertStatus(Response::HTTP_OK)
        ;
    }

    public function testUserCanOnlySeeTheirData(): void {
        $userList = $this->browser()
            ->loginAs('admin', 'admin_password')
            ->get('/api/users')
            ->getItems()
        ;

        $userUri = null;
        $adminUri = null;
        foreach ($userList as $userData) {
            if ($userData['username'] === 'user') {
                $userUri = $userData['@id'];
            }

            if ($userData['username'] === 'admin') {
                $adminUri = $userData['@id'];
            }
        }

        $this->browser()
            ->loginAs('user', 'user_password')
            ->get($userUri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson()
            ->get($adminUri)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson()
        ;
    }
}
