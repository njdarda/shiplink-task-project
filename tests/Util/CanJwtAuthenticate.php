<?php declare(strict_types=1);

namespace App\Tests\Util;

use Zenstruck\Browser\HttpOptions;

trait CanJwtAuthenticate
{
    public function loginAs(string $email, string $password): self
    {
        $response = $this
            ->post(
                '/auth',
                HttpOptions::json([
                    'username' => $email,
                    'password' => $password,
                ])
            )
            ->json()
        ;

        $this->setDefaultHttpOptions([
            'headers' => [
                'Authorization' => 'Bearer ' . ($response->decoded()['token'] ?? ''),
            ],
        ]);

        return $this;
    }

    public function logout(): self
    {
        $this->setDefaultHttpOptions([]);

        return $this;
    }

    public function getItems(): array
    {
        return $this
            ->json()
            ->decoded()['hydra:member']
        ;
    }
}
