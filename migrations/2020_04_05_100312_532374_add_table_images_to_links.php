<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddTableImagesToLinks20200405100312532374 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $sql = '
        CREATE TABLE images_to_links (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            FILE_ID INT(18) NOT NULL,
            ELEMENT_ID INT(11) NOT NULL,
            ADDRESS VARCHAR(255),
            IS_MAIN TINYINT NOT NULL,
            FOREIGN KEY (FILE_ID)
                REFERENCES b_file (ID)
                ON DELETE CASCADE,
            FOREIGN KEY (ELEMENT_ID)
                REFERENCES b_iblock_element (ID)
                ON DELETE CASCADE,
            INDEX file_id_idx (FILE_ID),
            INDEX element_id_idx (ELEMENT_ID)
        )  ENGINE=INNODB;
        ';

        $this->db->query($sql);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function down()
    {
        $this->db->query('drop table images_to_links');
    }
}
