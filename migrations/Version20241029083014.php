<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029083014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_warehouse (user_id INT NOT NULL, warehouse_id INT NOT NULL, INDEX IDX_EC530618A76ED395 (user_id), INDEX IDX_EC5306185080ECDE (warehouse_id), PRIMARY KEY(user_id, warehouse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_warehouse ADD CONSTRAINT FK_EC530618A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_warehouse ADD CONSTRAINT FK_EC5306185080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_warehouse DROP FOREIGN KEY FK_EC530618A76ED395');
        $this->addSql('ALTER TABLE user_warehouse DROP FOREIGN KEY FK_EC5306185080ECDE');
        $this->addSql('DROP TABLE user_warehouse');
    }
}
