<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 12:39
 */

namespace Unit\Sviaz\Processors\AotGraph\CollocationManager;


class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testLaunch()
    {
        \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager::createDefault();
    }

    public function testRun()
    {
        $collocation_manager = \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager::create();

        $factory = $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\IFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['createCollocationCandidatesByGraph'])
            ->getMock();
        $container_collocations = [
            $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation::class)
                ->disableOriginalConstructor()
                ->getMock()
        ];
        $factory->expects($this->at(0))
            ->method('createCollocationCandidatesByGraph')
            ->willReturn($container_collocations);


        $filter = $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate\IFilter::class)
            ->disableOriginalConstructor()
            ->setMethods(['filter'])
            ->getMock();
        $filter->expects($this->at(0))
            ->method('filter')
            ->willReturn($container_collocations);


        $substitute = $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation\ISubstitute::class)
            ->disableOriginalConstructor()
            ->setMethods(['run'])
            ->getMock();
        $substitute->expects($this->at(0))
            ->method('run');

        $collocation_manager->setFactoryCollocationCandidates($factory);
        $collocation_manager->addFiltersCollocationCandidate($filter);
        $collocation_manager->setSubstituteWordsInCollocation($substitute);


        $graph = $this->getMockBuilder(\Aot\Graph\Slovo\Graph::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collocation_manager->run($graph);
    }
}
