<?php

namespace APP\Controllers;

use Efx\Core\Controller\AbstractController;
use Efx\Core\Http\Response;

class HomeController extends AbstractController
{

    public function __construct(

    )
    {
    }

    public function index(): Response
    {
        return $this->render('home.index', [

        ]);
    }
}