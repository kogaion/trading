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
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = ['screen:bonds', 'screen:shares', 'screen:portfolio'];
        foreach ($commands as $command) {
            $this->getApplication()->find($command)->run(new ArrayInput([]), $output);
        }
    }
}