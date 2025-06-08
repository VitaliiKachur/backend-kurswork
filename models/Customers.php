<?php
namespace models;

use core\Model;
use core\Core;

/**
* @property int $CustomerID ID клієнта
* @property string $FirstName Ім'я клієнта
* @property string $LastName Прізвище клієнта
* @property string $Email Email клієнта
* @property string $Phone Телефон клієнта
* @property string $AddressClient Адреса клієнта
* @property string $City Місто клієнта
* @property string $DateOfBirth Дата народження
* @property string $RegistrationDate Дата реєстрації
* @property string $Password Пароль клієнта
* @property string $ProfilePhoto Фото профілю
*/
class Customers extends Model
{
    public static $tableName = 'customers';
    protected static $primaryKey = 'CustomerID';

    public static function FindByEmailAndPassword($email, $password)
    {
        $rows = self::findByCondition(['Email' => $email, 'Password' => $password]);
        if(!empty($rows))
            return $rows[0];
        else
            return null;
    }

    public static function FindByEmail($email)
    {
        $rows = self::findByCondition(['Email' => $email]);
        if(!empty($rows))
            return $rows[0];
        else
            return null;
    }

    public static function isUserLogged()
    {
        return !empty(Core::get()->session->get('customer'));
    }

    public static function LoginUser($customer)
    {
        Core::get()->session->set('customer', $customer);
    }

    public static function LogoutUser()
    {
        Core::get()->session->remove('customer');
    }

    public static function RegisterCustomer($firstName, $lastName, $email, $password, $phone = null, $address = null, $city = null, $dateOfBirth = null)
    {
        $customer = new Customers();
        $customer->FirstName = $firstName;
        $customer->LastName = $lastName;
        $customer->Email = $email;
        $customer->Password = $password;
        $customer->Phone = $phone;
        $customer->AddressClient = $address;
        $customer->City = $city;
        $customer->DateOfBirth = $dateOfBirth;
        $customer->save();
        return $customer;
    }
}