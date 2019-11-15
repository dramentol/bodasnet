<?php
/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 11:21
 */

namespace App\Elevator;


use App\Model\Elevator;
use App\Model\Request;
use DateTimeImmutable;

class Controller
{
    public const NUM_ELEVATORS = 3;

    /**
     * @var Elevator[]
     */
    private $elevators = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        for ($i = 0; $i < self::NUM_ELEVATORS; $i++) {
            $this->elevators[] = new Elevator($i);
        }
    }

    /**
     * @param Request $request
     * @param DateTimeImmutable $currentTime
     * @return bool
     */
    public function call(Request $request, DateTimeImmutable $currentTime): bool
    {
        $elevator = $this->nearestAvailableElevator($currentTime, $request->getCallFloor());
        if ($elevator) {
            $elevator->setLastCallTime($currentTime);

            $elevator->goToFloor($request->getCallFloor());

            foreach ($request->getDestinationFloors() as $floor) {
                $elevator->goToFloor($floor);
            }
        }

        return !!$elevator;
    }

    /**
     * @param DateTimeImmutable $time
     * @param int $floor
     * @return Elevator|null
     */
    private function nearestAvailableElevator(DateTimeImmutable $time, int $floor): ?Elevator
    {
        /** @var Elevator[] $available */
        $available = array_filter($this->elevators, function (Elevator $elevator) use ($time) {
            return !$elevator->getLastCallTime() || $elevator->getLastCallTime() < $time;
        });

        usort($available, function (Elevator $a, Elevator $b) use ($floor) {
            return abs($a->getCurrentFloor() - $floor) - abs($b->getCurrentFloor() - $floor);
        });

        return $available ? $available[0] : null;
    }

    /**
     * @return Elevator[]
     */
    public function getElevators(): array
    {
        return $this->elevators;
    }
}