<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
    * @Route("/")
    */
    public function showHome()
    {
        return $this->render('home.html.twig');
    }
}
