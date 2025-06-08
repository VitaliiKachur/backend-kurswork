<?php
namespace models;

use core\Model;
use core\Core;

class OrderItem extends Model
{
    public static $tableName = 'order_items';

    public static function addItem($itemData)
    {
        return Core::get()->db->insert(self::$tableName, $itemData);
    }
}