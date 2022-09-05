<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905074702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swap CHANGE idshape_id idshape_id INT NOT NULL, CHANGE idgameuser_id idgameuser_id INT NOT NULL, CHANGE idgamebuyer_id idgamebuyer_id INT NOT NULL, CHANGE iduser_id iduser_id INT NOT NULL, CHANGE idbuyer_id idbuyer_id INT NOT NULL, CHANGE swapuser swapuser TINYINT(1) NOT NULL, CHANGE swapbuyer swapbuyer TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE typname typname VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swap CHANGE idshape_id idshape_id INT DEFAULT NULL, CHANGE iduser_id iduser_id INT DEFAULT NULL, CHANGE idbuyer_id idbuyer_id INT DEFAULT NULL, CHANGE idgameuser_id idgameuser_id INT DEFAULT NULL, CHANGE idgamebuyer_id idgamebuyer_id INT DEFAULT NULL, CHANGE swapuser swapuser TINYINT(1) DEFAULT NULL, CHANGE swapbuyer swapbuyer TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE typname typname VARCHAR(100) DEFAULT NULL');
    }
}
