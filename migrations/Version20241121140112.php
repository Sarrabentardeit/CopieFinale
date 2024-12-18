<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121140112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP name, DROP email, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE doctor CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36ABF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient CHANGE id id INT NOT NULL, CHANGE dob dob DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD type VARCHAR(255) NOT NULL, DROP username');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE admin ADD name VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36ABF396750');
        $this->addSql('ALTER TABLE doctor CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750');
        $this->addSql('ALTER TABLE patient CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE dob dob DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) DEFAULT NULL, DROP type');
    }
}
