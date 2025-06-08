<?php
/** @var string $table */
/** @var array $record */
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати запис у таблиці: <?= htmlspecialchars($table) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Редагувати запис у таблиці: <?= htmlspecialchars($table) ?></h1>
    <form method="post" action="" enctype="multipart/form-data">
        <?php if ($table === 'categories'): ?>
            <?php if (!empty($record['CategoryImage'])): ?>
                <div class="mb-3">
                    <label class="form-label">Поточне фото:</label><br>
                    <img src="<?= htmlspecialchars($record['CategoryImage']) ?>" alt="Фото категорії" style="max-width:150px; max-height:150px;">
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="CategoryImage" class="form-label">Змінити фото категорії</label>
                <input type="file" class="form-control" id="CategoryImage" name="CategoryImage" accept="image/*">
            </div>
        <?php endif; ?>
        <?php foreach ($record as $key => $value): ?>
            <div class="mb-3">
                <label for="<?= htmlspecialchars($key) ?>" class="form-label"><?= htmlspecialchars($key) ?></label>
                <input type="text" class="form-control" id="<?= htmlspecialchars($key) ?>" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Зберегти</button>
    </form>
</div>
</body>
</html>