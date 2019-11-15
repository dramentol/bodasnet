<?php
/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 11:44
 */

namespace App\Model;


class Cell
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $currentFloor;

    /**
     * @var int
     */
    private $traveledFloors;

    /**
     * Cell constructor.
     * @param int $id
     * @param int $currentFloor
     * @param int $traveledFloors
     */
    public function __construct(int $id, int $currentFloor, int $traveledFloors)
    {
        $this->id = $id;
        $this->currentFloor = $currentFloor;
        $this->traveledFloors = $traveledFloors;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCurrentFloor(): int
    {
        return $this->currentFloor;
    }

    /**
     * @return int
     */
    public function getTraveledFloors(): int
    {
        return $this->traveledFloors;
    }
}