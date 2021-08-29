<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210828121341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_book_record DROP FOREIGN KEY FK_32AEF811217BBB47');
        $this->addSql('DROP INDEX IDX_32AEF811217BBB47 ON phone_book_record');
        $this->addSql('ALTER TABLE phone_book_record DROP person_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_book_record ADD person_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone_book_record ADD CONSTRAINT FK_32AEF811217BBB47 FOREIGN KEY (person_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_32AEF811217BBB47 ON phone_book_record (person_id)');
    }
}
