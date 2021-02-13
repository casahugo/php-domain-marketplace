<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210208171734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relational tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create table category
            (
                code varchar(50) not null,
                name varchar(255) not null,
                constraint category_pk primary key (code)
            );

            create unique index category_code_uindex on category (code);
        SQL);

        $this->addSql(<<<SQL
            create table brand
            (
                code varchar(50) not null,
                name varchar(255) not null,
                constraint category_pk primary key (code)
            );

            create unique index brand_code_uindex on brand (code);
        SQL);

        $this->addSql(<<<SQL
            create table company
            (
                id varchar(26) not null,
                email varchar(255) not null,
                name varchar(255) not null
            );

            create unique index company_email_uindex on seller (email);
            create unique index company_id_uindex on seller (id);
            alter table company add constraint company_pk primary key (id);
        SQL);

        $this->addSql(<<<SQL
            create table product
            (
                reference varchar(26) not null,
                code varchar(50) not null,
                name varchar(255) not null,
                price decimal(12,2) not null,
                original_price decimal(12,2) null,
                stock int null,
                status varchar(50) null,
                intro mediumtext null,
                description mediumtext null,
                created_at datetime not null,
                updated_at datetime null,
                brand_code varchar(50) null,
                category_code varchar(50) not null,
                company_id varchar(26) not null
            );

            create unique index product_code_uindex on product (code);
            create unique index product_reference_uindex on product (reference);
            alter table product add constraint product_pk primary key (reference);
            alter table product add constraint product_category_code_fkforeign key (category_code) references category (code);
            alter table product add constraint product_company_id_fkforeign key (company_id) references company (code);
            alter table product add constraint product_brand_code_fkforeign key (brand_code) references brand (code);
        SQL);
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
