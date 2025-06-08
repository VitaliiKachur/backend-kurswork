<?php
/** @var array $items */
/** @var float $total */
$this->Title = $title ?? 'Оформлення замовлення';


?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/site/home">Головна</a></li>
            <li class="breadcrumb-item"><a href="/site/cart">Кошик</a></li>
            <li class="breadcrumb-item active" aria-current="page">Оформлення замовлення</li>
        </ol>
    </nav>

    <h1>Оформлення замовлення</h1>

    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Дані доставки</h2>
            <form method="post">
                <div class="form-group">
                    <label for="first_name">Ім'я</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="<?= htmlspecialchars($customer['FirstName'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Прізвище</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="<?= htmlspecialchars($customer['LastName'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="shipping_address">Адреса доставки</label>
                    <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
                </div>
                <div class="form-group">
                    <label for="shipping_city">Місто</label>
                    <input type="text" class="form-control" id="shipping_city" name="shipping_city" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Спосіб оплати</label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="">Оберіть спосіб оплати</option>
                        <option value="Credit Card">Кредитна картка</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Cash on Delivery">Готівкою при отриманні</option>
                    </select>
                </div>
                <input type="hidden" name="shipping_cost" value="150">
                <button type="submit" class="btn-confirm-order">Підтвердити замовлення</button>
            </form>
        </div>

        <div class="checkout-summary">
            <h2>Ваше замовлення</h2>
            <table>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="product-row">
                            <td>
                                <div class="product-name"><?= htmlspecialchars($item['ProductName']) ?></div>
                                <div class="product-quantity">Кількість: <?= $item['quantity'] ?></div>
                            </td>
                            <td class="text-end"><?= number_format($item['Price'] * $item['quantity'], 2, ',', ' ') ?> ₴</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td>До сплати:</td>
                        <td class="text-end"><?= number_format($total, 2, ',', ' ') ?> ₴</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (isset($error_message) && !empty($error_message)): ?>
    <div class="alert alert-danger"><?= $error_message ?></div>
<?php endif; ?>