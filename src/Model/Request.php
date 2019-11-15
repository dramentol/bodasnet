<?php
/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 11:21
 */

namespace App\Model;


use DateTimeImmutable;

class Request
{
    /**
     * @var DateTimeImmutable
     */
    private $time;

    /**
     * @var int
     */
    private $callFloor;

    /**
     * @var int[]
     */
    private $destinationFloors;

    /**
     * Call constructor.
     * @param DateTimeImmutable $time
     * @param int $callFloor
     * @param int[] $destinationFloor
     */
    public function __construct(DateTimeImmutable $time, int $callFloor, array $destinationFloor)
    {
        $this->time = $time;
        $this->callFloor = $callFloor;
        $this->destinationFloors = $destinationFloor;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getCallFloor(): int
    {
        return $this->callFloor;
    }

    /**
     * @return int[]
     */
    public function getDestinationFloors(): array
    {
        return $this->destinationFloors;
    }

}