<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnused */

/**
 * Created by PhpStorm.
 * User: david.ramentol@gmail.com
 * Date: 15/11/2019
 * Time: 10:51
 */

namespace App\Command;


use App\Elevator\Controller;
use App\Model\Cell;
use App\Model\Request;
use App\Model\Row;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Exception;
use IteratorAggregate;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Traversable;

class SimulatorCommand extends Command implements IteratorAggregate
{
    protected static $defaultName = 'app:simulator:run';

    /**
     * @var Controller
     */
    private $controller;

    protected function configure()
    {
        $this->controller = new Controller();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Time', 'Elevator 1 (current / total)', 'Elevator 2 (current / total)', 'Elevator 3 (current / total)']);

        /** @var Row $row */
        foreach ($this->getIterator() as $row) {

            $table->addRow([
                $row->getTime()->format('H:i'),
                '    ' . $row->getCells()[0]->getCurrentFloor() . ' / ' . $row->getCells()[0]->getTraveledFloors(),
                '    ' . $row->getCells()[1]->getCurrentFloor() . ' / ' . $row->getCells()[1]->getTraveledFloors(),
                '    ' . $row->getCells()[2]->getCurrentFloor() . ' / ' . $row->getCells()[2]->getTraveledFloors(),
            ]);
        }

        $table->render();
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     * @throws Exception
     */
    public function getIterator()
    {
        $requests = $this->generateSequence();

        $oneMinute = DateInterval::createFromDateString('1 minute');
        for ($time = new DateTime('today 9:00'); $time <= new DateTime('today 20:00'); $time->add($oneMinute)) {

            $row = new Row();
            $row->setTime($time);

            foreach ($this->controller->getElevators() as $elevator) {
                $cell = new Cell($elevator->getId(), $elevator->getCurrentFloor(), $elevator->getFloorsTraveled());
                $row->addCell($cell);
            }

            yield $row;

            $currentRequests = array_filter($requests, function (Request $request) use ($time) {
                return $request->getTime() <= $time;
            });

            foreach ($currentRequests as $request) {
                if ($this->controller->call($request, DateTimeImmutable::createFromMutable($time))
                    && null !== ($key = array_search($request, $requests))) {
                    unset($requests[$key]);
                }
            }
        }
    }

    /**
     * @return Request[]
     * @throws Exception
     */
    private function generateSequence()
    {
        /** @var Request[] $sequence */
        $sequence = [];
        $config = $this->parseConfigFile();

        foreach ($config['sequences'] as $item) {
            $this->processConfigItem($item, $sequence);
        }

        return $sequence;
    }

    private function parseConfigFile()
    {
        $fileLocator = new FileLocator([dirname(__DIR__) . '/../config']);
        $yamlSequenceFile = $fileLocator->locate('sequences.yaml', null, true);
        return Yaml::parseFile($yamlSequenceFile);
    }

    /**
     * @param array $config
     * @param array $sequence
     * @throws Exception
     */
    private function processConfigItem(array $config, array &$sequence)
    {
        $interval = DateInterval::createFromDateString($config['interval'] . ' minutes');

        $start = new DateTime('today ' . $config['start']);
        $end = new DateTime('today ' . $config['end']);

        $calls = $config['calls'];
        $destinations = $config['destinations'];

        for ($current = $start; $current <= $end; $current->add($interval)) {
            foreach ($calls as $call) {
                $sequence[] = new Request(DateTimeImmutable::createFromMutable($current), $call, $destinations);
            }
        }
    }
}