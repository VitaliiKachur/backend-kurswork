<?php
namespace models;

use core\Model;
use core\Core;

class Order extends Model
{
    public static $tableName = 'orders';

    public static function createOrder($orderData, $items)
    {
        try {
            Core::get()->db->pdo->beginTransaction();
            $orderId = Core::get()->db->insert(self::$tableName, $orderData);

            foreach ($items as $item) {
                $orderItem = [
                    'OrderID' => $orderId,
                    'ProductID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Price' => $item['Price']
                ];
                OrderItem::addItem($orderItem);

                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $currentStock = Core::get()->db->select('products', ['StockQuantity'], ['ProductID' => $productId])[0]['StockQuantity'];
                $newStock = $currentStock - $quantity;
                Core::get()->db->update('products', ['StockQuantity' => $newStock], ['ProductID' => $productId]);
            }

            Core::get()->db->pdo->commit();
            return $orderId;
        } catch (\Exception $e) {
            Core::get()->db->pdo->rollBack();
            return false;
        }
    }
}