<?php
namespace models;

use core\Model;
use core\Core;

class Categories extends Model
{
    public static $tableName = 'categories';
    protected static $primaryKey = 'CategoryID';
    

    public static function getAllCategories()
    {
        return Core::get()->db->select(self::$tableName);
    }
    

    public static function getCategoryById($id)
    {
        $result = Core::get()->db->select(self::$tableName, '*', ['CategoryID' => $id]);
        return !empty($result) ? $result[0] : null;
    }
    
    
    public static function getCategoryByName($name)
    {
        $result = Core::get()->db->select(self::$tableName, '*', ['CategoryName' => $name]);
        return !empty($result) ? $result[0] : null;
    }
    

    public static function createCategory($name, $description = null, $image = null)
    {
        $data = [
            'CategoryName' => $name,
            'CategoryDescription' => $description
        ];
        if ($image !== null) {
            $data['CategoryImage'] = $image;
        }
        return Core::get()->db->insert(self::$tableName, $data);
    }
    
 
    public static function updateCategory($id, $data)
    {
        return Core::get()->db->update(self::$tableName, $data, ['CategoryID' => $id]);
    }
    
  
    public static function deleteCategory($id)
    {
        return Core::get()->db->delete(self::$tableName, ['CategoryID' => $id]);
    }
}