<?php
/** @var array $categories */
$this->Title = 'Категорії товарів';
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="text-white mb-3">Категорії товарів</h1>
    </div>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info text-center">
            <h4>Категорії не знайдено</h4>
            <p>На жаль, категорії товарів поки що відсутні.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm category-card">
                        <?php if (!empty($category['CategoryImage'])): ?>
                            <div class="d-flex flex-row align-items-center h-100">
                                <div class="category-image-wrapper d-flex align-items-center justify-content-center p-2">
                                    <img src="<?= htmlspecialchars($category['CategoryImage']) ?>" alt="<?= htmlspecialchars($category['CategoryName']) ?>">
                                </div>
                                <div class="card-body d-flex flex-column flex-grow-1 justify-content-center">
                                    <h5 class="card-title text-primary">
                                        <?= htmlspecialchars($category['CategoryName']) ?>
                                    </h5>
                                    <?php if (!empty($category['CategoryDescription'])): ?>
                                        <p class="card-text flex-grow-1">
                                            <?= htmlspecialchars($category['CategoryDescription']) ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="card-text flex-grow-1 text-muted">
                                            Опис категорії відсутній
                                        </p>
                                    <?php endif; ?>
                                    <div class="mt-auto">
                                        <a href="/site/product/view?category_id=<?= $category['CategoryID'] ?>"
                                            class="btn btn-primary btn-block">
                                            <i class="fas fa-eye"></i> Переглянути товари
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">
                                    <?= htmlspecialchars($category['CategoryName']) ?>
                                </h5>
                                <?php if (!empty($category['CategoryDescription'])): ?>
                                    <p class="card-text flex-grow-1">
                                        <?= htmlspecialchars($category['CategoryDescription']) ?>
                                    </p>
                                <?php else: ?>
                                    <p class="card-text flex-grow-1 text-muted">
                                        Опис категорії відсутній
                                    </p>
                                <?php endif; ?>
                                <div class="mt-auto">
                                    <a href="/site/product/view?category_id=<?= $category['CategoryID'] ?>"
                                        class="btn btn-primary btn-block">
                                        <i class="fas fa-eye"></i> Переглянути товари
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .category-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border: 1px solid #e0e0e0;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
    }

    .card-text {
        color: #6c757d;
        line-height: 1.5;
    }

    .container {
        max-width: 1200px;
    }

    @media (max-width: 768px) {
        .col-sm-6 {
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 1.75rem;
        }
    }

    .category-image-wrapper {
        border-radius: 8px;
        min-height: 140px;
        max-height: 140px;
        min-width: 140px;
        max-width: 160px;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .category-image-wrapper img {
        max-width: 140px;
        max-height: 140px;
        width: auto;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
        display: block;
        margin: 0 auto;
    }
</style>