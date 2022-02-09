<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Statistique;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\StatistiqueRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/profile", name="stat_")
 */
class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="player")
     */
    public function showStatByPlayer(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager): Response
    {
        $gameRepository = $managerRegistry->getRepository(Game::class);
        $games = $gameRepository->findBy([], ['date'=>'DESC']);
        return $this->render('stat/statByPlayer.html.twig', [
            'games' => $games
        ]);
    }
    /**
     * @Route("/stats", name="players")
     */
    public function showStatAllPlayers(StatistiqueRepository $statistiqueRepository, ManagerRegistry $managerRegistry):Response
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $statistiqueRepository = $managerRegistry->getRepository(Statistique::class);

        return $this->render('stat/statAllPlayers.html.twig', [
            'users' => $users,
        ]);
    }
}
