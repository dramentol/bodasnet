<?php
/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 11:44
 */

namespace App\Model;


use DateTimeInterface;

class Row
{
    /**
     * @var DateTimeInterface
     */
    private $time;

    /**
     * @var Cell[]
     */
    private $cells = [];

    /**
     * @return DateTimeInterface
     */
    public function getTime(): DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @param DateTimeInterface $time
     */
    public function setTime(DateTimeInterface $time): void
    {
        $this->time = $time;
    }

    /**
     * @return Cell[]
     */
    public function getCells(): array
    {
        return $this->cells;
    }

    /**
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        $this->cells[$cell->getId()] = $cell;
    }
}