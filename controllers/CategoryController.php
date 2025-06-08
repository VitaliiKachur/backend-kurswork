<?php
namespace controllers;

use core\Controller;
use core\Core;

class CategoryController extends Controller
{
    public function actionIndex()
    {
        try {
            $categories = Core::get()->db->select('categories');

            if ($categories === null) {
                $categories = [];
            }

            $this->template->setParams([
                'categories' => $categories
            ]);
            
            return $this->render();
            
        } catch (\Exception $e) {

            error_log("CategoryController Error: " . $e->getMessage());
            
            $this->addErrorMessage('Не вдалося завантажити категорії. Спробуйте пізніше.');

            $this->template->setParams([
                'categories' => []
            ]);
            
            return $this->render();
        }
    }
}