<?php

declare(strict_types=1);

namespace Bundles\CurrencyConverterBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241016201044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы currencies для хранения валют.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currencies (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, symbol VARCHAR(10) NOT NULL, name_plural VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_37C4469377153098 ON currencies (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currencies');
    }
}
