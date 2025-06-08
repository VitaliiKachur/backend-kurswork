<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Products;
use models\Categories;

class ProductController extends Controller
{
    public function actionIndex()
    {
        try {
            $products = Products::getAllProducts();
            
            if ($products === null) {
                $products = [];
            }
            
            $this->template->setParams([
                'products' => $products,
                'title' => 'Всі товари'
            ]);
            
            return $this->render();
            
        } catch (\Exception $e) {
            error_log("ProductController Error: " . $e->getMessage());
            $this->addErrorMessage('Не вдалося завантажити товари. Спробуйте пізніше.');
            
            $this->template->setParams([
                'products' => [],
                'title' => 'Товари'
            ]);
            
            return $this->render();
        }
    }
    
    public function actionView()
    {
        try {
            $categoryId = $this->get->category_id;
            
            if (empty($categoryId)) {
                $this->addErrorMessage('Категорія не вказана.');
                return $this->redirect('/site/category');
            }
            
            $category = Categories::getCategoryById($categoryId);
            
            if (!$category) {
                $this->addErrorMessage('Категорія не знайдена.');
                return $this->redirect('/site/category');
            }
            
            $searchTerm = $this->get->search ?? '';
            $priceMin = $this->get->price_min ?? null;
            $priceMax = $this->get->price_max ?? null;
            $sortOrder = $this->get->sort ?? '';
            
            error_log('Search parameters: ' . print_r([
                'search' => $searchTerm,
                'price_min' => $priceMin,
                'price_max' => $priceMax,
                'sort' => $sortOrder,
                'category_id' => $categoryId
            ], true));
            
            $products = Products::getProductsByCategoryWithFilters(
                $categoryId, 
                $searchTerm, 
                $priceMin, 
                $priceMax, 
                $sortOrder
            );
            
            if ($products === null) {
                $products = [];
            }
            
            $this->template->setParams([
                'products' => $products,
                'category' => $category,
                'title' => 'Товари в категорії: ' . $category['CategoryName'],
                'search_params' => [
                    'search' => $searchTerm,
                    'price_min' => $priceMin,
                    'price_max' => $priceMax,
                    'sort' => $sortOrder,
                    'category_id' => $categoryId
                ]
            ]);
            
            return $this->render();
            
        } catch (\Exception $e) {
            error_log("ProductController View Error: " . $e->getMessage());
            $this->addErrorMessage('Не вдалося завантажити товари категорії. Спробуйте пізніше.');
            
            $this->template->setParams([
                'products' => [],
                'category' => null,
                'title' => 'Товари категорії',
                'search_params' => []
            ]);
            
            return $this->render();
        }
    }
    
    public function actionDetail()
    {
        try {
            $productId = $this->get->id;
            
            if (empty($productId)) {
                $this->addErrorMessage('Товар не вказано.');
                return $this->redirect('/site/product');
            }
            
            $product = Products::getProductById($productId);
            
            if (!$product) {
                $this->addErrorMessage('Товар не знайдено.');
                return $this->redirect('/site/product');
            }
            
            $category = Categories::getCategoryById($product['CategoryID']);
            
            $this->template->setParams([
                'product' => $product,
                'category' => $category,
                'title' => $product['ProductName']
            ]);
            
            return $this->render();
            
        } catch (\Exception $e) {
            error_log("ProductController Detail Error: " . $e->getMessage());
            $this->addErrorMessage('Не вдалося завантажити інформацію про товар. Спробуйте пізніше.');
            
            return $this->redirect('/site/product');
        }
    }

    public function actionImage()
    {
        $imagePath = $this->get->path ?? '';
        if (empty($imagePath)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $fullPath = 'assets/images/products/' . basename($imagePath);
        if (!file_exists($fullPath)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $mime = mime_content_type($fullPath);
        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($fullPath));
        readfile($fullPath);
        exit;
    }
}