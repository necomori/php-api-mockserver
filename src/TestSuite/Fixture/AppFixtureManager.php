<?php
/**
 * Copyright (c) necomori LLC (http://necomori.asia)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright (c) necomori LLC (http://necomori.asia)
 * @since      0.1.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\TestSuite\Fixture;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionInterface;
use Cake\Datasource\ConnectionManager;
use Cake\DataSource\Exception\MissingDatasourceConfigException;
use Cake\TestSuite\Fixture\FixtureManager;
use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;
use UnexpectedValueException;

/**
 * Core の FixtureManager を拡張
 *
 * @package App\Test\Fixture
 */
class AppFixtureManager extends FixtureManager
{
    protected $faker;

    /**
     * FixtureManager の loadSingle の挙動を変更する
     *
     * @param string $nameAndFilename of the fixture
     * @param ConnectionInterface|null $db Connection instance or leave null to get a Connection from the fixture
     * @param bool $dropTables Whether or not tables should be dropped and re-created.
     * @return void
     * @throws UnexpectedValueException if $name is not a previously loaded class
     * @throws MissingDatasourceConfigException
     */
    public function loadSingle($nameAndFilename, $db = null, $dropTables = true)
    {
        $filename = null;
        if (strpos($nameAndFilename, ':') !== false) {
            list ($name, $filename) = explode(':', $nameAndFilename);
        } else {
            $name = $nameAndFilename;
        }

        if (!isset($this->_fixtureMap[$name])) {
            throw new UnexpectedValueException(__('Referenced fixture class {0} not found', $name));
        }

        /** @var TestFixture $fixture */
        $fixture = $this->_fixtureMap[$name];

        /** @var Connection $db */
        if (!$db) {
            $db = ConnectionManager::get($fixture->connection());
        }

        if ($this->faker === null) {
            $this->faker = Factory::create('ja_JP');
        }

        $fixture->records = [];

        if ($filename) {
            $fixture->records = $this->loadFixtureData($filename);
        }

        if (!$this->isFixtureSetup($db->configName(), $fixture)) {
            $sources = $db->getSchemaCollection()->listTables();
            $this->_setupTable($fixture, $db, $sources, $dropTables);
        }

        if (!$dropTables) {
            $fixture->dropConstraints($db);
            $fixture->truncate($db);
        }

        $fixture->createConstraints($db);
        $fixture->insert($db);
    }

    /**
     * 指定されたファイルを読み込んで、Fixture として使うデータを返却する
     *
     * @param string $filename ファイル名
     * @return array
     * @throws UnexpectedValueException
     */
    protected function loadFixtureData($filename)
    {
        $fileinfo = pathinfo($filename);
        $extension = strtolower($fileinfo['extension']);
        $method = sprintf('loadFrom%s', ucfirst($extension));
        if (!method_exists($this, $method)) {
            throw new UnexpectedValueException(__('Not exists - {0}', $method));
        }
        $filename = sprintf('%s/tests/Fixture/Data/%s', dirname(dirname(dirname(__DIR__))), $filename);
        if (!file_exists($filename)) {
            throw new UnexpectedValueException(__('Not found - {0}', $filename));
        }

        return $this->$method($filename);
    }

    /**
     * CSVファイルを読み込んで、Fixtureクラスで使用できる形にする
     *
     * @param string $filename ファイル名
     * @return array
     * @throws UnexpectedValueException
     */
    protected function loadFromCsv($filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false) {
            throw new UnexpectedValueException(__('Failed open file - {0}', $filename));
        }
        $line = rtrim(fgets($fp));
        $fields = explode(',', $line);
        $records = [];
        while (($data = fgetcsv($fp, 8192)) !== false) {
            $record = [];
            foreach ($data as $k => $v) {
                $record[$fields[$k]] = $v;
            }
            $records[] = $record;
        }
        fclose($fp);

        return $records;
    }

    /**
     * TSVファイルを読み込んで、Fixtureクラスで使用できる形にする
     *
     * @param string $filename ファイル名
     * @return array
     * @throws UnexpectedValueException
     */
    protected function loadFromTsv($filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false) {
            throw new UnexpectedValueException(__('Failed open file - {0}', $filename));
        }
        $line = rtrim(fgets($fp));
        $fields = explode("\t", $line);
        $records = [];
        while (($data = fgetcsv($fp, 8192, "\t")) !== false) {
            $record = [];
            foreach ($data as $k => $v) {
                $record[$fields[$k]] = $v;
            }
            $records[] = $record;
        }
        fclose($fp);

        return $records;
    }

    /**
     * phpファイルを読み込んで、Fixtureクラスで使用できる形にする
     *
     * @param string $filename ファイル名
     * @return array
     */
    protected function loadFromPhp($filename)
    {
        $loader = include $filename;

        return $loader($this->faker);
    }
}
