<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123154420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drink (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) DEFAULT NULL, sku VARCHAR(20) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) NOT NULL, rating SMALLINT DEFAULT NULL, caffeine VARCHAR(255) DEFAULT NULL, count INT DEFAULT NULL, flavored VARCHAR(20) DEFAULT NULL, seasonal VARCHAR(10) DEFAULT NULL, instock VARCHAR(255) DEFAULT NULL, facebook TINYINT(1) DEFAULT NULL, is_kcup TINYINT(1) DEFAULT NULL, shortdesc LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE drink');
    }
}
