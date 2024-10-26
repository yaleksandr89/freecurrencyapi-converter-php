<?php

declare(strict_types=1);

namespace Bundles\CurrencyConverterBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241026124026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Обновляет курсы валют с сервиса freecurrencyapi.com и сохраняет их в базу данных.';
    }


    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency_update_schedule (id SERIAL NOT NULL, last_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, next_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currency_update_schedule');
    }
}
