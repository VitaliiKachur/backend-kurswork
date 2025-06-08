<?php

namespace controllers;
use core\Template;
use core\Controller;

class ChatController extends Controller
{
    public function actionIndex()
    {
        return $this->render("views/profile/index.php");
    }
}