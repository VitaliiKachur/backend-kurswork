<?php

namespace controllers;
use core\Template;
use core\Controller;
use core\Config;
use models\Categories;
use models\Products;

class HomeController extends Controller
{
    public function actionIndex()
    {
        $promoCategory = Categories::getCategoryByName('Акції');
        $promoProducts = [];
        if ($promoCategory) {
            $promoProducts = Products::getProductsByCategory($promoCategory['CategoryID']);
        }
        $this->template->setParams([
            'promoProducts' => $promoProducts
        ]);
        return $this->render();
    }
    public function actionAbout()
    {
        $config = Config::get();
        $this->template->setParams(
            [
            'admin' => $config->admin,
            'title' => $config->title
            ]);
    return $this->render();
    }
}
