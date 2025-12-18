<?php

namespace App\Controller\Wallets;

use App\Entity\Wallet;
use App\Service\ExpenseService;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/wallets/{uid}', name: 'wallets_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])] Wallet $wallet,
        ExpenseService $expenseService,
        WalletService $walletService,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 15): Response
    {
        $connectedUser = $this->getUser();
        $xUserWallet = $walletService->getUserAccessOnWallet($connectedUser, $wallet);
        if(is_null($xUserWallet)) {
            $this->addFlash("error", "Vous n'avez pas accès à ce portefeuille.");
            return $this->redirectToRoute("wallets_list");
        }

        $expenses = $expenseService->findExpensesForWallet($wallet, $page, $limit);

        $nbTotalExpenses = $expenseService->countExpensesForWallet($wallet);
        $maxPaginationPage = ceil($nbTotalExpenses/$limit);

        return $this->render('wallets/show/index.html.twig', [
            'controller_name' => 'ShowController',
            'uid' => $wallet->getUid(),
            'expenses' => $expenses,
            'wallet' => $wallet,
            'maxPaginationPage' => $maxPaginationPage,
            'limit' => $limit,
            'page' => $page
        ]);
    }
}
