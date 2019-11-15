<?php
/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 11:16
 */

namespace App\Model;


use DateTimeImmutable as DateTime;

class Elevator
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $currentFloor = 0;

    /**
     * @var DateTime|null
     */
    private $lastCallTime;

    /**
     * @var int
     */
    private $floorsTraveled = 0;

    /**
     * Elevator constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function goToFloor(int $floor)
    {
        $this->floorsTraveled += abs($this->currentFloor - $floor);

        $this->currentFloor = $floor;
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
     * @return DateTime|null
     */
    public function getLastCallTime(): ?DateTime
    {
        return $this->lastCallTime;
    }

    /**
     * @param DateTime|null $lastCallTime
     */
    public function setLastCallTime(?DateTime $lastCallTime): void
    {
        $this->lastCallTime = $lastCallTime;
    }

    /**
     * @return int
     */
    public function getFloorsTraveled(): int
    {
        return $this->floorsTraveled;
    }
}