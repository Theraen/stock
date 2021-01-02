<?php

namespace App\Controller;

use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{

    private $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * @Route("/log", name="log")
     */
    public function index(): Response
    {
        $logs = $this->logRepository->findAll();

        return $this->render('log/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}
