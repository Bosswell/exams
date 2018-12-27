<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227205808 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE qualification (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(10) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, qualification_id INT NOT NULL, query LONGTEXT NOT NULL, answer_a LONGTEXT NOT NULL, answer_b LONGTEXT NOT NULL, answer_c LONGTEXT NOT NULL, answer_d LONGTEXT NOT NULL, correct SMALLINT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6F7494E1A75EE38 (qualification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_qualification (stage_id INT NOT NULL, qualification_id INT NOT NULL, INDEX IDX_4BEB764E2298D193 (stage_id), INDEX IDX_4BEB764E1A75EE38 (qualification_id), PRIMARY KEY(stage_id, qualification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E1A75EE38 FOREIGN KEY (qualification_id) REFERENCES qualification (id)');
        $this->addSql('ALTER TABLE stage_qualification ADD CONSTRAINT FK_4BEB764E2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_qualification ADD CONSTRAINT FK_4BEB764E1A75EE38 FOREIGN KEY (qualification_id) REFERENCES qualification (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E1A75EE38');
        $this->addSql('ALTER TABLE stage_qualification DROP FOREIGN KEY FK_4BEB764E1A75EE38');
        $this->addSql('ALTER TABLE stage_qualification DROP FOREIGN KEY FK_4BEB764E2298D193');
        $this->addSql('DROP TABLE qualification');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_qualification');
    }
}
