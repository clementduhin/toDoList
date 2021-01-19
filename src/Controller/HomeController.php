<?php

namespace App\Controller;

use App\Entity\Todo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {

        $repoToDo = $this->getDoctrine()->getRepository(Todo::class);
        $allToDo = $repoToDo->findAll();

        return $this->render('home/index.html.twig', [
            'todos' => $allToDo,
        ]);
    }
}
