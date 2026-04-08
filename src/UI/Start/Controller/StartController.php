<?php

namespace App\UI\Start\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/', name: 'shop_')]
class StartController extends AbstractController
{
    #[Route('', name: 'start', methods: ['GET'])]
    public function productList(): Response
    {
        return $this->render('@ui/Start/View/start_page.html.twig');
    }
}
