<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Statistique;
use App\Entity\User;
use App\Form\statByPlayer;
use App\Form\GameType;
use App\Form\StatByPlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/new/game", name="new_game")
     */
    public function newGame(ManagerRegistry $managerRegistry, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Le match a bien été crée !');
            return $this->redirectToRoute('admin_new_stats', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/newGame.html.twig', [
            "form" => $form->createView(),
            "users" => $users,
        ]);
    }

    /**
     * @Route("/new/stats", name="new_stats")
     */
    public function newStatByPlayer(ManagerRegistry $managerRegistry, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $statistiques = new Statistique();
        $form = $this->createForm(StatByPlayerType::class, $statistiques);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($statistiques);
            $entityManager->flush();

            $this->addFlash('success', 'Les stats du joueur ont bien été prises en compte !');
            return $this->redirectToRoute('admin_new_stats', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/statByPlayer.html.twig', [
            "form" => $form->createView(),
            'statistiques' => $statistiques,
        ]);

    }
}
