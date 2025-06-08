<?php

namespace controllers;
use core\Template;
use core\Controller;
use core\Core;
use models\Customers;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        $customer = Customers::isUserLogged() ? Core::get()->session->get('customer') : null;

        if (!$customer) {
            $this->addErrorMessage('Будь ласка, увійдіть для перегляду профілю');
            return $this->redirect('/site/profile/login');
        }

        $this->template->setParams([
            'customer' => $customer,
            'title' => 'Мій профіль'
        ]);

        return $this->render('views/profile/index.php');
    }

    public function actionLogin()
    {
        if (Customers::isUserLogged())
            return $this->redirect('/site');

        if ($this->isPost) {
            $customer = Customers::FindByEmailAndPassword($this->post->email, $this->post->password);
            if (!empty($customer)) {
                Customers::LoginUser($customer);
                return $this->redirect('/site');
            } else {
                $error_message = 'Неправильний email та/або пароль';
                $this->addErrorMessage($error_message);
            }
        }

        return $this->render();
    }

    public function actionRegister()
    {
        if (Customers::isUserLogged())
            return $this->redirect('/site');

        if ($this->isPost) {
            $customer = Customers::FindByEmail($this->post->email);
            if (!empty($customer)) {
                $this->addErrorMessage('Користувач із таким email вже існує!');
            }
            if (strlen($this->post->email) === 0)
                $this->addErrorMessage('Email не вказано!');
            if (strlen($this->post->password) === 0)
                $this->addErrorMessage('Пароль не вказано!');
            if (strlen($this->post->password2) === 0)
                $this->addErrorMessage('Пароль (ще раз) не вказано!');
            if ($this->post->password != $this->post->password2)
                $this->addErrorMessage('Паролі не співпадають!');
            if (strlen($this->post->first_name) === 0)
                $this->addErrorMessage('Ім\'я не вказано!');
            if (strlen($this->post->last_name) === 0)
                $this->addErrorMessage('Прізвище не вказано!');

            if (!$this->isErrorMessageExist()) {
                Customers::RegisterCustomer(
                    $this->post->first_name,
                    $this->post->last_name,
                    $this->post->email,
                    $this->post->password,
                    $this->post->phone,
                    $this->post->address,
                    $this->post->city,
                    $this->post->date_of_birth
                );
                return $this->redirect('/site/profile/registered');
            }
        }
        return $this->render();
    }

    public function actionRegistered()
    {
        if (Customers::isUserLogged())
            return $this->redirect('/site');
        return $this->render();
    }

    public function actionLogout()
    {
        Customers::LogoutUser();
        return $this->redirect('/site/profile/login');
    }
    public function actionOrders()
{
    $customer = \models\Customers::isUserLogged() ? \core\Core::get()->session->get('customer') : null;
    if (!$customer) {
        $this->addErrorMessage('Будь ласка, увійдіть для перегляду замовлень');
        return $this->redirect('/site/profile/login');
    }

    $orders = \core\Core::get()->db->select('orders', '*', ['CustomerID' => $customer['CustomerID']]);

    $this->template->setParams([
        'orders' => $orders,
        'title' => 'Мої замовлення'
    ]);
    return $this->render('views/profile/orders.php');
}

public function actionUpdate()
{
    $customer = Customers::isUserLogged() ? Core::get()->session->get('customer') : null;

    if (!$customer) {
        $this->addErrorMessage('Будь ласка, увійдіть для оновлення профілю');
        return $this->redirect('/site/profile/login');
    }

    if ($this->isPost) {
        $updatedData = [
            'FirstName' => $this->post->first_name,
            'LastName' => $this->post->last_name,
            'Email' => $this->post->email,
            'Phone' => $this->post->phone,
            'AddressClient' => $this->post->address,
            'City' => $this->post->city
        ];
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/site/assets/images/profile/';
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $fileName = 'profile_' . $customer['CustomerID'] . '_' . time() . '.' . $ext;
            $filePath = $uploadPath . $fileName;
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $filePath)) {
                $updatedData['ProfilePhoto'] = $uploadDir . $fileName;
            }
        }
        Core::get()->db->update('customers', $updatedData, ['CustomerID' => $customer['CustomerID']]);

        $customer = array_merge($customer, $updatedData);
        Core::get()->session->set('customer', $customer);
        $this->addErrorMessage('Дані успішно оновлено');
        return $this->redirect('/site/profile');
    }

    $this->template->setParams([
        'customer' => $customer,
        'title' => 'Оновлення профілю'
    ]);

    return $this->render('views/profile/index.php');
}
}