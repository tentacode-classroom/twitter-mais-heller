<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181018145344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, follower_id INT NOT NULL, following_id INT NOT NULL, INDEX IDX_55EEAC61AC24F853 (follower_id), INDEX IDX_55EEAC611816E3A3 (following_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, liker_id INT NOT NULL, message_liked_id INT NOT NULL, INDEX IDX_49CA4E7D979F103A (liker_id), INDEX IDX_49CA4E7DEBCBDC68 (message_liked_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, post_date DATETIME NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retweet (id INT AUTO_INCREMENT NOT NULL, retweeter_id INT NOT NULL, message_retweeted_id INT NOT NULL, INDEX IDX_45E67DB3219566C2 (retweeter_id), INDEX IDX_45E67DB390E65B67 (message_retweeted_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_name VARCHAR(50) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(190) NOT NULL, birthday DATE DEFAULT NULL, profile_picture VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', banner_picture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E8EBF192 (profile_name), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC611816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D979F103A FOREIGN KEY (liker_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DEBCBDC68 FOREIGN KEY (message_liked_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB3219566C2 FOREIGN KEY (retweeter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB390E65B67 FOREIGN KEY (message_retweeted_id) REFERENCES message (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DEBCBDC68');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB390E65B67');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61AC24F853');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC611816E3A3');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D979F103A');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB3219566C2');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE retweet');
        $this->addSql('DROP TABLE user');
    }
}
