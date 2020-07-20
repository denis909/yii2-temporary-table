<?php

namespace denis909\yii;

use Yii;

abstract class TemporaryTable extends \yii\db\ActiveRecord
{

    public static $tableName;

    public static $createdTemporaryTables = [];

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

    public static function tableName(bool $refresh = false, ?string $sql = null)
    {
        Assert::notEmpty(static::$tableName);

        $createdIndex = array_search(static::$tableName, static::$createdTemporaryTables);

        if (defined('YII_ENV_TEST') && YII_ENV_TEST && (Yii::$app->db->getTableSchema($return, true) === null))
        {
            if ($createdIndex)
            {
                $createdIndex = null;

                unset(static::$createdTemporaryTables[$createdIndex]);
            }
        }

        if ($createdIndex && $refresh)
        {
            static::dropTemporaryTable(static::$tableName);

            $createdIndex = null;

            unset(static::$createdTemporaryTables[$createdIndex]);            
        }

        if (!$createdIndex)
        {
            static::createTemporaryTable(static::$tableName, $sql);

            static::$createdTemporaryTables[] = static::$tableName;
        }

        return static::$tableName;
    }

}