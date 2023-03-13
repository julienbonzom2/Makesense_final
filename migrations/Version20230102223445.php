<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102223445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision ADD profit LONGTEXT NOT NULL, ADD drawback LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE ongoing_decision ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ongoing_decision ADD CONSTRAINT FK_FCC9CDD412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_FCC9CDD412469DE2 ON ongoing_decision (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ongoing_decision DROP FOREIGN KEY FK_FCC9CDD412469DE2');
        $this->addSql('DROP INDEX IDX_FCC9CDD412469DE2 ON ongoing_decision');
        $this->addSql('ALTER TABLE ongoing_decision DROP category_id');
        $this->addSql('ALTER TABLE decision DROP profit, DROP drawback');
    }
}
