<?php

namespace App\Controller;

use App\Repository\SeatRepository;
use App\Service\SeatAllocator;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class ReservationController extends AbstractController
{
    private SeatRepository $seatRepository;

    public function __construct(SeatRepository $seatRepository){
        $this->seatRepository = $seatRepository;
    }
    /**
     * @OA\Parameter(
     *     name="persons",
     *     in="path",
     *     description="Field for setting amount of persons",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="solution")
     *
     * @param SeatAllocator $seatAllocator
     * @param int           $amountOfPersons
     *
     * @return Response
     */
    #[Route('api/solution/{amountOfPersons}', name: 'app_reservation', methods: 'GET')]

    public function solution(SeatAllocator $seatAllocator, int $amountOfPersons) : response
    {
        $seats = $this->seatRepository->findAll();

        $availableSeats = $seatAllocator->getAvailableSeats($seats);

        if ($seatAllocator->checkAvailability($availableSeats ,$amountOfPersons))
        {
            $groupedSeats = $seatAllocator->groupSeats($seats, $amountOfPersons);
            $assignedSeats = $seatAllocator->assignSeats($groupedSeats, $amountOfPersons);
            return $this->json($assignedSeats);
        }

        return $this->json(null);
    }
}


