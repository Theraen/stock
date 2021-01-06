<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106154513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_category_recipe (recipe_id INT NOT NULL, category_recipe_id INT NOT NULL, INDEX IDX_BC142E2059D8A214 (recipe_id), INDEX IDX_BC142E209EB87024 (category_recipe_id), PRIMARY KEY(recipe_id, category_recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_category_recipe ADD CONSTRAINT FK_BC142E2059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category_recipe ADD CONSTRAINT FK_BC142E209EB87024 FOREIGN KEY (category_recipe_id) REFERENCES category_recipe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE category_recipe_recipe');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_recipe_recipe (category_recipe_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_494D8A5D59D8A214 (recipe_id), INDEX IDX_494D8A5D9EB87024 (category_recipe_id), PRIMARY KEY(category_recipe_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_recipe_recipe ADD CONSTRAINT FK_494D8A5D59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_recipe_recipe ADD CONSTRAINT FK_494D8A5D9EB87024 FOREIGN KEY (category_recipe_id) REFERENCES category_recipe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE recipe_category_recipe');
    }
}
