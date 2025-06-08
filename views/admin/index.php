<?php
/** @var array $tables */
/** @var string $selectedTable */
/** @var array $tableData */

use core\Core;

$primaryKey = match ($selectedTable) {
    'customers' => 'CustomerID',
    'categories' => 'CategoryID',
    'cart' => 'id',
    'orders' => 'OrderID',
    'order_items' => 'OrderItemID',
    'products' => 'ProductID',
    default => 'id',
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    header('Content-Type: application/json');
    $data = $_POST['data'];
    $errors = [];
    $success = true;
    
    foreach ($data as $id => $row) {
        if (!empty($id) && isset($row[$primaryKey])) {
            $result = Core::get()->db->update($selectedTable, $row, [$primaryKey => $id]);

        } else {
            $errors[] = "Пропущено первинний ключ для запису з ID: $id";
            $success = false;
        }
    }
    
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Зміни успішно збережено' : implode('<br>', $errors),
        'errors' => $errors
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-panel-container {
            background: linear-gradient(135deg, rgba(32, 37, 55, 0.98) 0%, rgba(41, 50, 78, 0.98) 100%);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            padding: 0px 30px 30px 30px;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .admin-panel-container h1,
        .admin-panel-container h2,
        .admin-panel-container label,
        .admin-panel-container th {
            color: #fff;
        }
        .admin-panel-container .form-select, .admin-panel-container .form-control {
            background: #23262b;
            color: #e0e0e0;
            border: 1px solid #444857;
            border-radius: 8px;
        }
        .admin-panel-container .form-select:focus, .admin-panel-container .form-control:focus {
            background: #23262b;
            color: #fff;
            border-color: #ffd700;
            box-shadow: 0 0 0 2px #ffd70033;
        }
        .admin-panel-container .btn-primary {
            background: linear-gradient(90deg, #ffd700 0%, #ffbc00 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            color: #23262b;
            transition: background 0.2s, color 0.2s;
        }
        .admin-panel-container .btn-primary:hover {
            background: linear-gradient(90deg, #ffbc00 0%, #ffd700 100%);
            color: #181a20;
        }
        .admin-panel-container .btn-success {
            background: #28a745;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .admin-panel-container .btn-danger {
            border-radius: 8px;
            font-weight: 600;
        }
        .admin-panel-container .table {
            background: #23262b;
            color: #e0e0e0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 0;
            min-width: 900px;
        }
        .admin-panel-container .table th, .admin-panel-container .table td {
            background: #23262b;
            color: #e0e0e0;
            border: 1px solid #444857;
            vertical-align: middle;
            min-width: 120px;
            padding: 12px 10px;
            font-size: 1.08rem;
            word-break: break-word;
        }
        .admin-panel-container .table th {
            background: #23262b;
            font-weight: 700;
            min-width: 130px;
            font-size: 1.12rem;
        }
        .admin-panel-container .table td:last-child, .admin-panel-container .table th:last-child {
            min-width: 150px;
        }
        .admin-panel-container .table input.form-control {
            background: #181a20;
            color: #ffd700;
            border: 1px solid #444857;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 15px;
        }
        .admin-panel-container .table-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        /* --- СТИЛІ ДЛЯ МОДАЛЬНОГО ВІКНА --- */
        .modal-content {
            background: #23262b;
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        }
        .modal-header, .modal-footer {
            border: none;
            background: #23262b;
        }
        .modal-title {
            color: #ffd700;
        }
        .modal-body {
            background: #23262b;
        }
        .modal .form-label {
            color: #ffd700;
        }
        .modal .form-control, .modal .form-select {
            background: #181a20;
            color: #ffd700;
            border: 1px solid #444857;
            border-radius: 8px;
        }
        .modal .form-control:focus, .modal .form-select:focus {
            background: #23262b;
            color: #fff;
            border-color: #ffd700;
            box-shadow: 0 0 0 2px #ffd70033;
        }
        .modal .btn-primary {
            background: linear-gradient(90deg, #ffd700 0%, #ffbc00 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            color: #23262b;
            transition: background 0.2s, color 0.2s;
        }
        .modal .btn-primary:hover {
            background: linear-gradient(90deg, #ffbc00 0%, #ffd700 100%);
            color: #181a20;
        }
        .modal .btn-secondary {
            border-radius: 8px;
        }
        .modal .btn {
            min-width: 100px;
        }
        .modal .mb-3 {
            margin-bottom: 1.2rem!important;
        }
        .admin-panel-container .alert {
            margin-top: 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        @media (max-width: 1200px) {
            .admin-panel-container .table th, .admin-panel-container .table td {
                min-width: 90px;
                font-size: 0.98rem;
                padding: 8px 6px;
            }
        }
        .admin-panel-container .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="container admin-panel-container mt-5">
    <h1 class="mb-4">Адмін-панель</h1>
    <form method="get" action="" id="tableSelectForm">
        <div class="mb-3">
            <label for="tableSelect" class="form-label">Оберіть таблицю</label>
            <select class="form-select" id="tableSelect" name="table">
                <?php foreach ($tables as $table): ?>
                    <option value="<?= htmlspecialchars($table) ?>" <?= $selectedTable === $table ? 'selected' : '' ?>>
                        <?= htmlspecialchars($table) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Показати</button>
    </form>

    <?php if (!empty($tableData)): ?>
        <h2 class="mt-4">Дані таблиці: <?= htmlspecialchars($selectedTable) ?></h2>
        <div id="messageAlert" class="alert" role="alert"></div>
        
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            Створити новий запис
        </button>

        <form method="post" action="" id="dataForm">
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <?php foreach (array_keys($tableData[0] ?? []) as $column): ?>
                        <th><?= htmlspecialchars($column) ?></th>
                    <?php endforeach; ?>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tableData as $row): ?>
                    <tr>
                        <?php foreach ($row as $key => $value): ?>
                            <td>
                                <input type="text" class="form-control" name="data[<?= $row[$primaryKey] ?? '' ?>][<?= htmlspecialchars((string)$key) ?>]" value="<?= htmlspecialchars((string)$value) ?>">
                            </td>
                        <?php endforeach; ?>
                        <td class="table-actions">
                            <?php if (isset($row[$primaryKey])): ?>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row[$primaryKey] ?>">Видалити</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <button type="submit" class="btn btn-success mt-3" id="saveBtn">
                Зберегти зміни
                <span class="loading" id="loadingIndicator">Оновлення...</span>
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Створити новий запис</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div id="createFormFields">
                    
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                <button type="button" class="btn btn-primary" id="saveNewRecord">Зберегти</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const currentTable = '<?= htmlspecialchars($selectedTable) ?>';
    
    $(document).ready(function() {
        $('#dataForm').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $alert = $('#messageAlert');
            var $saveBtn = $('#saveBtn');
            var $loading = $('#loadingIndicator');
            
            $alert.hide();
            $saveBtn.prop('disabled', true);
            $loading.show();
            
            $.ajax({
                type: 'POST',
                url: '',
                data: $form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $alert.removeClass('alert-danger').addClass('alert-success');
                    } else {
                        $alert.removeClass('alert-success').addClass('alert-danger');
                    }
                    
                    $alert.html(response.message).show();
                },
                error: function() {
                    $alert.removeClass('alert-success').addClass('alert-danger')
                        .html('Сталася помилка при збереженні даних').show();
                },
                complete: function() {
                    $saveBtn.prop('disabled', false);
                    $loading.hide();
                    
                    setTimeout(function() {
                        $alert.fadeOut();
                    }, 5000);
                }
            });
        });
        
        $('#tableSelect').on('change', function() {
            $('#tableSelectForm').submit();
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            deleteRecord(id);
        });

        $('#createModal').on('shown.bs.modal', function () {
            if (currentTable === 'customers') {
                $('#createFormFields input[name="ProfilePhoto"]').closest('.mb-3').remove();
            }
        });
    });    function deleteRecord(id) {
        if (!currentTable) {
            showAlert('danger', 'Не вибрано таблицю');
            return;
        }

        if (confirm('Ви впевнені, що хочете видалити цей запис?')) {
            $.ajax({
                type: 'POST',
                url: '/site/admin/delete',
                data: {
                    table: currentTable,
                    id: id
                },
                dataType: 'json',
                beforeSend: function() {
                    showAlert('info', 'Видалення...');
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        const row = $(`button.delete-btn[data-id="${id}"]`).closest('tr');
                        row.fadeOut(300, function() {
                            $(this).remove();
                            showAlert('success', response.message || 'Запис успішно видалено');
                        });
                    } else {
                        showAlert('danger', response.message || 'Помилка при видаленні запису');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr);
                    showAlert('danger', 'Помилка при видаленні запису: ' + error);
                }
            });
        }
    }

    function showAlert(type, message) {
        console.log('Showing alert:', { type, message });
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    function getTableStructure() {
        $.ajax({
            type: 'GET',
            url: '/site/admin/getTableStructure',
            data: {
                table: currentTable
            },
            success: function(response) {
                if (response.success) {
                    createFormFields(response.structure);
                } else {
                    showAlert('danger', 'Помилка отримання структури таблиці');
                }
            },
            error: function() {
                showAlert('danger', 'Помилка при запиті структури таблиці');
            }
        });
    }

    $('#saveNewRecord').on('click', function() {
        const $btn = $(this);
        if ($btn.prop('disabled')) {
            return;
        }
        const $form = $('#createForm');
        const formArray = $form.serializeArray();
        const formData = {};
        formArray.forEach(item => {
            if (item.value !== '') {
                formData[item.name] = item.value;
            }
        });
        if ((currentTable === 'products' || currentTable === 'categories') && $form.find('input[type="file"]').length) {
            const fd = new FormData();
            fd.append('table', currentTable);
            if (currentTable === 'products') {
                Object.keys(formData).forEach(key => {
                    fd.append('data[' + key + ']', formData[key]);
                });
                const fileInput = $form.find('input[type="file"]')[0];
                if (fileInput && fileInput.files.length > 0) {
                    fd.append('image', fileInput.files[0]);
                }
            } else if (currentTable === 'categories') {
                Object.keys(formData).forEach(key => {
                    fd.append(key, formData[key]);
                });
                const fileInput = $form.find('input[type="file"]')[0];
                if (fileInput && fileInput.files.length > 0) {
                    fd.append('CategoryImage', fileInput.files[0]);
                }
            }
            $btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: '/site/admin/create',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#createModal').modal('hide');
                        showAlert('success', response.message || 'Запис успішно створено');
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        showAlert('danger', response.message || 'Помилка при створенні запису');
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Помилка при створенні запису';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage += ': ' + (response.message || error);
                    } catch (e) {
                        errorMessage += ': ' + error;
                    }
                    showAlert('danger', errorMessage);
                    $btn.prop('disabled', false);
                }
            });
            return;
        }

        if (currentTable === 'customers' && $form.find('input[type="file"]').length) {
            const fd = new FormData();
            fd.append('table', currentTable);
            Object.keys(formData).forEach(key => {
                fd.append('data[' + key + ']', formData[key]);
            });
            const fileInput = $form.find('input[type="file"]')[0];
            if (fileInput && fileInput.files.length > 0) {
                fd.append('data[ProfilePhoto]', fileInput.files[0]);
            }
            $btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: '/site/admin/create',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#createModal').modal('hide');
                        showAlert('success', response.message || 'Запис успішно створено');
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        showAlert('danger', response.message || 'Помилка при створенні запису');
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Помилка при створенні запису';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage += ': ' + (response.message || error);
                    } catch (e) {
                        errorMessage += ': ' + error;
                    }
                    showAlert('danger', errorMessage);
                    $btn.prop('disabled', false);
                }
            });
            return;
        }
      
        $btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '/site/admin/create',
            contentType: 'application/json',
            data: JSON.stringify({
                table: currentTable,
                data: formData
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#createModal').modal('hide');
                    showAlert('success', response.message || 'Запис успішно створено');
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showAlert('danger', response.message || 'Помилка при створенні запису');
                    $btn.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Помилка при створенні запису';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage += ': ' + (response.message || error);
                } catch (e) {
                    errorMessage += ': ' + error;
                }
                showAlert('danger', errorMessage);
                $btn.prop('disabled', false);
            }
        });
    });

  
    function createFormFields(structure) {
        const formFields = $('#createFormFields');
        formFields.empty();
        
        console.log('Creating form fields with structure:', structure);

        structure.forEach(field => {
            let input = '';
            
            if (field.type === 'select' && field.options) {
                input = `<select class="form-select" name="${field.name}" ${field.required ? 'required' : ''}>
                    <option value="">Виберіть ${field.label}</option>
                    ${field.options.map(opt => `<option value="${opt.value}">${opt.label}</option>`).join('')}
                </select>`;
            } else if (field.type === 'textarea') {
                input = `<textarea class="form-control" name="${field.name}" ${field.required ? 'required' : ''}></textarea>`;
            } else if (field.type === 'number') {
                input = `<input type="number" step="0.01" class="form-control" name="${field.name}" ${field.required ? 'required' : ''}>`;
            } else {
                input = `<input type="${field.type}" class="form-control" name="${field.name}" ${field.required ? 'required' : ''}>`;
            }

            formFields.append(`
                <div class="mb-3">
                    <label class="form-label">${field.label}</label>
                    ${input}
                </div>
            `);
        });
    }


    $('#createModal').on('show.bs.modal', function () {
        getTableStructure();
    });

 
    $('#createModal').on('hidden.bs.modal', function () {
        $('#createForm')[0].reset();
        $('#createFormFields').empty();
        $('#saveNewRecord').prop('disabled', false);
    });
</script>
</body>
</html>