<?php
/** @var array $items */
/** @var float $total */
$this->Title = $title ?? 'Кошик';
?>

<style>
.remove-item-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 10px;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.remove-item-btn:hover {
    background: #b52a37;
}
.remove-item-btn i {
    pointer-events: none;
}
.text-muted {
    color: rgb(171 213 255 / 75%) !important;
}
</style>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/site/home">Головна</a></li>
            <li class="breadcrumb-item active" aria-current="page">Кошик</li>
        </ol>
    </nav>

    <h1>Кошик</h1>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Ваш кошик порожній</h3>
                <p class="mb-0">Почніть покупки, щоб додати товари до кошика</p>
                <a href="/site/category" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> До категорій
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <div class="cart-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Ціна</th>
                                <th>Кількість</th>
                                <th>Сума</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr data-item-id="<?= $item['id'] ?>" data-stock="<?= $item['StockQuantity'] ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($item['ImageURL'])): ?>
                                                <img src="<?= htmlspecialchars($item['ImageURL']) ?>" 
                                                     class="product-image me-3"
                                                     alt="<?= htmlspecialchars($item['ProductName']) ?>">
                                            <?php else: ?>
                                                <div class="product-image me-3 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <a href="/site/product/details?id=<?= $item['id'] ?>" class="product-name">
                                                <?= htmlspecialchars($item['ProductName']) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="item-price"><?= number_format($item['Price'], 2, ',', ' ') ?> ₴</td>
                                    <td>
                                        <div class="cart-quantity">
                                            <button class="update-quantity" type="button"
                                                data-item-id="<?= $item['id'] ?>" data-action="decrease">-</button>
                                            <input type="text" class="quantity-input"
                                                value="<?= $item['quantity'] ?>" 
                                                data-item-id="<?= $item['id'] ?>"
                                                data-stock="<?= $item['StockQuantity'] ?>">
                                            <button class="update-quantity" type="button"
                                                data-item-id="<?= $item['id'] ?>" data-action="increase">+</button>
                                        </div>
                                        <small class="text-muted">В наявності: <?= $item['StockQuantity'] ?></small>
                                    </td>
                                    <td class="item-total">
                                        <?= number_format($item['Price'] * $item['quantity'], 2, ',', ' ') ?> ₴
                                    </td>
                                    <td>
                                        <button class="remove-item-btn"
                                            data-item-id="<?= $item['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="cart-summary">
                    <h5>Підсумок</h5>
                    <div class="total-row">
                        <span>Товари:</span>
                        <span id="total-items-count"><?= count($items) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Загальна сума:</span>
                        <span class="total-price" id="total-price">
                            <?= number_format($total, 2, ',', ' ') ?> ₴
                        </span>
                    </div>

                    <a href="/site/cart/checkout" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Оформити замовлення
                    </a>
                    <a href="/site/category" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Продовжити покупки
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateCartCounter(count) {
            const counter = document.getElementById('cart-count');
            if (counter) {
                counter.textContent = count || 0;
            }
        }

        function updateTotalPrice(total) {
            const totalElement = document.getElementById('total-price');
            if (totalElement) {
                totalElement.textContent = numberFormat(total) + ' ₴';
            }
        }

        function updateTotalItemsCount(count) {
            const countElement = document.getElementById('total-items-count');
            if (countElement) {
                countElement.textContent = count;
            }
        }

        function numberFormat(number) {
            return parseFloat(number).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

        function updateItemTotal(itemId, price, quantity) {
            const itemRow = document.querySelector(`tr[data-item-id="${itemId}"]`);
            if (itemRow) {
                const totalCell = itemRow.querySelector('.item-total');
                if (totalCell) {
                    const total = price * quantity;
                    totalCell.textContent = numberFormat(total) + ' ₴';
                }
            }
        }

        function safeJsonParse(response) {
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', e);
                    console.error('Response text:', text);
                    throw new Error('Сервер повернув некоректну відповідь');
                }
            });
        }

        document.querySelectorAll('.update-quantity').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.dataset.itemId;
                const action = this.dataset.action;
                const quantityContainer = this.closest('.cart-quantity');
                const input = quantityContainer.querySelector('.quantity-input');
                const stockQuantity = parseInt(input.dataset.stock);
                let quantity = parseInt(input.value);

                if (action === 'increase') {
                    if (quantity >= stockQuantity) {
                        alert('Неможливо додати більше товару, ніж є в наявності');
                        return;
                    }
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }

                input.value = quantity;
                updateCartItem(itemId, quantity);
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function () {
                const itemId = this.dataset.itemId;
                const stockQuantity = parseInt(this.dataset.stock);
                let quantity = parseInt(this.value);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    this.value = 1;
                }

                if (quantity > stockQuantity) {
                    alert('Неможливо додати більше товару, ніж є в наявності');
                    quantity = stockQuantity;
                    this.value = stockQuantity;
                }

                updateCartItem(itemId, quantity);
            });
        });

        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function () {
                if (confirm('Ви впевнені, що хочете видалити цей товар з кошика?')) {
                    const itemId = this.dataset.itemId;
                    const row = this.closest('tr');
                    row.style.opacity = '0.5';
                    row.style.transition = 'opacity 0.3s';

                    removeCartItem(itemId, row);
                }
            });
        });

        function updateCartItem(itemId, quantity) {
            fetch(`/site/cart/update?item_id=${itemId}&quantity=${quantity}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return safeJsonParse(response);
            })
            .then(data => {
                if (data.success) {
                    updateCartCounter(data.count);
                    updateTotalPrice(data.total);
                    updateTotalItemsCount(data.count);

                    const itemRow = document.querySelector(`tr[data-item-id="${itemId}"]`);
                    if (itemRow) {
                        const totalCell = itemRow.querySelector('.item-total');
                        const priceText = itemRow.querySelector('.item-price').textContent;
                        const price = parseFloat(priceText.replace(/[^\d,]/g, '').replace(',', '.'));
                        
                        if (totalCell) {
                            totalCell.textContent = numberFormat(price * quantity) + ' ₴';
                        }
                    }
                } else {
                    alert(data.message || 'Помилка оновлення кількості товару');
                }
            })
            .catch(error => {
                console.error('Error updating cart:', error);
                alert('Помилка при оновленні кошика: ' + error.message);
            });
        }

        function removeCartItem(itemId, row) {
            fetch(`/site/cart/remove?item_id=${itemId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return safeJsonParse(response);
            })
            .then(data => {
                if (data.success) {
                    updateCartCounter(data.count);
                    updateTotalPrice(data.total);
                    updateTotalItemsCount(data.count);

                    if (row) {
                        row.remove();
                    }

                    if (data.count === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Помилка видалення товару');
                    if (row) {
                        row.style.opacity = '1';
                    }
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                alert('Помилка при видаленні товару: ' + error.message);
                if (row) {
                    row.style.opacity = '1';
                }
            });
        }

        fetch('/site/cart/count', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => safeJsonParse(response))
        .then(data => {
            updateCartCounter(data.count);
        })
        .catch(error => {
            console.error('Error loading cart count:', error);
        });
    });
</script>