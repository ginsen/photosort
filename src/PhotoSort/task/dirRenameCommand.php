<?php
namespace PhotoSort\task;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use PhotoSort\lib\PhotoSort;

/**
 * Class dirRenameCommand
 *
 * @package PhotoSort\task
 */
class dirRenameCommand extends Command
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
                ->setName('dir:rename')
                ->setDescription('Rename and sort images of one directory by created date')
                ->setHelp('Rename and sort images of one directory by created date.')
                ->setDefinition(
                        new InputDefinition(array(
                            new InputArgument('source', InputArgument::REQUIRED, 'Path from source images directory'),
                            new InputOption('format', 'f', InputOption::VALUE_OPTIONAL, "Set format of image file name. It has two possible formats, 'time' or 'number', by default is 'time'. for example: '1970-03-31 22:10:00.jpg'"),
                        ))
                );
    }


    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $quiet   = ($output->getVerbosity() == OutputInterface::VERBOSITY_QUIET);
        $verbose = ($output->getVerbosity() == OutputInterface::VERBOSITY_VERBOSE);

        if (!$quiet) {
            $output->write('<info>'.$this->getApplication()->getName().'</info>');
            $output->write(' version <comment>'.$this->getApplication()->getVersion().'</comment> ');
            $output->writeln("by J.Ginés Hernández G. <jgines@gmail.com>\n");
        }

        $sourcePath = $input->getArgument('source');
        $formatType = $input->getOption('format');

        $sortImg = new PhotoSort($verbose);
        $items = $sortImg->rename($sourcePath, $formatType);

        if ($verbose) {
            if (count($items)) {
                $table = new Table($output);
                $table->setHeaders(array('Old File', 'New File'));
                $table->setRows($items);
                $table->render();
            }

            $output->writeln("\nCheers!\n");
        }

        return true;
    }
}