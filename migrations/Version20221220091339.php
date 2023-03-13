<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220091339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, comment_author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, comment_type VARCHAR(50) NOT NULL, INDEX IDX_5F9E962ABDEE7539 (decision_id), INDEX IDX_5F9E962A1F0B124D (comment_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concerned_user (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_70547ADEBDEE7539 (decision_id), INDEX IDX_70547ADEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A1F0B124D FOREIGN KEY (comment_author_id) REFERENCES concerned_user (id)');
        $this->addSql('ALTER TABLE concerned_user ADD CONSTRAINT FK_70547ADEBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE concerned_user ADD CONSTRAINT FK_70547ADEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ABDEE7539');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A1F0B124D');
        $this->addSql('ALTER TABLE concerned_user DROP FOREIGN KEY FK_70547ADEBDEE7539');
        $this->addSql('ALTER TABLE concerned_user DROP FOREIGN KEY FK_70547ADEA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE concerned_user');
    }
}
