<?php
/** @var string $table */
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати запис до таблиці: <?= htmlspecialchars($table) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Додати запис до таблиці: <?= htmlspecialchars($table) ?></h1>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="table" value="categories">
        <div class="mb-3">
            <label for="CategoryName" class="form-label">Назва категорії</label>
            <input type="text" class="form-control" id="CategoryName" name="CategoryName" required>
        </div>
        <div class="mb-3">
            <label for="CategoryDescription" class="form-label">Опис</label>
            <textarea class="form-control" id="CategoryDescription" name="CategoryDescription"></textarea>
        </div>
        <div class="mb-3">
            <label for="CategoryImage" class="form-label">Фото категорії</label>
            <input type="file" class="form-control" id="CategoryImage" name="CategoryImage" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Додати</button>
    </form>
</div>
</body>
</html>