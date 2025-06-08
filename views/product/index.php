<?php
use models\Customers;
/** @var array $products */
$this->Title = $title ?? 'Всі товари';
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/site/home" class="text-decoration-none">
                    <i class="fas fa-home"></i> Головна
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Товари
            </li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="text-primary mb-0">
                    <i class="fas fa-shopping-bag"></i> Всі товари
                </h1>
                <div class="d-flex gap-2">
                    <a href="/site/category" class="btn btn-outline-primary">
                        <i class="fas fa-th-large"></i> Переглянути категорії
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <div class="mb-3">
                <i class="fas fa-box-open fa-3x text-muted"></i>
            </div>
            <h4>Товари не знайдено</h4>
            <p class="mb-0">Товари поки що відсутні в системі.</p>
            <div class="mt-3">
                <a href="/site/category" class="btn btn-primary">
                    <i class="fas fa-th-large"></i> Переглянути категорії
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Загальна кількість товарів: <strong><?= count($products) ?></strong>
                </p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 product-card shadow-sm">
                        <div class="card-img-container">
                            <?php if (!empty($product['ImageURL'])): ?>
                                <img src="<?= htmlspecialchars($product['ImageURL']) ?>" class="card-img-top product-image"
                                    alt="<?= htmlspecialchars($product['ProductName']) ?>"
                                    onerror="this.src='/site/assets/images/no-image.jpg'">
                            <?php else: ?>
                                <div class="card-img-top no-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <?php if (!empty($product['CategoryName'])): ?>
                                <div class="mb-2">
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-tag"></i> <?= htmlspecialchars($product['CategoryName']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <h5 class="card-title text-primary">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </h5>

                            <?php if (!empty($product['DescriptionProduct'])): ?>
                                <p class="card-text text-muted flex-grow-1">
                                    <?= htmlspecialchars(mb_substr($product['DescriptionProduct'], 0, 100)) ?>
                                    <?= mb_strlen($product['DescriptionProduct']) > 100 ? '...' : '' ?>
                                </p>
                            <?php else: ?>
                                <p class="card-text text-muted flex-grow-1">
                                    Опис товару відсутній
                                </p>
                            <?php endif; ?>

                            <div class="product-info mt-auto">
                                <div class="price-container mb-2">
                                    <span class="price h4 text-success mb-0">
                                        <i class="fas fa-hryvnia-sign"></i>
                                        <?= number_format($product['Price'], 2, ',', ' ') ?>
                                    </span>
                                </div>
                                <div class="stock-info mb-3">
                                    <?php if ($product['StockQuantity'] > 0): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> В наявності: <?= $product['StockQuantity'] ?> шт.
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Немає в наявності
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="btn-group w-100" role="group">
                                    <a href="/site/product/detail?id=<?= $product['ProductID'] ?>"
                                        class="btn btn-primary flex-fill">
                                        <i class="fas fa-eye"></i> Детальніше
                                    </a>
                                    <?php if (Customers::isUserLogged() && $product['StockQuantity'] > 0): ?>
                                        <button class="btn btn-success add-to-cart" data-product-id="<?= $product['ProductID'] ?>">
                                            <i class="fas fa-shopping-cart"></i> Купити
                                        </button>
                                    <?php elseif ($product['StockQuantity'] > 0): ?>
                                        <!-- Гість, товар є, але не можна купити -->
                                        <button class="btn btn-secondary flex-fill" disabled>
                                            <i class="fas fa-user-lock"></i> Лише для зареєстрованих
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary flex-fill" disabled>
                                            <i class="fas fa-ban"></i> Недоступно
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border: 1px solid #e0e0e0;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .card-img-container {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image,
    .card-img-top {
        max-width: 95% !important;
        max-height: 95% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .no-image {
        height: 200px;
        background-color: var(--card-bg);
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .price {
        font-weight: 700;
        color: #28a745 !important;
    }

    .stock-info .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }

    .btn-group .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
    }

    .btn-success:hover {
        transform: translateY(-1px);
    }

    .breadcrumb {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .col-sm-12 {
            margin-bottom: 1.5rem;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 0.5rem;
        }

        .btn-group .btn:last-child {
            margin-bottom: 0;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<script>
    function addToCart(productId) {
        alert('Функціонал кошика буде додано пізніше. Товар ID: ' + productId);
    }
</script>