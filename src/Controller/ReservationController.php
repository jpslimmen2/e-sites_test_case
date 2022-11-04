<?php

namespace App\Controller;

use App\Repository\SeatRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private SeatRepository $seatRepository;

    public function __construct(SeatRepository $seatRepository){
        $this->seatRepository = $seatRepository;
    }

    #[Route('api/solution/{persons}', name: 'app_reservation', methods: 'GET')]
    /**
     * @OA\Parameter(
     *     name="persons",
     *     in="path",
     *     description="Field for setting amount of persons",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="solution")
     */
    public function solution(int $persons) : response{
        
        $allSeats = $this->seatRepository->findAll();

        $availableSeats = array_filter($allSeats, function($seat){
            return !$seat->isOccupied();
        });

        if (count($availableSeats) < $persons){
            return $this->json(null);
        }

        $hashArray = []; 
        $groupsArray = []; 

        foreach ($allSeats as $key => $seat){

            if ($seat->isOccupied() === false){
                array_push($hashArray, $seat);
                if (count($hashArray)  >= $persons){
                    break;
                }
            }
           
            else if(count($hashArray) > 0){
                array_push($groupsArray, $hashArray);
                $hashArray = [];
                continue;
            }

            if ($key === count($allSeats) -1 && count($hashArray) > 0){
                array_push($groupsArray, $hashArray);
                $hashArray = [];
            }
        }

        if (count($hashArray) > 0){
            return $this->json($hashArray);
        }

        for ($i = 0; $i < count($groupsArray); $i++){
            $foundGroup = $groupsArray[$i];

            for ($y = 0; $y < count($foundGroup); $y++){
                $seat = $foundGroup[$y];
                array_push($hashArray,$seat);
                $persons--;

                if ($persons <= 0){
                    break;
                }
            }
            if ($persons <= 0){
                break;
            }
        }
        
        return $this->json($hashArray);
    }
}


