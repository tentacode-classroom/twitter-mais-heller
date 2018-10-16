<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016085240 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, follower_id INT DEFAULT NULL, following_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_55EEAC61AC24F853 (follower_id), UNIQUE INDEX UNIQ_55EEAC611816E3A3 (following_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, post_date DATETIME NOT NULL, likes INT DEFAULT NULL, retweet INT DEFAULT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_name VARCHAR(50) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(190) NOT NULL, birthday DATE DEFAULT NULL, profile_picture VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', banner_picture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E8EBF192 (profile_name), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_friend (user_id INT NOT NULL, friend_id INT NOT NULL, INDEX IDX_30BCB75CA76ED395 (user_id), INDEX IDX_30BCB75C6A5458E8 (friend_id), PRIMARY KEY(user_id, friend_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC611816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75C6A5458E8 FOREIGN KEY (friend_id) REFERENCES friend (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_friend DROP FOREIGN KEY FK_30BCB75C6A5458E8');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61AC24F853');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC611816E3A3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE user_friend DROP FOREIGN KEY FK_30BCB75CA76ED395');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_friend');
    }
}
