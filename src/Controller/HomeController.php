<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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

        if (isset($_POST['name'], $_POST['description'], $_POST['statut'], $_POST['limitedAt'])) {
            $newToDo->setCreatedAt(new \DateTime())
                ->setName($_POST['name'])
                ->setDescription($_POST['description'])
                ->setStatut($_POST['statut'])
                ->setLimitedAt(new \DateTime());

            $monManager->persist($newToDo);
            $user = $this->getUser();
            $newToDo->addUser($user);
            $monManager->flush();
        }

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

    /**
     * @Route("/viewToDo", name="viewToDo")
     */
    public function viewToDo(Request $request): Response
    {
        $repoToDo = $this->getDoctrine()->getRepository(Todo::class);
        $allToDo = $repoToDo->findAll();
        $user = $this->getUser();
        $newToDo = new Todo;

        $formNewTodo = $this->createForm(TodoType::class, $newToDo);
        $formNewTodo->handleRequest($request);
        if ($user) {
            $toDoUser = $user->getTodos();
        }
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            foreach ($toDoUser as $todo) {
                $data = array(
                    'name' => $todo->getName(),
                    'description' => $todo->getDescription(),
                    'limitedAt' => $todo->getLimitedAt(),
                    'statut' => $todo->getStatut()
                );
                $jsonData[$idx++] = $data;
            }
            return new JsonResponse($jsonData);
        } else {
            return $this->render('home/index.html.twig');
        }
    }
}
