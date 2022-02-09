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
/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/new/game/", name="new_game")
     */
    public function newGame(ManagerRegistry $managerRegistry, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        $game = new Game();
        $statistique = new Statistique();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($game->getUser() as $user){
                $statistique = new Statistique();
                $statistique->setUser($user);
                $statistique->setGame($game);
                $entityManager->persist($statistique);
            }
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Le match a bien été crée !');
            $this->addFlash('danger', 'Attention, les statistiques du joueur sont initialisées à 0, donc n\'oublie pas de modifier les statistiques de tous les joueurs présents au match !');
            return $this->redirectToRoute('admin_show_games_list',[], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/newGame.html.twig', [
            "form" => $form->createView(),
            "users" => $users,
        ]);
    }

    /**
     * @Route("/games", name="show_games")
     */
    public function showGames(ManagerRegistry $managerRegistry)
    {
        $games = $managerRegistry->getRepository(Game::class)->findAll();
        return $this->render('games/showGames.html.twig', [
            'games' => $games,
        ]);
    }

    /**
     * @Route("/game/{id}/edit", name="edit_game", methods={"GET", "POST"})
     */
    public function editGame(ManagerRegistry $managerRegistry, Request $request, Game $game, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $statistiques = $managerRegistry->getRepository(Statistique::class)->findByGame($game);
            $names = [];
            foreach ($statistiques as $stat) {
                $names[] = $stat->getUser()->getPseudo();
            }
            foreach ($game->getUser() as $user){
                if (!in_array($user->getPseudo(), $names)) {
                    $statistique = new Statistique();
                    $statistique->setUser($user);
                    $statistique->setGame($game);
                    $entityManager->persist($statistique);
                }
            }
            $entityManager->flush();

            $this->addFlash('success', 'Le match a bien été modifié.');
            return  $this->redirectToRoute('admin_show_games', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('games/_editGame.html.twig', [
            'game'  => $game,
            'form'     => $form,
        ]);
    }

    /**
     * @Route("/game/{id}/delete", name="delete_game", methods={"POST"})
     */
    public function deleteGame(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token')))
        {
            foreach ($game->getStatistiques() as $statistique) {
                $entityManager->remove($statistique);
            }
            $entityManager->remove($game);
            $entityManager->flush();
            $this->addFlash('success', 'Le match a bien été supprimé.');
        }
        return $this->redirectToRoute('admin_show_games', [], Response::HTTP_SEE_OTHER);
    }
}
