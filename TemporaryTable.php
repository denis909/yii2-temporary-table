<?php

namespace denis909\yii;

use Yii;

abstract class TemporaryTable extends \yii\db\ActiveRecord
{

    public static $tableName;

    public static $indexPrefix = '';

    public static $indexSuffix = '';

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
        assert(static::$tableName ? true : false, __CLASS__ . '::$tableName');

        $schema = Yii::$app->db->getTableSchema(static::$tableName, true);

        if ($schema && $refresh)
        {
            static::dropTemporaryTable(static::$tableName);

            $schema = null;
        }

        if (!$schema)
        {
            static::createTable(static::$tableName);
        }

        return static::$tableName;
    }

    public static function createIndex(string $name, string $tableName, $columns = [], $unique = false)
    {
        $name = static::$indexPrefix . $name . static::$indexSuffix;

        Yii::$app->db->createCommand()->createIndex($name, $tableName, $columns, $unique)->execute();
    }

}