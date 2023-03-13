<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220092309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ongoing_concerned_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ongoing_decision_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F2980820A76ED395 (user_id), INDEX IDX_F2980820702D8B37 (ongoing_decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ongoing_concerned_user ADD CONSTRAINT FK_F2980820A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ongoing_concerned_user ADD CONSTRAINT FK_F2980820702D8B37 FOREIGN KEY (ongoing_decision_id) REFERENCES ongoing_decision (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ongoing_concerned_user DROP FOREIGN KEY FK_F2980820A76ED395');
        $this->addSql('ALTER TABLE ongoing_concerned_user DROP FOREIGN KEY FK_F2980820702D8B37');
        $this->addSql('DROP TABLE ongoing_concerned_user');
    }
}
