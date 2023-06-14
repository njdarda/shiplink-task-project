<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614184017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fakestore_id field';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD fakestore_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADC7A30A6B ON product (fakestore_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_D34A04ADC7A30A6B');
        $this->addSql('ALTER TABLE product DROP fakestore_id');
    }
}
