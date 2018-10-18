<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181018072242 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, liker_id INT NOT NULL, message_liked_id INT NOT NULL, INDEX IDX_49CA4E7D979F103A (liker_id), INDEX IDX_49CA4E7DEBCBDC68 (message_liked_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D979F103A FOREIGN KEY (liker_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DEBCBDC68 FOREIGN KEY (message_liked_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message DROP likes');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE likes');
        $this->addSql('ALTER TABLE message ADD likes INT DEFAULT NULL');
    }
}
