<?php
namespace models;

use core\Model;

class Category extends Model
{
    public static $tableName = 'categories';
    protected static $primaryKey = 'CategoryID';
    
    public static function getAllCategories()
    {
        return self::findByCondition([]);
    }
    
    public static function getCategoryById($id)
    {
        return self::findById($id);
    }
}