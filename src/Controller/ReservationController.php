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
        //gets all seats
        $allSeats = $this->seatRepository->findAll();

        //filters all seats on availability
        $availableSeats = array_filter($allSeats, function($seat){
            return !$seat->isOccupied();
        });

        // returns null if amount of persons is higher than available seats
        if (count($availableSeats) < $persons){
            return $this->json(null);
        }

        $hashArray = []; // array with empty seats
        $groupsArray = []; // array with grouped seats

        // foreach for creating groups of free seats
        foreach ($allSeats as $key => $seat){

            //puts seat in hashArray when occupation is false and breaks if amount is higher or equal of amount of persons
            if ($seat->isOccupied() === false){
                array_push($hashArray, $seat);
                if (count($hashArray)  >= $persons){
                    break;
                }
            }
            //pushes the empty seats into a group array and resets it to create a new group
            else if(count($hashArray) > 0){
                array_push($groupsArray, $hashArray);
                $hashArray = [];
                continue;
            }

            //if the index is eqeul to allseats -1 and hashArray still has items create a new group for it.
            if ($key === count($allSeats) -1 && count($hashArray) > 0){
                array_push($groupsArray, $hashArray);
                $hashArray = [];
            }
        }

        // returns the array when a big enough gap has been found where everyone can fit in.
        if (count($hashArray) > 0){
            return $this->json($hashArray);
        }

        //assigns the group to different seats when there isn't enough space for everyone next to each other
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
        //returns json array of available seats
        return $this->json($hashArray);
    }
}


