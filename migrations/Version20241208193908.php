<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208193908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, stock_name VARCHAR(4) NOT NULL, created_at DATE NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Initial data for companies
        $this->addSql("INSERT INTO company 
        (name, stock_name, created_at, description) 
        VALUES ('Apple', 'AAPL', '2024-12-08 12:00:00', 'desc')");

        $this->addSql("INSERT INTO company 
        (name, stock_name, created_at, description) 
        VALUES ('Facebook', 'META', '2024-12-08 12:00:00', 'desc')");

        $this->addSql("INSERT INTO company 
        (name, stock_name, created_at, description) 
        VALUES ('Netflix', 'NFLX', '2024-12-08 12:00:00', 'desc')");

        $this->addSql("INSERT INTO company 
        (name, stock_name, created_at, description) 
        VALUES ('Amazon', 'AMZN', '2024-12-08 12:00:00', 'desc')");

        $this->addSql("INSERT INTO company 
        (name, stock_name, created_at, description) 
        VALUES ('Google', 'GOOG', '2024-12-08 12:00:00', 'desc 4')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE company');
    }
}
