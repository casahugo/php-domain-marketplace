<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210206115956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add product readmodel';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<SQL
            create table product_readmodel
            (
                reference   varchar(26) not null,
                data longtext    not null,
                constraint product_reference_uindex
                    unique (reference)
            );

            alter table product_readmodel
                add primary key (reference);
        SQL);
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function down(Schema $schema) : void
    {
    }
}
