<?php

namespace denis909\yii;

use Yii;

abstract class TemporaryTable extends \yii\db\ActiveRecord
{

    public static $tableName;

    public static $indexPrefix = '';

    public static $indexSuffix = '';

    public static $createdTemporaryTables = [];

    public static abstract function createTable(string $tableName);

    public static function createTemporaryTable(string $tableName, string $sql)
    {
        $sql = 'CREATE TEMPORARY TABLE ' . $tableName . ' ' . $sql;

        return Yii::$app->db->createCommand($sql)->execute();
    }

    public static function dropTemporaryTable(string $tableName)
    {
        $sql = 'DROP TEMPORARY TABLE ' . $tableName;

        return Yii::$app->db->createCommand($sql)->execute();
    }

    public static function tableName(bool $refresh = false)
    {
        Assert::notEmpty(static::$tableName);

        $createdIndex = array_search(static::$tableName, static::$createdTemporaryTables);

        if (defined('YII_ENV_TEST') && YII_ENV_TEST && (Yii::$app->db->getTableSchema($return, true) === null))
        {
            if ($createdIndex !== false)
            {
                $createdIndex = false;

                unset(static::$createdTemporaryTables[$createdIndex]);
            }
        }

        if (($createdIndex !== false) && $refresh)
        {
            static::dropTemporaryTable(static::$tableName);

            $createdIndex = false;

            unset(static::$createdTemporaryTables[$createdIndex]);            
        }

        if ($createdIndex === false)
        {
            static::createTable(static::$tableName);

            static::$createdTemporaryTables[] = static::$tableName;
        }

        return static::$tableName;
    }

    public static function createIndex(string $indexName, string $tableName, $columns = [], $unique = false)
    {
        $indexName = static::$indexPrefix . $indexName . static::$indexSuffix;

        Yii::$app->db->createCommand()->createIndex($indexName, $tableName, $columns, $unique)->execute();
    }

}