<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UpdateProductDatabase extends Command
{
    const API_URL = 'https://fakestoreapi.com/';

    protected static $defaultName = 'app:update-product-database';

    private EntityManagerInterface $entityManager;
    private HttpClientInterface $client;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $client
    ) {
        $this->entityManager = $entityManager;
        $this->client = $client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Use this command to fetch and update product database.')
            ->addOption(
                'truncate',
                't',
                InputOption::VALUE_NONE,
                'Truncate products table before updating?',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('truncate')) {
            $this->truncateProductsDatabase();
        }

        $this->populateProductDatabaseFromFakestore();

        return Command::SUCCESS;
    }

    private function truncateProductsDatabase() {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            $this->entityManager->remove($product);
        }

        $this->entityManager->flush();
    }

    private function populateProductDatabaseFromFakestore(): void
    {
        $response = $this->client->request(Request::METHOD_GET, self::API_URL . 'products');

        $content = $response->getContent();
        $contentJson = json_decode($content, true, JSON_THROW_ON_ERROR);

        foreach ($contentJson as $product) {
            $newProduct = new Product();

            $fakestoreId = (int)$product['id'];

            $alreadyInDatabase = $this->entityManager->getRepository(Product::class)->findOneBy(['fakestoreId' => $fakestoreId]);
            if ($alreadyInDatabase) {
                continue;
            }

            $newProduct->setFakestoreId($fakestoreId);
            $newProduct->setTitle($product['title']);
            $newProduct->setDescription($product['description']);
            $newProduct->setCategory($product['category']);
            $newProduct->setPrice((string)$product['price']);
            $newProduct->setImage($product['image']);

            $this->entityManager->persist($newProduct);
        }

        $this->entityManager->flush();
    }
}
