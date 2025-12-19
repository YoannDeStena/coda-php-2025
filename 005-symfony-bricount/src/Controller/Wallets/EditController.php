<?php

namespace App\Controller\Wallets;

use App\DTO\WalletDTO;
use App\Entity\Wallet;
use App\Form\WalletType;
use App\Service\WalletService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/wallets/{uid}/edit', name: 'wallets_edit', methods: ['GET', 'POST'])]
    public function index(#[MapEntity(mapping: ['uid' => 'uid'])] Wallet $wallet, Request $request, WalletService $walletService): Response
    {
        $xUserWallet = $walletService->getUserAccessOnWallet($this->getUser(), $wallet);
        if(is_null($xUserWallet) || $xUserWallet->getRole() != "admin") {
            // en cas d'erreur, ajout d'un message flash pour indiquer l'erreur
            $this->addFlash('error', "Vous n'avez pas la permission pour modifier ce portefeuille.");
            // redirection vers la page de création du wallet
            return $this->redirectToRoute('wallets_list');
        }

        $dto = WalletDTO::fromEntity($wallet);
        $form = $this->createForm(WalletType::class, $dto);
        // traitement du formulaire par symfony, validations, etc.
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération des données du formulaire sous forme de la DTO WalletDTO
            $dto = $form->getData();

            try {
                // traitements métier pour créer le wallet via le service WalletService
                $wallet = $walletService->updateWallet($dto, $wallet, $this->getUser());
            } catch (Exception $e) {
                // en cas d'erreur, ajout d'un message flash pour indiquer l'erreur
                $this->addFlash('error', 'Erreur lors de la modification du portefeuille');

                // redirection vers la page de création du wallet
                return $this->redirectToRoute('wallets_edit');
            }

            // ajout d'un message flash pour indiquer le succès de l'opération
            $this->addFlash('success', 'Portefeuille modifié avec succès !');

            // redirection vers le détail du wallet nouvellement modifié
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        return $this->render('wallets/edit/index.html.twig', [
            'form' => $form,
        ]);
    }
}
