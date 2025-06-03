<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250602122546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE delit (id SERIAL NOT NULL, lieu_id INT DEFAULT NULL, type TEXT NOT NULL, description TEXT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F2762E6AB213CC ON delit (lieu_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN delit.type IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN delit.date IS '(DC2Type:date_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delit_partenaire (delit_id INT NOT NULL, partenaire_id INT NOT NULL, PRIMARY KEY(delit_id, partenaire_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4243E7E37E44E39E ON delit_partenaire (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4243E7E398DE13AC ON delit_partenaire (partenaire_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lieu (id SERIAL NOT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) NOT NULL, code_postal VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE paiement (id SERIAL NOT NULL, delit_id INT DEFAULT NULL, montant INT NOT NULL, devise VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B1DC7A1E7E44E39E ON paiement (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN paiement.date IS '(DC2Type:date_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE partenaire (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE preuve (id SERIAL NOT NULL, delit_id INT DEFAULT NULL, description TEXT NOT NULL, fichier VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_238DF9E07E44E39E ON preuve (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit ADD CONSTRAINT FK_4F2762E6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire ADD CONSTRAINT FK_4243E7E37E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire ADD CONSTRAINT FK_4243E7E398DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preuve ADD CONSTRAINT FK_238DF9E07E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit DROP CONSTRAINT FK_4F2762E6AB213CC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire DROP CONSTRAINT FK_4243E7E37E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire DROP CONSTRAINT FK_4243E7E398DE13AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E7E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preuve DROP CONSTRAINT FK_238DF9E07E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit_partenaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lieu
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE paiement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE partenaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE preuve
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
