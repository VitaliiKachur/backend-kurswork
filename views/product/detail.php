<?php
/** @var array $product */
/** @var array $category */
$this->Title = $title ?? 'Деталі товару';
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/site/home" class="text-decoration-none">
                    <i class="fas fa-home"></i> Головна
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/site/category" class="text-decoration-none">Категорії</a>
            </li>
            <?php if (isset($category)): ?>
                <li class="breadcrumb-item">
                    <a href="/site/product/view?category_id=<?= $category['CategoryID'] ?>" class="text-decoration-none">
                        <?= htmlspecialchars($category['CategoryName']) ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($product['ProductName']) ?>
            </li>
        </ol>
    </nav>

    <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($product)): ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="product-image-container">
                    <?php if (!empty($product['ImageURL'])): ?>
                        <img src="<?= htmlspecialchars($product['ImageURL']) ?>" class="img-fluid product-detail-image"
                            alt="<?= htmlspecialchars($product['ProductName']) ?>" onerror="this.src='/images/no-image.jpg'">
                    <?php else: ?>
                        <div class="no-image-placeholder d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="fas fa-image fa-5x text-muted mb-3"></i>
                                <p class="text-muted">Зображення відсутнє</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="product-info">
                    <h1 class="product-title text-primary mb-3">
                        <?= htmlspecialchars($product['ProductName']) ?>
                    </h1>
                    <?php if (isset($category)): ?>
                        <div class="mb-3">
                            <span class="badge bg-secondary fs-6">
                                <i class="fas fa-tag"></i>
                                <?= htmlspecialchars($category['CategoryName']) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="price-section mb-4">
                        <h2 class="price text-success mb-0">
                            <i class="fas fa-hryvnia-sign"></i>
                            <?= number_format($product['Price'], 2, ',', ' ') ?>
                        </h2>
                        <small class="text-muted">Ціна за одиницю</small>
                    </div>

                    <div class="stock-section mb-4">
                        <h5>Наявність:</h5>
                        <?php if ($product['StockQuantity'] > 0): ?>
                            <div class="alert alert-success d-inline-block">
                                <i class="fas fa-check-circle"></i>
                                <strong>В наявності:</strong> <?= $product['StockQuantity'] ?> шт.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger d-inline-block">
                                <i class="fas fa-times-circle"></i>
                                <strong>Немає в наявності</strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="action-buttons mb-4">
                        <?php if ($product['StockQuantity'] > 0): ?>
                            <button class="btn btn-success add-to-cart" data-product-id="<?= $product['ProductID'] ?>">
                                <i class="fas fa-shopping-cart"></i> Купити
                            </button>
                           
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg mb-2" disabled>
                                <i class="fas fa-ban"></i> Товар недоступний
                            </button>
                            <button class="btn btn-outline-primary btn-lg mb-2"
                                onclick="notifyWhenAvailable(<?= $product['ProductID'] ?>)">
                                <i class="fas fa-bell"></i> Повідомити про наявність
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="additional-info">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-truck text-muted"></i>
                                <strong>Доставка:</strong> По всій Україні
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-muted"></i>
                                <strong>Гарантія:</strong> Згідно з законодавством
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-undo text-muted"></i>
                                <strong>Повернення:</strong> Протягом 14 днів
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($product['DescriptionProduct'])): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">
                                <i class="fas fa-info-circle"></i> Опис товару
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?= nl2br(htmlspecialchars($product['DescriptionProduct'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between flex-wrap">
                    <?php if (isset($category)): ?>
                        <a href="/site/product/view?category_id=<?= $category['CategoryID'] ?>"
                            class="btn btn-outline-secondary mb-2">
                            <i class="fas fa-arrow-left"></i> Назад до категорії
                        </a>
                    <?php endif; ?>
                    <a href="/site/category" class="btn btn-outline-primary mb-2">
                        <i class="fas fa-th-large"></i> Всі категорії
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;
                fetch(`/site/cart/add?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Товар додано до кошика!');
                            document.getElementById('cart-count').textContent = data.count;
                        } else {
                            alert(data.message);
                        }
                    });
            });
        });
    });
</script>
<style>
    .product-image-container {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .product-detail-image {
        width: 500px;
        height: auto;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-detail-image:hover {
        transform: scale(1.05);
    }

    .no-image-placeholder {
        height: 400px;
        background-color: var(--card-bg);
        border: 2px dashed #dee2e6;
        border-radius: 10px;
    }

    .product-title {
        font-weight: 700;
        font-size: 2rem;
    }

    .price {
        font-weight: 700;
        font-size: 2.5rem;
        color: #28a745 !important;
    }

    .stock-section .alert {
        margin-bottom: 0;
        padding: 0.75rem 1rem;
        font-size: 1.1rem;
    }

    .action-buttons .btn {
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
</style>