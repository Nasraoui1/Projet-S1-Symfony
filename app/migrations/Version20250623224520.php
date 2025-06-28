<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250623224520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP SEQUENCE delit_financier_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE delit_fraude_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE delit_vol_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE document_audio_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE document_fichier_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE document_image_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE document_video_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE partenaire_moral_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE partenaire_physique_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE politicien_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lieu (id SERIAL NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(100) NOT NULL, codepostal VARCHAR(10) NOT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, type_etablissement VARCHAR(100) DEFAULT NULL, est_public BOOLEAN DEFAULT NULL, niveau_securite VARCHAR(50) DEFAULT NULL, capacite_accueil INT DEFAULT NULL, horaire_acces VARCHAR(255) DEFAULT NULL, responsable_securite VARCHAR(255) DEFAULT NULL, video_surveillance BOOLEAN DEFAULT NULL, PRIMARY KEY(id))
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
            ALTER TABLE politicien DROP CONSTRAINT fk_d7f73e4d712547c6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_fichier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_audio
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit_vol
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE politicien
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE partenaire_physique
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit_fraude
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delit_financier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE partenaire_moral
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_video
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_image
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
            ALTER TABLE delit ADD CONSTRAINT FK_4F2762E6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire ADD CONSTRAINT FK_4243E7E37E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire ADD CONSTRAINT FK_4243E7E398DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A7660BB6FE6 FOREIGN KEY (auteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A767E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A766042E2D1 FOREIGN KEY (document_parent_id) REFERENCES document (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE parti_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('parti_id_seq', (SELECT MAX(id) FROM parti))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parti ALTER id SET DEFAULT nextval('parti_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649712547C6 FOREIGN KEY (parti_id) REFERENCES parti (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE politicien_delit ADD CONSTRAINT FK_B06A884C7C1FA7B6 FOREIGN KEY (politicien_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE politicien_delit ADD CONSTRAINT FK_B06A884C7E44E39E FOREIGN KEY (delit_id) REFERENCES delit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
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
            CREATE SEQUENCE delit_financier_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE delit_fraude_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE delit_vol_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE document_audio_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE document_fichier_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE document_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE document_video_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE partenaire_moral_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE partenaire_physique_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE politicien_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document_fichier (id SERIAL NOT NULL, type_fichier VARCHAR(50) NOT NULL, format_fichier VARCHAR(10) DEFAULT NULL, nombre_pages INT DEFAULT NULL, est_signe_numeriquement BOOLEAN DEFAULT NULL, signataires JSON DEFAULT NULL, date_signature TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, autorite_signature VARCHAR(255) DEFAULT NULL, numero_document VARCHAR(100) DEFAULT NULL, version_document VARCHAR(20) DEFAULT NULL, document_original BOOLEAN DEFAULT NULL, contenu_extrait TEXT DEFAULT NULL, indexe_recherche BOOLEAN DEFAULT NULL, mots_cles_document JSON DEFAULT NULL, clauses_importantes JSON DEFAULT NULL, montants_mentionnes JSON DEFAULT NULL, personnes_mentionnees JSON DEFAULT NULL, date_validite TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document_audio (id SERIAL NOT NULL, format_audio VARCHAR(10) DEFAULT NULL, duree INT DEFAULT NULL, bitrate INT DEFAULT NULL, frequence_echantillonnage INT DEFAULT NULL, nombre_canaux VARCHAR(10) DEFAULT NULL, qualite_audio VARCHAR(20) DEFAULT NULL, date_enregistrement TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, lieu_enregistrement VARCHAR(255) DEFAULT NULL, materiel_enregistrement VARCHAR(255) DEFAULT NULL, personnes_enregistrees JSON DEFAULT NULL, transcription_texte TEXT DEFAULT NULL, transcription_validee BOOLEAN DEFAULT NULL, langue_principale VARCHAR(10) DEFAULT NULL, mots_cles_audio JSON DEFAULT NULL, niveau_sonore INT DEFAULT NULL, filtres_appliques JSON DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delit_vol (id SERIAL NOT NULL, biens_derobes JSON DEFAULT NULL, valeur_estimee BIGINT DEFAULT NULL, biens_recuperes BOOLEAN DEFAULT NULL, pourcentage_recupere INT DEFAULT NULL, lieu_stockage VARCHAR(255) DEFAULT NULL, methode_derriere_vol TEXT DEFAULT NULL, receleurs JSON DEFAULT NULL, vol_premedite BOOLEAN DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE politicien (id SERIAL NOT NULL, parti_id INT DEFAULT NULL, biographie TEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, date_entree_politique DATE DEFAULT NULL, mandat_actuel VARCHAR(255) DEFAULT NULL, circonscription VARCHAR(255) DEFAULT NULL, salaire_mensuel BIGINT DEFAULT NULL, declaration_patrimoine JSON DEFAULT NULL, casier_judiciaire TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d7f73e4d712547c6 ON politicien (parti_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE partenaire_physique (id SERIAL NOT NULL, prenom VARCHAR(100) NOT NULL, nom_famille VARCHAR(100) NOT NULL, date_naissance DATE DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, nationalite VARCHAR(100) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, numero_secu VARCHAR(15) DEFAULT NULL, numero_cni VARCHAR(20) DEFAULT NULL, situation_familiale VARCHAR(50) DEFAULT NULL, casier_judiciaire BOOLEAN DEFAULT NULL, fortune_estimee BIGINT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delit_fraude (id SERIAL NOT NULL, type_fraude VARCHAR(255) NOT NULL, documents_manipules JSON DEFAULT NULL, nombre_victimes INT DEFAULT NULL, prejudice_estime BIGINT DEFAULT NULL, methode_fraude TEXT NOT NULL, complices_identifies JSON DEFAULT NULL, systeme_informatique BOOLEAN DEFAULT NULL, fraude_organisee BOOLEAN DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN delit_fraude.methode_fraude IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delit_financier (id SERIAL NOT NULL, montant_estime BIGINT DEFAULT NULL, dedevise VARCHAR(255) NOT NULL, methode_paiement VARCHAR(100) DEFAULT NULL, compte_bancaire VARCHAR(50) DEFAULT NULL, paradiss_fiscal VARCHAR(255) DEFAULT NULL, "blanchiment_soupçonne" BOOLEAN DEFAULT NULL, institutions_impliquees JSON DEFAULT NULL, circuit_financier TEXT DEFAULT NULL, montant_recupere BIGINT DEFAULT NULL, argent_recupere BOOLEAN DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE partenaire_moral (id SERIAL NOT NULL, raison_sociale VARCHAR(255) NOT NULL, forme_juridique VARCHAR(50) NOT NULL, siret VARCHAR(14) DEFAULT NULL, secteur_activite VARCHAR(255) DEFAULT NULL, dirigeant_principal VARCHAR(255) DEFAULT NULL, chiffre_affaires BIGINT DEFAULT NULL, nombre_employes INT DEFAULT NULL, pays_fiscal VARCHAR(100) DEFAULT NULL, date_creation_entreprise DATE DEFAULT NULL, capital_social BIGINT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document_video (id SERIAL NOT NULL, format_video VARCHAR(10) DEFAULT NULL, duree INT DEFAULT NULL, resolution VARCHAR(20) NOT NULL, frame_rate INT DEFAULT NULL, codec VARCHAR(50) DEFAULT NULL, qualite_video VARCHAR(10) DEFAULT NULL, avec_son BOOLEAN DEFAULT NULL, sous_titres BOOLEAN DEFAULT NULL, langue_audio VARCHAR(10) DEFAULT NULL, date_enregistrement TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, lieu_enregistrement VARCHAR(255) DEFAULT NULL, personnes_filmees JSON DEFAULT NULL, timestamps_importants JSON DEFAULT NULL, thumbnail_path VARCHAR(500) DEFAULT NULL, url_streaming_externe VARCHAR(500) DEFAULT NULL, plateforme_hebergement VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document_image (id SERIAL NOT NULL, format_image VARCHAR(10) DEFAULT NULL, largeur INT DEFAULT NULL, hauteur INT DEFAULT NULL, resolution INT DEFAULT NULL, date_photo TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, lieu_photo VARCHAR(255) DEFAULT NULL, thumbnail_path VARCHAR(50) DEFAULT NULL, personnes_identifiees JSON DEFAULT NULL, appareil_photo VARCHAR(255) DEFAULT NULL, coordonnees_gps VARCHAR(50) DEFAULT NULL, est_retouchee BOOLEAN DEFAULT NULL, logiciel_retouche VARCHAR(255) DEFAULT NULL, metadonnees_exif JSON DEFAULT NULL, qualite_image VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE politicien ADD CONSTRAINT fk_d7f73e4d712547c6 FOREIGN KEY (parti_id) REFERENCES parti (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lieu
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649712547C6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE politicien_delit DROP CONSTRAINT FK_B06A884C7C1FA7B6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE politicien_delit DROP CONSTRAINT FK_B06A884C7E44E39E
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
            ALTER TABLE document DROP CONSTRAINT FK_D8698A7660BB6FE6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP CONSTRAINT FK_D8698A767E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP CONSTRAINT FK_D8698A766042E2D1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire DROP CONSTRAINT FK_4243E7E37E44E39E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delit_partenaire DROP CONSTRAINT FK_4243E7E398DE13AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parti ALTER id DROP DEFAULT
        SQL);
    }
}
