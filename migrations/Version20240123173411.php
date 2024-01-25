<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123173411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink CHANGE id id INT NOT NULL, CHANGE sku sku VARCHAR(255) DEFAULT NULL, CHANGE brand brand VARCHAR(255) DEFAULT NULL, CHANGE facebook facebook INT DEFAULT NULL, CHANGE is_kcup is_kcup INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE sku sku VARCHAR(20) DEFAULT NULL, CHANGE brand brand VARCHAR(255) NOT NULL, CHANGE facebook facebook TINYINT(1) DEFAULT NULL, CHANGE is_kcup is_kcup TINYINT(1) DEFAULT NULL');
    }
}
