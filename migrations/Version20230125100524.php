<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125100524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision ADD first_decision_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD final_decision_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision ADD updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', DROP first_decision_at, DROP final_decision_at');
    }
}
