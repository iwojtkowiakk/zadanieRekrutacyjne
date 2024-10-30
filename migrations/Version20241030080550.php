<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030080550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD warehouse_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1FE25E29A FOREIGN KEY (warehouse_id_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE INDEX IDX_723705D1FE25E29A ON transaction (warehouse_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1FE25E29A');
        $this->addSql('DROP INDEX IDX_723705D1FE25E29A ON transaction');
        $this->addSql('ALTER TABLE transaction DROP warehouse_id_id');
    }
}
