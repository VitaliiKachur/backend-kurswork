<?php
/** @var array $orders */
$this->Title = $title ?? 'Мої замовлення';
?>
<div class="container mt-5">
    <div class="orders-header mb-5 text-center">
        <h1 class="mb-3 fw-bold text-white">Мої замовлення</h1>
        <p class="text-muted fs-5">Історія ваших замовлень та їх статуси</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            <span>У вас ще немає замовлень.</span>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover orders-table">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Дата</th>
                        <th>Сума</th>
                        <th>Статус</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr class="order-row" data-order-id="<?= $order['OrderID'] ?>">
                        <td class="fw-bold">#<?= $order['OrderID'] ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span><?= date('d.m.Y', strtotime($order['OrderDate'])) ?></span>
                                <small class="text-muted"><?= date('H:i', strtotime($order['OrderDate'])) ?></small>
                            </div>
                        </td>
                        <td class="fw-bold"><?= number_format($order['TotalAmount'], 2, ',', ' ') ?> ₴</td>
                        <td>
                            <?php
                            $statusClass = match($order['OrderStatus']) {
                                'Processing' => 'bg-warning',
                                'Completed' => 'bg-success',
                                'Cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            $statusText = match($order['OrderStatus']) {
                                'Processing' => 'В обробці',
                                'Completed' => 'Виконано',
                                'Cancelled' => 'Скасовано',
                                default => $order['OrderStatus']
                            };
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary view-details-btn" data-order-id="<?= $order['OrderID'] ?>">
                                <i class="fas fa-eye"></i> Деталі
                            </button>
                        </td>
                    </tr>
                    <tr class="order-details d-none" id="details-<?= $order['OrderID'] ?>">
                        <td colspan="5">
                            <div class="order-details-content p-3">
                                <h5 class="mb-3">Деталі замовлення #<?= $order['OrderID'] ?></h5>
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Завантаження...</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.orders-table {
    background: #242b3d;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.orders-table thead th {
    border: none;
    padding: 20px 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    background: #1e2433;
}

.orders-table tbody {
    background: #242b3d;
}

.order-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #2d364c;
    background: #242b3d !important;
    color: #ffffff !important;
}

.order-row td {
    background: #242b3d !important;
    padding: 20px 15px !important;
    font-size: 1.1rem;
    color: #ffffff !important;
}

.order-row:hover {
    background-color: #2d364c !important;
    transform: translateX(5px);
}

.order-row:hover td {
    background-color: #2d364c !important;
}

.text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 1rem;
}

.order-details-content {
    background: #1e2433;
    border-radius: 8px;
    margin: 0 15px 15px;
}

.table-hover tbody tr:hover {
    background-color: #2d364c !important;
    color: #ffffff;
}

.btn-outline-primary {
    color: #ffffff;
    border-color: #2d364c;
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.btn-outline-primary:hover {
    background-color: #2d364c;
    border-color: #2d364c;
    color: #ffffff;
}

.small, small {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
}

.fw-bold {
    color: #ffffff !important;
}

#ID, .order-id {
    font-weight: 600;
    color: #ffffff;
}

.status-badge {
    font-size: 1rem;
    padding: 6px 12px;
    border-radius: 20px;
    color: white;
    font-weight: 500;
    display: inline-block;
}

.view-details-btn {
    transition: all 0.2s ease;
}

.view-details-btn:hover {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .orders-table {
        font-size: 0.9em;
    }
    
    .order-row td {
        padding: 10px;
    }
    
    .view-details-btn {
        padding: 4px 8px;
        font-size: 0.8em;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailsButtons = document.querySelectorAll('.view-details-btn');
    
    detailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const detailsRow = document.getElementById(`details-${orderId}`);
            
            if (detailsRow.classList.contains('d-none')) {
                document.querySelectorAll('.order-details').forEach(row => {
                    if (row.id !== `details-${orderId}`) {
                        row.classList.add('d-none');
                    }
                });
                
                detailsRow.classList.remove('d-none');
                
    
            } else {
                detailsRow.classList.add('d-none');
            }
        });
    });
});</script>