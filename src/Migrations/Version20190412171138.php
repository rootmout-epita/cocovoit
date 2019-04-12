<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412171138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_preference (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, preference_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_FA0E76BFA76ED395 (user_id), INDEX IDX_FA0E76BFD81022C0 (preference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preference (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icone_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, activated TINYINT(1) NOT NULL, nbr_canceled_trip INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, conductor_id INT NOT NULL, departure_place VARCHAR(255) NOT NULL, arrival_place VARCHAR(255) NOT NULL, departure_schedule DATETIME NOT NULL, duration TIME NOT NULL, canceled TINYINT(1) NOT NULL, nbr_places INT NOT NULL, price INT NOT NULL, INDEX IDX_7656F53BA49DECF0 (conductor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, user_id INT NOT NULL, reservation_key VARCHAR(255) NOT NULL, ticket_path VARCHAR(255) NOT NULL, reservation_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_42C84955DF928676 (reservation_key), UNIQUE INDEX UNIQ_42C84955398F9E37 (ticket_path), INDEX IDX_42C84955A5BC2E0E (trip_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, trip_id INT NOT NULL, comment LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_D2294458F675F31B (author_id), INDEX IDX_D2294458A5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_checker (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, mail VARCHAR(255) NOT NULL, date DATETIME NOT NULL, check_key VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E417671DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFD81022C0 FOREIGN KEY (preference_id) REFERENCES preference (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA49DECF0 FOREIGN KEY (conductor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE email_checker ADD CONSTRAINT FK_E417671DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_FA0E76BFD81022C0');
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_FA0E76BFA76ED395');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BA49DECF0');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458F675F31B');
        $this->addSql('ALTER TABLE email_checker DROP FOREIGN KEY FK_E417671DA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A5BC2E0E');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A5BC2E0E');
        $this->addSql('DROP TABLE user_preference');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE email_checker');
    }
}
