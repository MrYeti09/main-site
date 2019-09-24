<?php

use Illuminate\Database\Seeder;
use Viaativa\Viaroot\Traits\DatabaseDataRow;
use Viaativa\Viaroot\Traits\DatabaseDataRowRelationship;

class DataRowsSeeder extends Seeder
{
    use DatabaseDataRow, DatabaseDataRowRelationship;

    public function run()
    {
        $this->iconSeeder();
        $this->pageIcons();
    }

    public function pageIcons(){
        $this->defineDataType('pages');
        $this->addDataRow('icon', 'font-icon', 'Ícone', 0, 0, 1, 1, 1, 1, '');
    }

    private function iconSeeder()
    {
        $this->defineDataType('icons');
        $this->addDataRow('id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '');
        $this->addDataRow('name', 'text', 'Nome', 1, 1, 1, 1, 1, 1, '');
        $this->addDataRow('slug', 'text', 'Slug', 0, 0, 0, 0, 0, 0, '');
        $this->addDataRow('icons', 'text', 'Ícones', 0, 1, 0, 0, 0, 0, '');
        $this->addDataRow('created_at', 'timestamp', 'Data Criação', 0, 0, 0, 0, 0, 0, '');
        $this->addDataRow('updated_at', 'timestamp', 'Data Atualização', 0, 0, 0, 0, 0, 0, '');
    }
}
