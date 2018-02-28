<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/28/2018
 * Time: 9:05 AM
 */

namespace AppBundle\Command;


use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScreenTradeville extends TradevilleCommand
{
    protected function configure()
    {
        $this->setName('screen:tradeville')
            ->setDescription('Get everything from Tradeville')
            ->setHelp('Get everything from Tradeville');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('screen:bonds');
        $returnCode = $command->run(new ArrayInput([]), $output);
    
        $command = $this->getApplication()->find('screen:shares');
        $returnCode = $command->run(new ArrayInput([]), $output);
    }
}