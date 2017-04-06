<?php

namespace AppCompile;

use AppCompile\Commands\Bower;
use AppCompile\Commands\Chars;
use AppCompile\Commands\Init;
use AppCompile\Commands\Template;
use AppCompile\Commands\UpdateVersion;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppCompile extends Application
{

    protected $config_file;

    public function __construct($version = '1.0.0')
    {
        parent::__construct("AppCompile: Compile haml, coffee and sass.", $version);

        $this->addCommands([
            new Init(),
            new Bower(),
            new UpdateVersion(),
            new Template(),
            new Chars()
        ]);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        // always show the version information except when the user invokes the help
        // command as that already does it
        if (false === $input->hasParameterOption(array('--help', '-h')) && null !== $input->getFirstArgument()) {
            $output->writeln($this->getLongVersion());
            $output->writeln('');
        }

        return parent::doRun($input, $output);
    }

}
