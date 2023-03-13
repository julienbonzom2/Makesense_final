<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220100600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A1F0B124D');
        $this->addSql('CREATE TABLE associated (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_D3D550D6BDEE7539 (decision_id), INDEX IDX_D3D550D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ongoing_associated (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ongoing_decision_id INT DEFAULT NULL, ongoing_status VARCHAR(255) NOT NULL, INDEX IDX_C72DCD23A76ED395 (user_id), INDEX IDX_C72DCD23702D8B37 (ongoing_decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE associated ADD CONSTRAINT FK_D3D550D6BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE associated ADD CONSTRAINT FK_D3D550D6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ongoing_associated ADD CONSTRAINT FK_C72DCD23A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ongoing_associated ADD CONSTRAINT FK_C72DCD23702D8B37 FOREIGN KEY (ongoing_decision_id) REFERENCES ongoing_decision (id)');
        $this->addSql('ALTER TABLE concerned_user DROP FOREIGN KEY FK_70547ADEA76ED395');
        $this->addSql('ALTER TABLE concerned_user DROP FOREIGN KEY FK_70547ADEBDEE7539');
        $this->addSql('ALTER TABLE ongoing_concerned_user DROP FOREIGN KEY FK_F2980820702D8B37');
        $this->addSql('ALTER TABLE ongoing_concerned_user DROP FOREIGN KEY FK_F2980820A76ED395');
        $this->addSql('DROP TABLE concerned_user');
        $this->addSql('DROP TABLE ongoing_concerned_user');
        $this->addSql('DROP INDEX IDX_5F9E962A1F0B124D ON comments');
        $this->addSql('ALTER TABLE comments CHANGE comment_author_id associated_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA347F76E FOREIGN KEY (associated_id) REFERENCES associated (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA347F76E ON comments (associated_id)');
        $this->addSql('ALTER TABLE ongoing_decision DROP FOREIGN KEY FK_FCC9CDD4F675F31B');
        $this->addSql('DROP INDEX IDX_FCC9CDD4F675F31B ON ongoing_decision');
        $this->addSql('ALTER TABLE ongoing_decision ADD ongoing_description LONGTEXT DEFAULT NULL, ADD ongoing_problem LONGTEXT DEFAULT NULL, ADD ongoing_impact_rate VARCHAR(50) DEFAULT NULL, ADD ongoing_effort_rate VARCHAR(50) DEFAULT NULL, DROP description, DROP problem, DROP impact_rate, DROP effort_rate, CHANGE author_id ongoing_author_id INT DEFAULT NULL, CHANGE title ongoing_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ongoing_decision ADD CONSTRAINT FK_FCC9CDD4FB44907 FOREIGN KEY (ongoing_author_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FCC9CDD4FB44907 ON ongoing_decision (ongoing_author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA347F76E');
        $this->addSql('CREATE TABLE concerned_user (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_70547ADEA76ED395 (user_id), INDEX IDX_70547ADEBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ongoing_concerned_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ongoing_decision_id INT DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F2980820702D8B37 (ongoing_decision_id), INDEX IDX_F2980820A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE concerned_user ADD CONSTRAINT FK_70547ADEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE concerned_user ADD CONSTRAINT FK_70547ADEBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ongoing_concerned_user ADD CONSTRAINT FK_F2980820702D8B37 FOREIGN KEY (ongoing_decision_id) REFERENCES ongoing_decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ongoing_concerned_user ADD CONSTRAINT FK_F2980820A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE associated DROP FOREIGN KEY FK_D3D550D6BDEE7539');
        $this->addSql('ALTER TABLE associated DROP FOREIGN KEY FK_D3D550D6A76ED395');
        $this->addSql('ALTER TABLE ongoing_associated DROP FOREIGN KEY FK_C72DCD23A76ED395');
        $this->addSql('ALTER TABLE ongoing_associated DROP FOREIGN KEY FK_C72DCD23702D8B37');
        $this->addSql('DROP TABLE associated');
        $this->addSql('DROP TABLE ongoing_associated');
        $this->addSql('ALTER TABLE ongoing_decision DROP FOREIGN KEY FK_FCC9CDD4FB44907');
        $this->addSql('DROP INDEX IDX_FCC9CDD4FB44907 ON ongoing_decision');
        $this->addSql('ALTER TABLE ongoing_decision ADD description LONGTEXT DEFAULT NULL, ADD problem LONGTEXT DEFAULT NULL, ADD impact_rate VARCHAR(50) DEFAULT NULL, ADD effort_rate VARCHAR(50) DEFAULT NULL, DROP ongoing_description, DROP ongoing_problem, DROP ongoing_impact_rate, DROP ongoing_effort_rate, CHANGE ongoing_author_id author_id INT DEFAULT NULL, CHANGE ongoing_title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ongoing_decision ADD CONSTRAINT FK_FCC9CDD4F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FCC9CDD4F675F31B ON ongoing_decision (author_id)');
        $this->addSql('DROP INDEX IDX_5F9E962AA347F76E ON comments');
        $this->addSql('ALTER TABLE comments CHANGE associated_id comment_author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A1F0B124D FOREIGN KEY (comment_author_id) REFERENCES concerned_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5F9E962A1F0B124D ON comments (comment_author_id)');
    }
}
