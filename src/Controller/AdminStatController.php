<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Statistique;
use App\Entity\User;
use App\Form\GameType;
use App\Form\RegistrationFormType;
use App\Form\StatByPlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/admin", name="admin_")
 */

class AdminStatController extends AbstractController
{
    /**
     * @Route("/new/stats", name="new_stats")
     */
    public function newStatByPlayer(ManagerRegistry $managerRegistry, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $statistiques = new Statistique();
        $game = $this->getUser();
        $form = $this->createForm(StatByPlayerType::class, $statistiques);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statistiques);
            $entityManager->flush();

            $this->addFlash('success', 'Les stats du joueur ont bien été prises en compte !');
            return $this->redirectToRoute('admin_show_games_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/statByPlayer.html.twig', [
            "form" => $form->createView(),
            'statistiques' => $statistiques,
        ]);
    }
    /**
     * @Route("/list/games", name="show_games_list")
     */
    public function showGames(ManagerRegistry $managerRegistry)
    {
        $games = $managerRegistry->getRepository(Game::class)->findAll();
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $statistiques = $managerRegistry->getRepository(Statistique::class)->findAll();
        return $this->render('statistiques/showGames.html.twig', [
            'statistiques' => $statistiques,
            'games' => $games,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/game/{id}/players", name="show_players_game")
     */
    public function showPlayersInGame(ManagerRegistry $managerRegistry, Game $game)
    {
        $statistiques = $managerRegistry->getRepository(Statistique::class)->findByGame($game);

        return $this->render('statistiques/showPlayersInGames.html.twig', [
            'statistiques' => $statistiques,
            'game' => $game,
        ]);
    }

    /**
     * @Route("/game/{gameId}/player/{userId}/edit/stat/{statistiqueId}", name="edit_stats_player", methods={"GET", "POST"})
     * @ParamConverter("game", class="App\Entity\Game", options={"mapping": {"gameId": "id"}})
     * @ParamConverter("user", class="App\Entity\User", options={"mapping": {"userId": "id"}})
     * @ParamConverter("statistique", class="App\Entity\Statistique", options={"mapping": {"statistiqueId": "id"}})
     */
    public function editStatsPlayer(Request $request,Game $game, Statistique $statistique, User $user, EntityManagerInterface $entityManager) : Response
    {
       
        $form = $this->createForm(StatByPlayerType::class, $statistique);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Les statistiques du joueur ont bien été modifiées.');
            return  $this->redirectToRoute('admin_show_players_game', ['id' => $game->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('statistiques/_editStats.html.twig', [
            'statistique' => $statistique,
            'game' => $game,
            'user' => $user,
            'form'  => $form,
        ]);
    }

    /**
     * @Route("/player/{userId}/edit/stat/{statistiqueId}", name="delete_stats_player", methods={"POST"})
     * @ParamConverter("user", class="App\Entity\User", options={"mapping": {"userId": "id"}})
     * @ParamConverter("statistique", class="App\Entity\Statistique", options={"mapping": {"statistiqueId": "id"}})

     */
    public function deleteStats(Request $request, Statistique $statistique, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $statistique->getId(), $request->request->get('_token')))
        {
            $entityManager->remove($statistique);
            $entityManager->flush();
            $this->addFlash('success', 'Les statistiques ont bien été supprimées.');
        }
        return $this->redirectToRoute('admin_show_games_list', [], Response::HTTP_SEE_OTHER);
    }

}
