<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511214450 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buildings_communal_services DROP CONSTRAINT FK_ADD9CC724D2A7E12');
        $this->addSql('ALTER TABLE c_exchanges DROP CONSTRAINT FK_3EA3B59D4D2A7E12');
        $this->addSql('ALTER TABLE rooms DROP CONSTRAINT FK_7CA11A964D2A7E12');
        $this->addSql('ALTER TABLE meters DROP CONSTRAINT FK_2A3E40E8544D5EE1');
        $this->addSql('ALTER TABLE buildings_communal_services DROP CONSTRAINT FK_ADD9CC723B06F6AE');
        $this->addSql('ALTER TABLE meters_indications DROP CONSTRAINT FK_A4E1BCA86E15CA9E');
        $this->addSql('ALTER TABLE buildings DROP CONSTRAINT FK_9A51B6A732C8A3DE');
        $this->addSql('ALTER TABLE rooms_accounts DROP CONSTRAINT FK_2DC0D8FB54177093');
        $this->addSql('ALTER TABLE meters DROP CONSTRAINT FK_2A3E40E8AF992FA6');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE buildings_communal_services');
        $this->addSql('DROP TABLE c_exchanges');
        $this->addSql('DROP TABLE communal_services');
        $this->addSql('DROP TABLE meters');
        $this->addSql('DROP TABLE meters_indications');
        $this->addSql('DROP TABLE organizations');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE rooms_accounts');
        $this->addSql('DROP TABLE messenger_messages');
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buildings (id UUID NOT NULL, organization_id UUID DEFAULT NULL, address VARCHAR(255) NOT NULL, balance INT NOT NULL, fias_id VARCHAR(180) NOT NULL, registration_code VARCHAR(12) NOT NULL, status INT NOT NULL, time_offset VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A51B6A71E29B59E ON buildings (fias_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A51B6A7B82B2744 ON buildings (registration_code)');
        $this->addSql('CREATE INDEX IDX_9A51B6A732C8A3DE ON buildings (organization_id)');
        $this->addSql('COMMENT ON COLUMN buildings.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN buildings.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE buildings_communal_services (id UUID NOT NULL, building_id UUID DEFAULT NULL, communal_service_id UUID DEFAULT NULL, external_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ADD9CC724D2A7E12 ON buildings_communal_services (building_id)');
        $this->addSql('CREATE INDEX IDX_ADD9CC723B06F6AE ON buildings_communal_services (communal_service_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADD9CC724D2A7E123B06F6AE ON buildings_communal_services (building_id, communal_service_id)');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.building_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.communal_service_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.external_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN buildings_communal_services.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE c_exchanges (id UUID NOT NULL, building_id UUID DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EA3B59D4D2A7E12 ON c_exchanges (building_id)');
        $this->addSql('CREATE INDEX IDX_3EA3B59D4D2A7E128B8E8428 ON c_exchanges (building_id, created_at)');
        $this->addSql('COMMENT ON COLUMN c_exchanges.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN c_exchanges.building_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN c_exchanges.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN c_exchanges.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE communal_services (id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CEEB2372B36786B ON communal_services (title)');
        $this->addSql('COMMENT ON COLUMN communal_services.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN communal_services.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN communal_services.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE meters (id UUID NOT NULL, building_communal_service_id UUID DEFAULT NULL, room_account_id UUID DEFAULT NULL, external_id UUID NOT NULL, indications_count INT NOT NULL, status INT NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2A3E40E8544D5EE1 ON meters (building_communal_service_id)');
        $this->addSql('CREATE INDEX IDX_2A3E40E8AF992FA6 ON meters (room_account_id)');
        $this->addSql('CREATE INDEX IDX_2A3E40E843625D9F ON meters (updated_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A3E40E8544D5EE19F75D7B0 ON meters (building_communal_service_id, external_id)');
        $this->addSql('COMMENT ON COLUMN meters.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters.building_communal_service_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters.room_account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters.external_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN meters.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE meters_indications (id UUID NOT NULL, meter_id UUID DEFAULT NULL, day_indication DOUBLE PRECISION DEFAULT NULL, month_of_the_period INT NOT NULL, night_indication DOUBLE PRECISION DEFAULT NULL, peak_indication DOUBLE PRECISION DEFAULT NULL, period TIMESTAMP(0) WITH TIME ZONE NOT NULL, year_of_the_period INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A4E1BCA86E15CA9E ON meters_indications (meter_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A4E1BCA86E15CA9EBD0891F976FA688E ON meters_indications (meter_id, year_of_the_period, month_of_the_period)');
        $this->addSql('COMMENT ON COLUMN meters_indications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters_indications.meter_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN meters_indications.period IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN meters_indications.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN meters_indications.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE organizations (id UUID NOT NULL, balance INT NOT NULL, c_exchange_token VARCHAR(64) NOT NULL, inn VARCHAR(10) NOT NULL, status SMALLINT NOT NULL, title VARCHAR(200) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_427C1C7FE93323CB ON organizations (inn)');
        $this->addSql('COMMENT ON COLUMN organizations.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organizations.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN organizations.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE rooms (id UUID NOT NULL, building_id UUID DEFAULT NULL, code INT NOT NULL, external_id UUID NOT NULL, suffix VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CA11A964D2A7E12 ON rooms (building_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CA11A964D2A7E129F75D7B0 ON rooms (building_id, external_id)');
        $this->addSql('COMMENT ON COLUMN rooms.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms.building_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms.external_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rooms.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE rooms_accounts (id UUID NOT NULL, room_id UUID DEFAULT NULL, external_id UUID NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2DC0D8FB54177093 ON rooms_accounts (room_id)');
        $this->addSql('CREATE INDEX IDX_2DC0D8FB43625D9F ON rooms_accounts (updated_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DC0D8FB541770939F75D7B0 ON rooms_accounts (room_id, external_id)');
        $this->addSql('COMMENT ON COLUMN rooms_accounts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms_accounts.room_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms_accounts.external_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rooms_accounts.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rooms_accounts.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('ALTER TABLE buildings ADD CONSTRAINT FK_9A51B6A732C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE buildings_communal_services ADD CONSTRAINT FK_ADD9CC724D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE buildings_communal_services ADD CONSTRAINT FK_ADD9CC723B06F6AE FOREIGN KEY (communal_service_id) REFERENCES communal_services (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE c_exchanges ADD CONSTRAINT FK_3EA3B59D4D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meters ADD CONSTRAINT FK_2A3E40E8544D5EE1 FOREIGN KEY (building_communal_service_id) REFERENCES buildings_communal_services (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meters ADD CONSTRAINT FK_2A3E40E8AF992FA6 FOREIGN KEY (room_account_id) REFERENCES rooms_accounts (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meters_indications ADD CONSTRAINT FK_A4E1BCA86E15CA9E FOREIGN KEY (meter_id) REFERENCES meters (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A964D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rooms_accounts ADD CONSTRAINT FK_2DC0D8FB54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
