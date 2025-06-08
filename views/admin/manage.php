<?php
/** @var string $table */
/** @var array $data */
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління таблицею: <?= htmlspecialchars($table) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Управління таблицею: <?= htmlspecialchars($table) ?></h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <?php foreach (array_keys($data[0] ?? []) as $column): ?>
                <th><?= htmlspecialchars($column) ?></th>
            <?php endforeach; ?>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
                <td>
                    <a href="/site/admin/edit/<?= $table ?>/<?= $row['id'] ?>" class="btn btn-warning btn-sm">Редагувати</a>
                    <a href="/site/admin/delete/<?= $table ?>/<?= $row['id'] ?>" class="btn btn-danger btn-sm">Видалити</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/site/admin/add/<?= $table ?>" class="btn btn-success">Додати запис</a>
</div>
</body>
</html>