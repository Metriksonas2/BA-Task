<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210829184004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE phone_book_record_user (phone_book_record_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F072023396AEE91F (phone_book_record_id), INDEX IDX_F0720233A76ED395 (user_id), PRIMARY KEY(phone_book_record_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone_book_record_user ADD CONSTRAINT FK_F072023396AEE91F FOREIGN KEY (phone_book_record_id) REFERENCES phone_book_record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE phone_book_record_user ADD CONSTRAINT FK_F0720233A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE phone_book_record_user');
    }
}
