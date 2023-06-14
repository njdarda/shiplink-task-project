<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class LoginTest extends KernelTestCase
{
    use Factories, HasBrowser;

    public function testUserCanLogIn(): void {
        $this->browser()
            ->post('/auth', [
                'json' => [
                    'email' => 'Dr. Lynn Kilback IV',
                    'password' => 'YpI*1<;z\'F2|);'
                ],
            ])
            ->assertJson()
            ->json()
            ->search('token')
        ;
    }
}
