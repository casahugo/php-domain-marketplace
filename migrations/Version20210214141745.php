<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210214141745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add shipping, tax, gallery, document table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create table shipping
            (
                code varchar(50) not null,
                name varchar(255) not null,
                price decimal (12, 2) not null,
                constraint shipping_pk primary key (code)
            );

            create unique index shipping_code_uindex on shipping (code);

            alter table product add shipping_code varchar(50) null;
            alter table product add constraint product_shipping_code_fk foreign key (shipping_code) references shipping (code);
        SQL);

        $this->addSql(<<<SQL
            create table tax
            (
                code varchar(50) not null,
                name varchar(255) not null,
                amount decimal (12, 2) not null,
                constraint tax_pk primary key (code)
            );

            create unique index tax_code_uindex on tax (code);
        SQL);

        $this->addSql(<<<SQL
            create table product_tax
            (
                product_reference varchar(26) not null,
                tax_code varchar(50) not null,
                constraint product_tax_pk primary key (product_reference, tax_code)
            );

            create unique index product_tax__uindex on shipping (product_reference, tax_code);
        SQL);

        $this->addSql(<<<SQL
            create table product_gallery
            (
                id varchar(26) not null,
                product_reference varchar(26) not null,
                name varchar(250),
                path varchar(250),
                constraint product_gallery_pk primary key (id)
            );

            create unique index product_gallery__uindex on shipping (id, product_reference);
        SQL);

        $this->addSql(<<<SQL
            create table product_document
            (
                id varchar(26) not null,
                product_reference varchar(26) not null,
                name varchar(250),
                path varchar(250),
                constraint product_document_pk primary key (id)
            );

            create unique index product_document__uindex on shipping (id, product_reference);
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
