<?php
namespace core;

class Router
{
    protected string $route;
    protected const DEFAULT_ROUTE = 'home/index';
    protected const DEFAULT_ACTION = 'index';

    public function __construct(?string $route = null)
    {
        $this->route = $route ?: self::DEFAULT_ROUTE;
    }

    public function run(): ?array
    {
        $this->route = trim($this->route, "/ \t\n\r\0\x0B");

        if (strtolower($this->route) === 'index') {
            $this->redirect('/site/home');
            return null;
        }

        $parts = explode('/', $this->route);

        if (empty($parts[0])) {
            $parts = ['home', 'index'];
        }

        $moduleName = $parts[0];
        $actionName = $parts[1] ?? self::DEFAULT_ACTION;

        Core::get()->moduleName = $moduleName;
        Core::get()->actionName = $actionName;

        $controllerClass = 'controllers\\' . ucfirst($moduleName) . 'Controller';
        $methodName = 'action' . ucfirst($actionName);

        if (!class_exists($controllerClass)) {
            $this->error(404);
            return null;
        }

        $controllerObject = new $controllerClass();
        Core::get()->controllerObject = $controllerObject;

        if (!method_exists($controllerObject, $methodName)) {
            $this->error(404);
            return null;
        }

        $params = array_slice($parts, 2);
        return $controllerObject->$methodName($params);
    }


    public function done(): void
    {
        // Можна додати логування або іншу завершальну логіку
    }

    protected function error(int $code): void
    {
        http_response_code($code);

        $errorPage = "views/errors/{$code}.php";

        if (file_exists($errorPage)) {
            include $errorPage;
        } else {
            echo "{$code} - Помилка";
        }

        exit;
    }
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}