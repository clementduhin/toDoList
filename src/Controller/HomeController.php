<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ObjectManager $monManager): Response
    {

        $repoToDo = $this->getDoctrine()->getRepository(Todo::class);
        $allToDo = $repoToDo->findAll();
        $user = $this->getUser();
        if ($user) {
            $toDoUser = $user->getTodos();
        }

        $newToDo = new Todo;
        $formNewTodo = $this->createForm(TodoType::class, $newToDo);
        $formNewTodo->handleRequest($request);
        if ($formNewTodo->isSubmitted() && $formNewTodo->isValid()) {
            $newToDo->setCreatedAt(new \DateTime());
            $monManager->persist($newToDo);
            $user = $this->getUser();
            $newToDo->addUser($user);
            $monManager->flush();
        }

        if (isset($toDoUser)) {
            return $this->render('home/index.html.twig', [
                'todos' => $toDoUser,
                'formNewTodo' => $formNewTodo->createView()
            ]);
        }

        return $this->render('home/index.html.twig');
    }
}
