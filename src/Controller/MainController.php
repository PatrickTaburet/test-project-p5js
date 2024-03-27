<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Scene1;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/main.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
    * @Route("/sendData", name="send_data", methods={"POST"})
    */
    public function sendData(Request $request): Response
    {
        $color = $request->request->get('color');
        $weight = $request->request->get('weight');
        $numLine = $request->request->get('numLine');

        //utilisateur temporaire
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find(2);

        if ($color !== null && $weight !== null && $numLine !== null) {
            $data = new Scene1;
            $data ->setColor($color);
            $data ->setWeight($weight);
            $data ->setNumLine($numLine);
            $data ->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return new Response('Data received and saved successfully!', Response::HTTP_OK);
        } else {
            // Retourner une réponse d'erreur si des données sont manquantes
            return new Response('Error: Missing data!', Response::HTTP_BAD_REQUEST);
        }

    }
}