<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104131818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_stock ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture_stock ADD CONSTRAINT FK_CBECD12EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CBECD12EA76ED395 ON picture_stock (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_stock DROP FOREIGN KEY FK_CBECD12EA76ED395');
        $this->addSql('DROP INDEX IDX_CBECD12EA76ED395 ON picture_stock');
        $this->addSql('ALTER TABLE picture_stock DROP user_id');
    }
}
