<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use App\Entity\Statistique;
use App\Entity\User;
use App\Form\GameType;
use App\Form\RegistrationFormType;
use App\Form\StatByPlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/admin", name="admin_")
 */

class AdminPlayerController extends AbstractController
{
    /**
     * @Route("/players", name="show_players")
     */
    public function showPlayers(ManagerRegistry $managerRegistry)
    {
        $users = $managerRegistry->getRepository(User::class)->findAll();
        return $this->render('players/showPlayers.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/player/{id}/edit", name="edit_player", methods={"GET", "POST"})
     */
    public function editPlayer(Request $request, User $user, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le profil du joueur a bien été modifié.');
            return  $this->redirectToRoute('admin_show_players', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('players/_editPlayer.html.twig', [
            'user'  => $user,
            'form'  => $form,
        ]);
    }

    /**
     * @Route("/player/{id}/delete", name="delete_player", methods={"POST"})
     */
    public function deletePLayer(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token')))
        {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Le joueur a bien été supprimé.');
        }
        return $this->redirectToRoute('admin_show_players', [], Response::HTTP_SEE_OTHER);
    }

}
