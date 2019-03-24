<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324214315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_preference ADD user_id INT NOT NULL, ADD preference_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFD81022C0 FOREIGN KEY (preference_id) REFERENCES preference (id)');
        $this->addSql('CREATE INDEX IDX_FA0E76BFA76ED395 ON user_preference (user_id)');
        $this->addSql('CREATE INDEX IDX_FA0E76BFD81022C0 ON user_preference (preference_id)');
        $this->addSql('ALTER TABLE trip ADD conductor_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA49DECF0 FOREIGN KEY (conductor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7656F53BA49DECF0 ON trip (conductor_id)');
        $this->addSql('ALTER TABLE reservation ADD trip_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C84955A5BC2E0E ON reservation (trip_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('ALTER TABLE feedback ADD author_id INT NOT NULL, ADD trip_id INT NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('CREATE INDEX IDX_D2294458F675F31B ON feedback (author_id)');
        $this->addSql('CREATE INDEX IDX_D2294458A5BC2E0E ON feedback (trip_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458F675F31B');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A5BC2E0E');
        $this->addSql('DROP INDEX IDX_D2294458F675F31B ON feedback');
        $this->addSql('DROP INDEX IDX_D2294458A5BC2E0E ON feedback');
        $this->addSql('ALTER TABLE feedback DROP author_id, DROP trip_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A5BC2E0E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('DROP INDEX IDX_42C84955A5BC2E0E ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955A76ED395 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP trip_id, DROP user_id');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BA49DECF0');
        $this->addSql('DROP INDEX IDX_7656F53BA49DECF0 ON trip');
        $this->addSql('ALTER TABLE trip DROP conductor_id');
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_FA0E76BFA76ED395');
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_FA0E76BFD81022C0');
        $this->addSql('DROP INDEX IDX_FA0E76BFA76ED395 ON user_preference');
        $this->addSql('DROP INDEX IDX_FA0E76BFD81022C0 ON user_preference');
        $this->addSql('ALTER TABLE user_preference DROP user_id, DROP preference_id');
    }
}
