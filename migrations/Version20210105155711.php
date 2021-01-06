<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105155711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D5607B4CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_recipe_recipe (category_recipe_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_494D8A5D9EB87024 (category_recipe_id), INDEX IDX_494D8A5D59D8A214 (recipe_id), PRIMARY KEY(category_recipe_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_recipe ADD CONSTRAINT FK_D5607B4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category_recipe_recipe ADD CONSTRAINT FK_494D8A5D9EB87024 FOREIGN KEY (category_recipe_id) REFERENCES category_recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_recipe_recipe ADD CONSTRAINT FK_494D8A5D59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_recipe_recipe DROP FOREIGN KEY FK_494D8A5D9EB87024');
        $this->addSql('DROP TABLE category_recipe');
        $this->addSql('DROP TABLE category_recipe_recipe');
    }
}
