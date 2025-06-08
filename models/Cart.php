<?php
namespace models;

use core\Model;
use core\Core;

class Cart extends Model
{
    public static $tableName = 'cart';

    public static function getCartItems($userId)
    {
        $sql = "SELECT c.*, p.ProductName, p.Price, p.ImageURL, p.StockQuantity 
                FROM cart c
                JOIN products p ON c.product_id = p.ProductID
                WHERE c.user_id = :userId";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':userId', $userId);
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function getCartItemById($itemId)
    {
        $sql = "SELECT c.*, p.ProductName, p.Price, p.ImageURL, p.StockQuantity 
                FROM cart c
                JOIN products p ON c.product_id = p.ProductID
                WHERE c.id = :itemId";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':itemId', $itemId);
        $sth->execute();
        return $sth->fetch();
    }

    public static function getCartTotal($userId)
    {
        $sql = "SELECT SUM(p.Price * c.quantity) as total
                FROM cart c
                JOIN products p ON c.product_id = p.ProductID
                WHERE c.user_id = :userId";

        $sth = Core::get()->db->pdo->prepare($sql);
        $sth->bindValue(':userId', $userId);
        $sth->execute();
        $result = $sth->fetch();
        return $result['total'] ?? 0;
    }

    public static function updateQuantity($itemId, $quantity)
    {
        if ($quantity <= 0) {
            return self::deleteById($itemId);
        }
        return Core::get()->db->update(
            self::$tableName,
            ['quantity' => $quantity],
            ['id' => $itemId]
        );
    }

    public static function addToCart($userId, $productId, $quantity = 1)
    {
        $existing = self::findByCondition([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        if (!empty($existing)) {
            return Core::get()->db->update(
                self::$tableName,
                ['quantity' => $existing[0]['quantity'] + $quantity],
                ['id' => $existing[0]['id']]
            );
        } else {
            return Core::get()->db->insert(self::$tableName, [
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    public static function removeFromCart($itemId)
    {
        return self::deleteById($itemId);
    }

    public static function getCartCount($userId)
    {
        $result = Core::get()->db->select(
            self::$tableName,
            ['SUM(quantity) as total'],
            ['user_id' => $userId]
        );
        return $result[0]['total'] ?? 0;
    }

  
    public static function clearCart($userId)
{
    return self::deleteByCondition(['user_id' => $userId]);
}
}