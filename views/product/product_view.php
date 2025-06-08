<?php
/** @var array $products */
/** @var array $category */
$this->Title = $title ?? 'Товари категорії';
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
                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlspecialchars($category['CategoryName']) ?>
                </li>
            <?php endif; ?>
        </ol>
    </nav>

    <?php if (isset($category)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <?php if (!empty($category['CategoryImage'])): ?>
                            <img src="<?= htmlspecialchars($category['CategoryImage']) ?>" alt="<?= htmlspecialchars($category['CategoryName']) ?>" style="max-height:120px; object-fit:cover; margin-bottom:1rem; border-radius:0.5rem;">
                        <?php endif; ?>
                        <h2 class="mb-0">
                            <i class="fas fa-tag"></i>
                            <?= htmlspecialchars($category['CategoryName']) ?>
                        </h2>
                    </div>
                    <?php if (!empty($category['CategoryDescription'])): ?>
                        <div class="card-body">
                            <p class="card-text">
                                <?= htmlspecialchars($category['CategoryDescription']) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-12">
            <form method="get" action="" class="d-flex align-items-center gap-3">
                <input type="text" name="search" class="form-control" placeholder="Пошук за назвою..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <div class="d-flex align-items-center">
                    <label for="price_min" class="me-2">Ціна:</label>
                    <input type="number" name="price_min" id="price_min" class="form-control" placeholder="Від" value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>">
                    <span class="mx-2">-</span>
                    <input type="number" name="price_max" id="price_max" class="form-control" placeholder="До" value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>">
                </div>

                <select name="sort" class="form-select">
                    <option value="" <?= empty($_GET['sort']) ? 'selected' : '' ?>>Сортувати</option>
                    <option value="asc" <?= ($_GET['sort'] ?? '') === 'asc' ? 'selected' : '' ?>>За алфавітом (А-Я)</option>
                    <option value="desc" <?= ($_GET['sort'] ?? '') === 'desc' ? 'selected' : '' ?>>За алфавітом (Я-А)</option>
                </select>

                <button type="submit" class="btn btn-primary">Застосувати</button>
            </form>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <div class="mb-3">
                <i class="fas fa-box-open fa-3x text-muted"></i>
            </div>
            <h4>Товари не знайдено</h4>
            <p class="mb-0">
                <?php if (isset($category)): ?>
                    В категорії "<?= htmlspecialchars($category['CategoryName']) ?>" поки що немає товарів.
                <?php else: ?>
                    Товари поки що відсутні.
                <?php endif; ?>
            </p>
            <div class="mt-3">
                <a href="/site/category" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Повернутися до категорій
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Знайдено товарів: <strong><?= count($products) ?></strong>
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
                                    onerror="this.src='/images/no-image.jpg'">
                            <?php else: ?>
                                <div class="card-img-top no-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
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
                                    <?php if ($product['StockQuantity'] > 0): ?>
                                        <button class="btn btn-success add-to-cart" data-product-id="<?= $product['ProductID'] ?>">
                                            <i class="fas fa-shopping-cart"></i> Купити
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

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="/site/category" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Повернутися до категорій
                </a>
            </div>
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
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .no-image {
        height: 200px;
        background-color: var(--card-bg);
        border-bottom: 1px solid #e0e0e0;
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
        background-color: var(--card-bg);
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
    }
</style>

<script>
    function addToCart(productId) {
        alert('Функціонал кошика буде додано пізніше. Товар ID: ' + productId);
    }
</script>