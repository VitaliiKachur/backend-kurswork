<?php
use models\Customers;
/** @var array $products */
/** @var array $category */
/** @var array $search_params */
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
        <div class="category-banner">

            <h2>
                <i class="fas fa-tag"></i>
                <?= htmlspecialchars($category['CategoryName']) ?>
            </h2>
            <?php if (!empty($category['CategoryDescription'])): ?>
                <p><?= htmlspecialchars($category['CategoryDescription']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="search-filters">
        <h2>
            <i class="fas fa-search"></i> Пошук та фільтри
        </h2>
        <form method="get" action="/site/product/view" id="filterForm">
            <input type="hidden" name="category_id"
                value="<?= htmlspecialchars($search_params['category_id'] ?? '') ?>">

            <div class="row g-4">
                <div class="col-md-4">
                    <label for="search" class="form-label">Пошук за назвою:</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Введіть назву товару..."
                            value="<?= htmlspecialchars($search_params['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Діапазон цін:</label>
                    <div class="price-range">
                        <div class="input-group">
                            <span class="input-group-text">₴</span>
                            <input type="number" name="price_min" class="form-control" placeholder="Від" min="0"
                                step="0.01" value="<?= htmlspecialchars($search_params['price_min'] ?? '') ?>">
                        </div>
                        <span>—</span>
                        <div class="input-group">
                            <span class="input-group-text">₴</span>
                            <input type="number" name="price_max" class="form-control" placeholder="До" min="0"
                                step="0.01" value="<?= htmlspecialchars($search_params['price_max'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="sort" class="form-label">Сортування:</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="" <?= empty($search_params['sort']) ? 'selected' : '' ?>>
                            За замовчуванням
                        </option>
                        <option value="asc" <?= ($search_params['sort'] ?? '') === 'asc' ? 'selected' : '' ?>>
                            За алфавітом (А-Я)
                        </option>
                        <option value="desc" <?= ($search_params['sort'] ?? '') === 'desc' ? 'selected' : '' ?>>
                            За алфавітом (Я-А)
                        </option>
                        <option value="price_asc" <?= ($search_params['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>
                            За ціною (зростання)
                        </option>
                        <option value="price_desc" <?= ($search_params['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>
                            За ціною (спадання)
                        </option>
                        <option value="stock_asc" <?= ($search_params['sort'] ?? '') === 'stock_asc' ? 'selected' : '' ?>>
                            За кількістю (зростання)
                        </option>
                        <option value="stock_desc" <?= ($search_params['sort'] ?? '') === 'stock_desc' ? 'selected' : '' ?>>
                            За кількістю (спадання)
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Знайти
                </button>
                <button type="button" class="btn btn-secondary" id="clearFilters">
                    <i class="fas fa-times"></i> Очистити фільтри
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($search_params['search']) || !empty($search_params['price_min']) || !empty($search_params['price_max'])): ?>
        <div class="alert alert-light border-0 mb-4">
            <i class="fas fa-info-circle"></i>
            Знайдено товарів: <strong><?= count($products) ?></strong>
            <?php if (!empty($search_params['search'])): ?>
                за запитом "<strong><?= htmlspecialchars($search_params['search']) ?></strong>"
            <?php endif; ?>
            <?php if (!empty($search_params['price_min']) || !empty($search_params['price_max'])): ?>
                в ціновому діапазоні:
                <?= !empty($search_params['price_min']) ? htmlspecialchars($search_params['price_min']) . ' ₴' : '0 ₴' ?>
                —
                <?= !empty($search_params['price_max']) ? htmlspecialchars($search_params['price_max']) . ' ₴' : '∞' ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div id="productsContainer">
        <?php if (empty($products)): ?>
            <div class="alert alert-info text-center">
                <div class="mb-3">
                    <i class="fas fa-box-open fa-3x text-muted"></i>
                </div>
                <h4>Товари не знайдено</h4>
                <p class="mb-0">
                    <?php if (isset($category)): ?>
                        <?php if (!empty($search_params['search']) || !empty($search_params['price_min']) || !empty($search_params['price_max'])): ?>
                            За вашими критеріями пошуку в категорії "<?= htmlspecialchars($category['CategoryName']) ?>" товари не
                            знайдено.
                        <?php else: ?>
                            В категорії "<?= htmlspecialchars($category['CategoryName']) ?>" поки що немає товарів.
                        <?php endif; ?>
                    <?php else: ?>
                        Товари поки що відсутні.
                    <?php endif; ?>
                </p>
                <div class="mt-3">
                    <?php if (!empty($search_params['search']) || !empty($search_params['price_min']) || !empty($search_params['price_max'])): ?>
                        <button type="button" class="btn btn-primary me-2" id="clearFilters">
                            <i class="fas fa-times"></i> Очистити фільтри
                        </button>
                    <?php endif; ?>
                    <a href="/site/category" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Повернутися до категорій
                    </a>
                </div>
            </div>
        <?php else: ?>
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
                                        <?php if (Customers::isUserLogged() && $product['StockQuantity'] > 0): ?>
                                            <button class="btn btn-success add-to-cart"
                                                data-product-id="<?= $product['ProductID'] ?>">
                                                <i class="fas fa-shopping-cart"></i> Купити
                                            </button>
                                        <?php elseif ($product['StockQuantity'] > 0): ?>
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

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="/site/category" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Повернутися до категорій
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
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

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .card-header {
        background-color: var(--card-bg);
        border-bottom: 1px solid #dee2e6;
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
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('filterForm');
        const clearFiltersBtn = document.querySelectorAll('#clearFilters');
        const productsContainer = document.getElementById('productsContainer');

        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Пошук...';
            submitBtn.disabled = true;

            fetch(`/site/product/view?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newProductsContainer = doc.querySelector('#productsContainer');

                    if (newProductsContainer) {
                        productsContainer.innerHTML = newProductsContainer.innerHTML;

                        const newUrl = new URL(window.location);
                        for (const [key, value] of params) {
                            if (value) {
                                newUrl.searchParams.set(key, value);
                            } else {
                                newUrl.searchParams.delete(key);
                            }
                        }
                        window.history.pushState({}, '', newUrl);

                        showNotification('Пошук виконано успішно!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Помилка при виконанні пошуку. Спробуйте ще раз.', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        clearFiltersBtn.forEach(btn => {
            btn.addEventListener('click', function () {
                filterForm.querySelector('input[name="search"]').value = '';
                filterForm.querySelector('input[name="price_min"]').value = '';
                filterForm.querySelector('input[name="price_max"]').value = '';
                filterForm.querySelector('select[name="sort"]').value = '';
                filterForm.dispatchEvent(new Event('submit'));
            });
        });

        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';

            const notification = document.createElement('div');
            notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas ${iconClass}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        document.addEventListener('click', function (event) {
            if (event.target.closest('.add-to-cart')) {
                const btn = event.target.closest('.add-to-cart');
                const productId = btn.getAttribute('data-product-id');

                console.log('Adding product to cart:', productId);
                showNotification('Товар додано до кошика!', 'success');

                btn.innerHTML = '<i class="fas fa-check"></i> Додано';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');

                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Купити';
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success');
                }, 2000);
            }
        });

        const searchInput = filterForm.querySelector('input[name="search"]');
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
    
                }
            }, 500);
        });

        const priceInputs = filterForm.querySelectorAll('input[name="price_min"], input[name="price_max"]');
        priceInputs.forEach(input => {
            input.addEventListener('change', function () {
                const priceMin = parseFloat(filterForm.querySelector('input[name="price_min"]').value) || 0;
                const priceMax = parseFloat(filterForm.querySelector('input[name="price_max"]').value) || Infinity;

                if (priceMin > priceMax && priceMax !== Infinity) {
                    showNotification('Мінімальна ціна не може бути більшою за максимальну!', 'error');
                    this.value = '';
                }
            });
        });
    });
</script>