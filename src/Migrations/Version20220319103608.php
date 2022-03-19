<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220319103608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE email_checker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE preference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trip_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_preference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE email_checker (id INT NOT NULL, user_id INT NOT NULL, mail VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, check_key VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E417671DA76ED395 ON email_checker (user_id)');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, author_id INT NOT NULL, trip_id INT NOT NULL, comment TEXT NOT NULL, note INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2294458F675F31B ON feedback (author_id)');
        $this->addSql('CREATE INDEX IDX_D2294458A5BC2E0E ON feedback (trip_id)');
        $this->addSql('CREATE TABLE preference (id INT NOT NULL, name VARCHAR(255) NOT NULL, icone_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, trip_id INT NOT NULL, user_id INT NOT NULL, reservation_key VARCHAR(255) NOT NULL, ticket_path VARCHAR(255) NOT NULL, reservation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C84955DF928676 ON reservation (reservation_key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C84955398F9E37 ON reservation (ticket_path)');
        $this->addSql('CREATE INDEX IDX_42C84955A5BC2E0E ON reservation (trip_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE TABLE trip (id INT NOT NULL, conductor_id INT NOT NULL, departure_place VARCHAR(255) NOT NULL, arrival_place VARCHAR(255) NOT NULL, departure_schedule TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration TIME(0) WITHOUT TIME ZONE NOT NULL, canceled BOOLEAN NOT NULL, nbr_places INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7656F53BA49DECF0 ON trip (conductor_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, activated BOOLEAN NOT NULL, nbr_canceled_trip INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_preference (id INT NOT NULL, user_id INT NOT NULL, preference_id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA0E76BFA76ED395 ON user_preference (user_id)');
        $this->addSql('CREATE INDEX IDX_FA0E76BFD81022C0 ON user_preference (preference_id)');
        $this->addSql('ALTER TABLE email_checker ADD CONSTRAINT FK_E417671DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA49DECF0 FOREIGN KEY (conductor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFD81022C0 FOREIGN KEY (preference_id) REFERENCES preference (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_preference DROP CONSTRAINT FK_FA0E76BFD81022C0');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458A5BC2E0E');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A5BC2E0E');
        $this->addSql('ALTER TABLE email_checker DROP CONSTRAINT FK_E417671DA76ED395');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458F675F31B');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53BA49DECF0');
        $this->addSql('ALTER TABLE user_preference DROP CONSTRAINT FK_FA0E76BFA76ED395');
        $this->addSql('DROP SEQUENCE email_checker_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE preference_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_preference_id_seq CASCADE');
        $this->addSql('DROP TABLE email_checker');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_preference');
    }
}
