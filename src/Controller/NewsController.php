<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news_index")
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('news/index.html.twig');
    }
}
