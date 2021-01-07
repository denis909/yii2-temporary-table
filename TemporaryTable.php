<?php

namespace denis909\yii;

abstract class TemporaryTable extends \yii\db\ActiveRecord
{

    use TemporaryTableTrait;

    public static $tableName;

    public static $indexPrefix = '';

    public static $indexSuffix = '';

}