<?php

namespace models;

use core\Model;
use core\Core;

class Products extends Model
{
    public static $tableName = 'products';
    protected static $primaryKey = 'ProductID';


    public static function getAllProducts()
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                ORDER BY p.ProductName";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }


    public static function getProductsByCategory($categoryId)
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                WHERE p.CategoryID = :categoryId 
                ORDER BY p.ProductName";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':categoryId', $categoryId);
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function getProductsByCategoryWithFilters($categoryId, $search = '', $priceMin = null, $priceMax = null, $sort = '')
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                WHERE p.CategoryID = :categoryId";

        $params = [':categoryId' => $categoryId];

        if (!empty($search)) {
            $sql .= " AND (p.ProductName LIKE :search OR p.DescriptionProduct LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($priceMin) && is_numeric($priceMin)) {
            $sql .= " AND p.Price >= :priceMin";
            $params[':priceMin'] = $priceMin;
        }

        if (!empty($priceMax) && is_numeric($priceMax)) {
            $sql .= " AND p.Price <= :priceMax";
            $params[':priceMax'] = $priceMax;
        }

        switch ($sort) {
            case 'asc':
                $sql .= " ORDER BY p.ProductName ASC";
                break;
            case 'desc':
                $sql .= " ORDER BY p.ProductName DESC";
                break;
            case 'price_asc':
                $sql .= " ORDER BY p.Price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.Price DESC";
                break;
            case 'stock_asc':
                $sql .= " ORDER BY p.StockQuantity ASC";
                break;
            case 'stock_desc':
                $sql .= " ORDER BY p.StockQuantity DESC";
                break;
            default:
                $sql .= " ORDER BY p.ProductName ASC";
                break;
        }

        $sth = Core::get()->db->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $sth->bindValue($key, $value);
        }

        $sth->execute();
        return $sth->fetchAll();
    }


    public static function getProductById($id)
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                WHERE p.ProductID = :id";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':id', $id);
        $sth->execute();
        $result = $sth->fetchAll();

        return !empty($result) ? $result[0] : null;
    }


    public static function searchProducts($searchTerm)
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                WHERE p.ProductName LIKE :searchTerm 
                   OR p.DescriptionProduct LIKE :searchTerm 
                ORDER BY p.ProductName";

        $sth = Core::get()->db->pdo->prepare($sql);
        $searchTerm = '%' . $searchTerm . '%';
        $sth->bindValue(':searchTerm', $searchTerm);
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function createProduct($data)
    {
        return Core::get()->db->insert(self::$tableName, $data);
    }


    public static function updateProduct($id, $data)
    {
        return Core::get()->db->update(self::$tableName, $data, ['ProductID' => $id]);
    }


    public static function deleteProduct($id)
    {
        return Core::get()->db->delete(self::$tableName, ['ProductID' => $id]);
    }


    public static function getLowStockProducts($threshold = 10)
    {
        $sql = "SELECT p.*, c.CategoryName 
                FROM products p 
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
                WHERE p.StockQuantity <= :threshold 
                ORDER BY p.StockQuantity ASC";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':threshold', $threshold);
        $sth->execute();
        return $sth->fetchAll();
    }


    public static function getProductCountByCategory($categoryId)
    {
        $sql = "SELECT COUNT(*) as count FROM products WHERE CategoryID = :categoryId";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':categoryId', $categoryId);
        $sth->execute();
        $result = $sth->fetch();

        return $result ? $result['count'] : 0;
    }
}