<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105153951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preparation_recipe (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, title LONGTEXT NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B857738659D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, nb_person INT NOT NULL, preparation_time INT NOT NULL, cooking_time INT NOT NULL, note LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preparation_recipe ADD CONSTRAINT FK_B857738659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE ingredient_recipe ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F2717659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_36F2717659D8A214 ON ingredient_recipe (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F2717659D8A214');
        $this->addSql('ALTER TABLE preparation_recipe DROP FOREIGN KEY FK_B857738659D8A214');
        $this->addSql('DROP TABLE preparation_recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP INDEX IDX_36F2717659D8A214 ON ingredient_recipe');
        $this->addSql('ALTER TABLE ingredient_recipe DROP recipe_id');
    }
}
