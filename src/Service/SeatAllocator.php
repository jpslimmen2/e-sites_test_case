<?php

namespace App\Service;

class SeatAllocator
{
    /**
     * @var array
     */
    private array $hashArray = [];

    /**
     * @var array
     */
    private array $groupsArray = [];

    /**
     * @var array
     */
    private array $assignedSeats = [];

    /**
     * @description filters provided seats based on occupation.
     * @param array $seats
     *
     * @return array
     */
    public function getAvailableSeats(array $seats) : array
    {
        return array_filter($seats, function($seat)
        {
            return !$seat->isOccupied();
        });
    }

    /**
     * @description checks if there is enough available seats for the amount of persons.
     * @param array $availableSeats
     * @param int   $amountOfPersons
     *
     * @return null|bool
     */
    public function checkAvailability(array $availableSeats, int $amountOfPersons): ?bool
    {
        if (count($availableSeats) < $amountOfPersons)
        {
            return null;
        }
        return true;
    }

    /**
     * @description Make seat groups.
     * @param array $seats
     * @param int   $AmountOfPersons
     *
     * @return array
     */
    public function groupSeats(array $seats, int $AmountOfPersons) : array
    {
        foreach ($seats as $key => $seat){
            if ($seat->isOccupied() === false)
            {
                $this->hashArray[] = $seat;
                if (count($this->hashArray)  >= $AmountOfPersons)
                {
                    if (count($this->hashArray) === 1)
                    {
                        $this->groupsArray[] = $this->hashArray;
                    }
                    break;
                }
            }

            if(count($this->hashArray) > 0)
            {
                $this->groupsArray[] = $this->hashArray;
                $this->hashArray = [];
                continue;
            }

            if ($key === count($seats) -1)
            {
                $this->groupsArray[] = $this->hashArray;
                $this->hashArray = [];
            }
        }
        return $this->groupsArray;
    }


    /**
     * @description Assigns the person to the best suiting seat.
     *
     * @param array $groupedSeats
     * @param int   $amountOfPersons
     *
     * @return array
     */
    public function assignSeats(array $groupedSeats ,int $amountOfPersons) : array
    {
        for ($i = 0; $i < count($groupedSeats); $i++)
        {
            $foundGroup = $groupedSeats[$i];

            for ($y = 0; $y < count($foundGroup); $y++)
            {
                $seat = $foundGroup[$y];
                $this->assignedSeats[] = $seat;
                $amountOfPersons--;

                if ($amountOfPersons <= 0)
                {
                    break;
                }
            }
            if ($amountOfPersons <= 0)
            {
                break;
            }
        }

        return $this->assignedSeats;
    }

}
