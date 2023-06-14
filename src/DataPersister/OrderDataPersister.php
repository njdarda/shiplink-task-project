<?php

namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Security\Core\Security;

final class OrderDataPersister implements ContextAwareDataPersisterInterface
{
    private ContextAwareDataPersisterInterface $decorated;
    private Security $security;

    public function __construct(ContextAwareDataPersisterInterface $decorated, Security $security)
    {
        $this->decorated = $decorated;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        $method = $context['operation'];

        if ($method instanceof Post) {
            $data->setOwner($this->security->getUser());
        }

        $result = $this->decorated->persist($data, $context);

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}
