<?php
namespace controllers;

use core\Controller;
use models\Cart;
use models\Products;
use core\Core;
use models\Order;
class CartController extends Controller
{
    public function actionIndex()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            $this->addErrorMessage('Будь ласка, увійдіть для перегляду кошика');
            return $this->redirect('/site/profile/login');
        }

        $items = Cart::getCartItems($customer['CustomerID']);
        $total = Cart::getCartTotal($customer['CustomerID']);

        $this->template->setParams([
            'items' => $items,
            'total' => $total,
            'title' => 'Кошик'
        ]);

        return $this->render();
    }

    public function actionAdd()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            return $this->returnJson(['success' => false, 'message' => 'Будь ласка, увійдіть']);
        }

        $productId = $this->get->product_id ?? null;
        if (empty($productId)) {
            return $this->returnJson(['success' => false, 'message' => 'Не вказано товар']);
        }

        $product = Products::getProductById($productId);
        if (!$product) {
            return $this->returnJson(['success' => false, 'message' => 'Товар не знайдено']);
        }

        $customerId = $customer['CustomerID'];
        $currentCartItem = Cart::findByCondition([
            'user_id' => $customerId,
            'product_id' => $productId
        ]);

        $currentQuantity = !empty($currentCartItem) ? $currentCartItem[0]['quantity'] : 0;
        $requestedQuantity = $this->get->quantity ?? 1;
        $newQuantity = $currentQuantity + $requestedQuantity;

        if ($newQuantity > $product['StockQuantity']) {
            return $this->returnJson([
                'success' => false, 
                'message' => 'Неможливо додати більше товару, ніж є в наявності. Доступно: ' . $product['StockQuantity']
            ]);
        }

        Cart::addToCart($customerId, $productId, $requestedQuantity);

        $count = Cart::getCartCount($customerId);
        return $this->returnJson(['success' => true, 'count' => $count]);
    }

    public function actionRemove()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            return $this->returnJson(['success' => false, 'message' => 'Будь ласка, увійдіть']);
        }

        $itemId = $this->get->item_id ?? null;
        if (empty($itemId)) {
            return $this->returnJson(['success' => false, 'message' => 'Не вказано елемент кошика']);
        }

        Cart::removeFromCart($itemId);

        $count = Cart::getCartCount($customer['CustomerID']);
        $total = Cart::getCartTotal($customer['CustomerID']);

        return $this->returnJson([
            'success' => true,
            'count' => $count,
            'total' => $total
        ]);
    }

    public function actionUpdate()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            return $this->returnJson(['success' => false, 'message' => 'Будь ласка, увійдіть']);
        }

        $itemId = $this->get->item_id ?? null;
        $quantity = $this->get->quantity ?? 1;

        if (empty($itemId)) {
            return $this->returnJson(['success' => false, 'message' => 'Не вказано елемент кошика']);
        }

        $itemInfo = Cart::getCartItemById($itemId);
        if (!$itemInfo) {
            return $this->returnJson(['success' => false, 'message' => 'Товар не знайдено в кошику']);
        }

        if ($quantity > $itemInfo['StockQuantity']) {
            return $this->returnJson([
                'success' => false, 
                'message' => 'Неможливо додати більше товару, ніж є в наявності. Доступно: ' . $itemInfo['StockQuantity']
            ]);
        }

        Cart::updateQuantity($itemId, $quantity);

        $count = Cart::getCartCount($customer['CustomerID']);
        $total = Cart::getCartTotal($customer['CustomerID']);
        $item_total = $itemInfo['Price'] * $quantity;

        return $this->returnJson([
            'success' => true,
            'count' => $count,
            'total' => $total,
            'item_total' => $item_total
        ]);
    }

    public function actionCount()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            return $this->returnJson(['count' => 0]);
        }
        $count = Cart::getCartCount($customer['CustomerID']);
        return $this->returnJson(['count' => $count]);
    }

    protected function returnJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    public function actionCheckout()
    {
        $customer = Core::get()->session->get('customer');
        if (empty($customer)) {
            $this->addErrorMessage('Будь ласка, увійдіть для оформлення замовлення');
            return $this->redirect('/site/profile/login');
        }

        $session = Core::get()->session;
        $error_message = $session->get('error_message');
        if ($error_message) {
            $this->template->setParam('error_message', $error_message);
            $session->remove('error_message');
        }

        $items = Cart::getCartItems($customer['CustomerID']);
        $total = Cart::getCartTotal($customer['CustomerID']);

        if (empty($items)) {
            $this->addErrorMessage('Ваш кошик порожній');
            return $this->redirect('/site/cart');
        }

        if ($this->isPost) {
          
            $insufficient = [];
            foreach ($items as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $product = \core\Core::get()->db->select('products', ['ProductName', 'StockQuantity'], ['ProductID' => $productId]);
                if (!$product || $product[0]['StockQuantity'] < $quantity) {
                    $insufficient[] = [
                        'name' => $product ? $product[0]['ProductName'] : ('ID ' . $productId),
                        'requested' => $quantity,
                        'available' => $product ? $product[0]['StockQuantity'] : 0
                    ];
                }
            }
            if (!empty($insufficient)) {
                $msg = "";
                foreach ($insufficient as $item) {
                    if ($item['available'] == 0) {
                        $msg .= "<b>".htmlspecialchars($item['name'])."</b>: цей товар закінчився. Чекайте поповнення запасів!<br>";
                    } else {
                        $msg .= "<b>".htmlspecialchars($item['name'])."</b>: потрібно {$item['requested']}, доступно: {$item['available']}<br>";
                    }
                }
           
                Core::get()->session->set('error_message', $msg);
                return $this->redirect('/site/cart/checkout');
            }
            $orderData = [
                'CustomerID' => $customer['CustomerID'],
                'OrderDate' => date('Y-m-d H:i:s'),
                'TotalAmount' => $total,
                'OrderStatus' => 'Processing',
                'PaymentMethod' => $this->post->payment_method,
                'ShippingAddress' => $this->post->shipping_address,
                'ShippingCity' => $this->post->shipping_city,
                'ShippingCost' => $this->post->shipping_cost ?? 0.00,
                'EstimatedDeliveryDate' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'FirstName' => $this->post->first_name,
                'LastName' => $this->post->last_name
            ];

            $orderId = Order::createOrder($orderData, $items);

            if ($orderId) {
                Cart::clearCart($customer['CustomerID']);

                 return $this->redirect("/site/cart/success/{$orderId}");
            } else {
                $this->addErrorMessage('Помилка при оформленні замовлення');
            }
        }

        $this->template->setParams([
            'items' => $items,
            'total' => $total,
            'customer' => $customer, 
            'title' => 'Оформлення замовлення'
        ]);

        return $this->render('views/cart/checkout.php');
    }

      public function actionSuccess($params)
    {
        $orderId = $params[0] ?? null;
        if (empty($orderId)) {
            if (!empty($_GET['orderId'])) {
                $orderId = $_GET['orderId'];
            } else {
                $uri = $_SERVER['REQUEST_URI'] ?? '';
                if (preg_match('#/cart/success/(\d+)#', $uri, $m)) {
                    $orderId = $m[1];
                }
            }
        }
        $this->template->setParams([
            'title' => 'Замовлення успішно оформлено',
            'orderId' => $orderId
        ]);
        return $this->render('views/order/success.php');
    }
}