<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Admin;

class AdminController extends Controller
{
    private function checkAdminAccess(): bool
    {
        $customer = Core::get()->session->get('customer');
        return !empty($customer) && $customer['is_admin'];
    }

    public function actionIndex()
    {
        if (!$this->checkAdminAccess()) {
            $this->addErrorMessage('Доступ заборонено');
            return $this->redirect('/profile/login');
        }

        $tables = ['customers', 'products', 'categories', 'orders', 'order_items', 'cart'];
        $selectedTable = $_GET['table'] ?? $tables[0];
        $tableData = [];

        $adminModel = new Admin();
        if (in_array($selectedTable, $tables)) {
            $tableData = $adminModel->getTableData($selectedTable);
        }

        $this->template->setParams([
            'title' => 'Адмін-панель',
            'tables' => $tables,
            'selectedTable' => $selectedTable,
            'tableData' => $tableData
        ]);

        return $this->render('views/admin/index.php');
    }

    public function actionUpdate()
    {
        if (!$this->checkAdminAccess()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Доступ заборонено'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
            $selectedTable = $_GET['table'] ?? null;
            if (!$selectedTable) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Таблиця не вказана'
                ]);
                exit;
            }

            $primaryKey = $this->getPrimaryKey($selectedTable);
            $adminModel = new Admin();
            $result = $adminModel->updateRecords($selectedTable, $_POST['data'], $primaryKey);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Зміни успішно збережено' : implode('<br>', $result['errors']),
                'errors' => $result['errors']
            ]);
            exit;
        }
    }
    public function actionDelete()
    {
        if (!$this->checkAdminAccess()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Доступ заборонено'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Метод не підтримується. Використовуйте POST.'
            ]);
            exit;
        }

        $selectedTable = $_POST['table'] ?? null;
        $id = $_POST['id'] ?? null;

        error_log("Attempting to delete from table: " . $selectedTable . ", ID: " . $id);

        if (!$selectedTable || !$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Не вказано таблицю або ID'
            ]);
            exit;
        }

        $primaryKey = $this->getPrimaryKey($selectedTable);
        error_log("Primary key for table {$selectedTable}: {$primaryKey}");

        $adminModel = new Admin();

        try {
            error_log("Starting deletion process...");

            if ($selectedTable === 'products') {
                $db = Core::get()->db;
                $checkSql = "SELECT COUNT(*) as count FROM order_items WHERE ProductID = :id";
                $sth = $db->pdo->prepare($checkSql);
                $sth->bindValue(':id', $id);
                $sth->execute();
                $result = $sth->fetch();

                if ($result && $result['count'] > 0) {
                    throw new \Exception('Неможливо видалити продукт, оскільки він використовується в замовленнях');
                }
            }

            $result = $adminModel->deleteRecord($selectedTable, $primaryKey, $id);
            error_log("Delete result: " . ($result ? "success" : "failed"));

            if ($result === false) {
                throw new \Exception('Помилка при видаленні запису з бази даних');
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Запис успішно видалено'
            ]);
        } catch (\Exception $e) {
            error_log("Delete error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Помилка при видаленні: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function actionGetTableStructure()
    {
        if (!$this->checkAdminAccess()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Доступ заборонено'
            ]);
            exit;
        }

        $table = $_GET['table'] ?? null;
        if (!$table) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Не вказано таблицю'
            ]);
            exit;
        }

        try {
            $structure = $this->getTableStructure($table);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'structure' => $structure
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function actionCreate()
    {
        error_log('FILES: ' . print_r($_FILES, true));
        error_log('POST: ' . print_r($_POST, true));

        error_log("AdminController::actionCreate() started");

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        error_log("Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));

        if (!$this->checkAdminAccess()) {
            error_log("Access denied: User is not admin");
            echo json_encode([
                'success' => false,
                'message' => 'Доступ заборонено'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
            echo json_encode([
                'success' => false,
                'message' => 'Метод не підтримується'
            ]);
            exit;
        }

        $rawInput = file_get_contents('php://input');
        error_log("Raw input: " . $rawInput);

        if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false) {
            error_log("Processing multipart/form-data request");
            $table = $_POST['table'] ?? null;
            if ($table === 'categories') {
                $data = $_POST;
                unset($data['table']);
            } else {
                $data = $_POST['data'] ?? [];
                if (is_string($data)) {
                    $data = json_decode($data, true);
                }
            }

            if ($table === 'products' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                error_log("Processing product image upload");
                $uploadDir = __DIR__ . '/../assets/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $data['ImageURL'] = '/site/assets/images/products/' . $fileName;
                    error_log("Image uploaded successfully: " . $data['ImageURL']);
                } else {
                    error_log("Failed to upload image");
                    echo json_encode([
                        'success' => false,
                        'message' => 'Не вдалося зберегти файл зображення'
                    ]);
                    exit;
                }
            }
            if ($table === 'categories' && isset($_FILES['CategoryImage']) && $_FILES['CategoryImage']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/images/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['CategoryImage']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . basename($_FILES['CategoryImage']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['CategoryImage']['tmp_name'], $targetPath)) {
                    $data['CategoryImage'] = '/site/assets/images/categories/' . $fileName;
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Не вдалося зберегти файл зображення категорії'
                    ]);
                    exit;
                }
            }
            if ($table === 'customers' && isset($_FILES['data']['ProfilePhoto']) && $_FILES['data']['ProfilePhoto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/images/profile/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['data']['ProfilePhoto']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('profile_', true) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['data']['ProfilePhoto']['tmp_name'], $targetPath)) {
                    $data['ProfilePhoto'] = '/site/assets/images/profile/' . $fileName;
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Не вдалося зберегти фото профілю користувача'
                    ]);
                    exit;
                }
            }
        } else {
            error_log("Processing JSON request");
            $jsonData = json_decode($rawInput, true);
            $table = $jsonData['table'] ?? null;
            $data = $jsonData['data'] ?? [];
        }

        error_log("Table: " . ($table ?? 'not set'));
        error_log("Data: " . print_r($data, true));

        if (!$table) {
            error_log("Table not specified");
            echo json_encode([
                'success' => false,
                'message' => 'Не вказано таблицю'
            ]);
            exit;
        }

        try {
            $adminModel = new Admin();

            switch ($table) {
                case 'customers':
                    if (empty($data['Email'])) {
                        throw new \Exception('Email обов\'язковий для користувача');
                    }
                    if (empty($data['Password'])) {
                        throw new \Exception('Пароль обов\'язковий для користувача');
                    }
                    if (empty($data['FirstName'])) {
                        throw new \Exception('Ім\'я обов\'язкове для користувача');
                    }
                    if (empty($data['LastName'])) {
                        throw new \Exception('Прізвище обов\'язкове для користувача');
                    }

                    $existingUsers = $adminModel->getTableData('customers', '*', ['Email' => $data['Email']]);
                    if (!empty($existingUsers)) {
                        throw new \Exception('Користувач з таким email вже існує');
                    }

                    $data['is_admin'] = $data['is_admin'] ?? 0;
                    $data['RegistrationDate'] = date('Y-m-d H:i:s');
                    break;

                case 'categories':
                    if (empty($data['CategoryName'])) {
                        throw new \Exception('Назва категорії обов\'язкова');
                    }
                    break;

                case 'products':
                    if (empty($data['ProductName'])) {
                        throw new \Exception('Назва продукту обов\'язкова');
                    }
                    if (empty($data['CategoryID'])) {
                        throw new \Exception('Категорія обов\'язкова');
                    }
                    if (!isset($data['Price']) || $data['Price'] === '') {
                        throw new \Exception('Ціна обов\'язкова');
                    }
                    if (!isset($data['StockQuantity']) || $data['StockQuantity'] === '') {
                        throw new \Exception('Кількість на складі обов\'язкова');
                    }

                    $data['Price'] = floatval($data['Price']);
                    $data['StockQuantity'] = intval($data['StockQuantity']);
                    $data['CategoryID'] = intval($data['CategoryID']);
                    break;
            }

            error_log("Attempting to create record with data: " . print_r($data, true));
            $result = $adminModel->createRecord($table, $data);

            if ($result === false) {
                throw new \Exception('Помилка при створенні запису в базі даних');
            }

            error_log("Record created successfully");
            echo json_encode([
                'success' => true,
                'message' => 'Запис успішно створено',
                'id' => $result
            ]);

        } catch (\Exception $e) {
            error_log("Error creating record: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    private function getPrimaryKey(string $table): string
    {
        return match ($table) {
            'customers' => 'CustomerID',
            'categories' => 'CategoryID',
            'cart' => 'id',
            'orders' => 'OrderID',
            'order_items' => 'OrderItemID',
            'products' => 'ProductID',
            default => 'id',
        };
    }

    private function getTableStructure($table)
    {
        $structure = [];

        switch ($table) {
            case 'categories':
                $structure = [
                    [
                        'name' => 'CategoryName',
                        'label' => 'Назва категорії',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'name' => 'CategoryDescription',
                        'label' => 'Опис',
                        'type' => 'textarea',
                        'required' => false
                    ],
                    [
                        'name' => 'CategoryImage',
                        'label' => 'Фото',
                        'type' => 'file',
                        'required' => false
                    ]
                ];
                break;

            case 'products':
                $adminModel = new Admin();
                $categories = $adminModel->getTableData('categories');
                $categoryOptions = array_map(function ($category) {
                    return [
                        'value' => $category['CategoryID'],
                        'label' => $category['CategoryName']
                    ];
                }, $categories);

                $structure = [
                    [
                        'name' => 'ProductName',
                        'label' => 'Назва продукту',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'name' => 'CategoryID',
                        'label' => 'Категорія',
                        'type' => 'select',
                        'required' => true,
                        'options' => $categoryOptions
                    ],
                    [
                        'name' => 'Price',
                        'label' => 'Ціна',
                        'type' => 'number',
                        'required' => true
                    ],
                    [
                        'name' => 'StockQuantity',
                        'label' => 'Кількість на складі',
                        'type' => 'number',
                        'required' => true
                    ],
                    [
                        'name' => 'DescriptionProduct',
                        'label' => 'Опис продукту',
                        'type' => 'textarea',
                        'required' => false
                    ],
                    [
                        'name' => 'Image',
                        'label' => 'Фото',
                        'type' => 'file',
                        'required' => false
                    ]
                ];
                break;

            case 'customers':
                $structure = [
                    [
                        'name' => 'FirstName',
                        'label' => "Ім'я",
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'name' => 'LastName',
                        'label' => 'Прізвище',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'name' => 'Email',
                        'label' => 'Email',
                        'type' => 'email',
                        'required' => true
                    ],
                    [
                        'name' => 'Phone',
                        'label' => 'Телефон',
                        'type' => 'tel',
                        'required' => false
                    ],
                    [
                        'name' => 'AddressClient',
                        'label' => 'Адреса',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'name' => 'City',
                        'label' => 'Місто',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'name' => 'DateOfBirth',
                        'label' => 'Дата народження',
                        'type' => 'date',
                        'required' => false
                    ],
                    [
                        'name' => 'Password',
                        'label' => 'Пароль',
                        'type' => 'password',
                        'required' => true
                    ]
                ];
                break;
        }

        return $structure;
    }
}