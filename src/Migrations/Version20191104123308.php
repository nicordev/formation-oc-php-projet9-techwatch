<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191104123308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rss_source_tag (rss_source_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_D4E1BE37DAE23DB1 (rss_source_id), INDEX IDX_D4E1BE37BAD26311 (tag_id), PRIMARY KEY(rss_source_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE twit_list_tag (twit_list_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_2B13CFD427F9AE68 (twit_list_id), INDEX IDX_2B13CFD4BAD26311 (tag_id), PRIMARY KEY(twit_list_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rss_source_tag ADD CONSTRAINT FK_D4E1BE37DAE23DB1 FOREIGN KEY (rss_source_id) REFERENCES rss_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rss_source_tag ADD CONSTRAINT FK_D4E1BE37BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE twit_list_tag ADD CONSTRAINT FK_2B13CFD427F9AE68 FOREIGN KEY (twit_list_id) REFERENCES twit_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE twit_list_tag ADD CONSTRAINT FK_2B13CFD4BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tag_rss_source');
        $this->addSql('DROP TABLE tag_twit_list');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag_rss_source (tag_id INT NOT NULL, rss_source_id INT NOT NULL, INDEX IDX_6409916FBAD26311 (tag_id), INDEX IDX_6409916FDAE23DB1 (rss_source_id), PRIMARY KEY(tag_id, rss_source_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tag_twit_list (tag_id INT NOT NULL, twit_list_id INT NOT NULL, INDEX IDX_ADB61D26BAD26311 (tag_id), INDEX IDX_ADB61D2627F9AE68 (twit_list_id), PRIMARY KEY(tag_id, twit_list_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tag_rss_source ADD CONSTRAINT FK_6409916FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_rss_source ADD CONSTRAINT FK_6409916FDAE23DB1 FOREIGN KEY (rss_source_id) REFERENCES rss_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_twit_list ADD CONSTRAINT FK_ADB61D2627F9AE68 FOREIGN KEY (twit_list_id) REFERENCES twit_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_twit_list ADD CONSTRAINT FK_ADB61D26BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE rss_source_tag');
        $this->addSql('DROP TABLE twit_list_tag');
    }
}
