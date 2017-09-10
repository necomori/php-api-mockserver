<?php

use Migrations\AbstractMigration;

/**
 * resources テーブルを追加
 */
class CreateResources extends AbstractMigration
{
    /**
     * Change Method.
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function change()
    {
        $this->table('resources')
            ->addColumn('url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('method', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('response', 'text', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('count', 'integer', [
                'default' => 0,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->create();
    }
}
