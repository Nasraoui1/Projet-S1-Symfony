<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250602125852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP SEQUENCE preuve_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commentaire (id SERIAL NOT NULL, auteur_id INT DEFAULT NULL, delit_id INT DEFAULT NULL, commentaire_parent_id INT DEFAULT NULL, contenu TEXT NOT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_modification TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BC60BB6FE6 ON commentaire (auteur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BC7E44E39E ON commentaire (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BCFDED4547 ON commentaire (commentaire_parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN commentaire.date_creation IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN commentaire.date_modification IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delit_user (delit_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(delit_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_323FAACD7E44E39E ON delit_user (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_323FAACDA76ED395 ON delit_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFDED4547 FOREIGN KEY (commentaire_parent_id) REFERENCES commentaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_user ADD CONSTRAINT FK_323FAACD7E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_user ADD CONSTRAINT FK_323FAACDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preuve DROP CONSTRAINT fk_238df9e07e44e39e
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE preuve
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE preuve_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE preuve (id SERIAL NOT NULL, delit_id INT DEFAULT NULL, description TEXT NOT NULL, fichier VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_238df9e07e44e39e ON preuve (delit_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preuve ADD CONSTRAINT fk_238df9e07e44e39e FOREIGN KEY (delit_id) REFERENCES delit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BC60BB6FE6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BC7E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BCFDED4547
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_user DROP CONSTRAINT FK_323FAACD7E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_user DROP CONSTRAINT FK_323FAACDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commentaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit_user
        SQL);
    }
}
