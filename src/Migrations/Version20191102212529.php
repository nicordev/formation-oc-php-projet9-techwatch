<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102212529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_rss_source (tag_id INT NOT NULL, rss_source_id INT NOT NULL, INDEX IDX_6409916FBAD26311 (tag_id), INDEX IDX_6409916FDAE23DB1 (rss_source_id), PRIMARY KEY(tag_id, rss_source_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_twit_list (tag_id INT NOT NULL, twit_list_id INT NOT NULL, INDEX IDX_ADB61D26BAD26311 (tag_id), INDEX IDX_ADB61D2627F9AE68 (twit_list_id), PRIMARY KEY(tag_id, twit_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_rss_source ADD CONSTRAINT FK_6409916FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_rss_source ADD CONSTRAINT FK_6409916FDAE23DB1 FOREIGN KEY (rss_source_id) REFERENCES rss_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_twit_list ADD CONSTRAINT FK_ADB61D26BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_twit_list ADD CONSTRAINT FK_ADB61D2627F9AE68 FOREIGN KEY (twit_list_id) REFERENCES twit_list (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag_rss_source DROP FOREIGN KEY FK_6409916FBAD26311');
        $this->addSql('ALTER TABLE tag_twit_list DROP FOREIGN KEY FK_ADB61D26BAD26311');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_rss_source');
        $this->addSql('DROP TABLE tag_twit_list');
    }
}
