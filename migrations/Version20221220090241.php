<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220090241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, category_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, problem LONGTEXT NOT NULL, impact_rate VARCHAR(50) NOT NULL, effort_rate VARCHAR(50) NOT NULL, first_decision LONGTEXT DEFAULT NULL, final_decision LONGTEXT DEFAULT NULL, INDEX IDX_84ACBE48F675F31B (author_id), INDEX IDX_84ACBE4812469DE2 (category_id), INDEX IDX_84ACBE486BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE4812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE486BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48F675F31B');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE4812469DE2');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE486BF700BD');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE status');
    }
}
