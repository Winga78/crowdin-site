<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:crowdin/migrations/Version20230416022913.php
final class Version20230416022913 extends AbstractMigration
========
final class Version20230411140659 extends AbstractMigration
>>>>>>>> 1c312c2e5ee7b2b88d0f127c8d979dcefa94669c:crowdin/migrations/Version20230411140659.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE langues (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(30) NOT NULL, langue VARCHAR(5) NOT NULL, datecreation DATE NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_5F8A7F73166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traduction_target (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, user_id INT NOT NULL, langue VARCHAR(10) NOT NULL, INDEX IDX_17B4841B953C1C61 (source_id), INDEX IDX_17B4841BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_langue (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, langue VARCHAR(5) NOT NULL, INDEX IDX_F6056EB3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE traduction_target ADD CONSTRAINT FK_17B4841B953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE traduction_target ADD CONSTRAINT FK_17B4841BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_langue ADD CONSTRAINT FK_F6056EB3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY FK_5F8A7F73166D1F9C');
        $this->addSql('ALTER TABLE traduction_target DROP FOREIGN KEY FK_17B4841B953C1C61');
        $this->addSql('ALTER TABLE traduction_target DROP FOREIGN KEY FK_17B4841BA76ED395');
        $this->addSql('ALTER TABLE user_langue DROP FOREIGN KEY FK_F6056EB3A76ED395');
        $this->addSql('DROP TABLE langues');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE traduction_target');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_langue');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
