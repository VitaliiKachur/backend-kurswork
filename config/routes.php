<?php

return [
    '/' => ['HomeController', 'index'],
    '/about' => ['HomeController', 'about'],
    
    '/admin' => ['AdminController', 'index'],
    '/admin/getData' => ['AdminController', 'getData'],
    '/admin/updateRecord' => ['AdminController', 'updateRecord'],
    '/admin/deleteRecord' => ['AdminController', 'deleteRecord'],
    '/admin/add' => ['AdminController', 'add'],
    '/admin/create' => ['AdminController', 'create'],
    
    '/category' => ['CategoryController', 'index'],
    '/category/view' => ['CategoryController', 'view'],
    
    '/product' => ['ProductController', 'index'],
    '/product/view' => ['ProductController', 'view'],
    '/product/detail' => ['ProductController', 'detail'],
    
    '/profile' => ['ProfileController', 'index'],
    '/profile/login' => ['ProfileController', 'login'],
    '/profile/register' => ['ProfileController', 'register'],
    '/profile/registered' => ['ProfileController', 'registered'],
    '/profile/logout' => ['ProfileController', 'logout'],
    '/profile/orders' => ['ProfileController', 'orders'],
    '/profile/update' => ['ProfileController', 'update'],
    
    '/cart' => ['CartController', 'index'],
    '/cart/add' => ['CartController', 'add'],
    '/cart/remove' => ['CartController', 'remove'],
    '/cart/update' => ['CartController', 'update'],
    '/cart/count' => ['CartController', 'count'],
    '/cart/checkout' => ['CartController', 'checkout'],
    '/cart/success' => ['CartController', 'success']
]; 