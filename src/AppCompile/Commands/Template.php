<?php

namespace AppCompile\Commands;

use Symfony\Component\Console\Input\InputArgument;

class Template extends Command
{

    protected function configure()
    {
        $this
            ->setName('template')
            ->setDescription('template parse')
            ->addArgument(
                'folder',
                InputArgument::OPTIONAL,
                '¿que carpeta?'
            )->addArgument(
                'file',
                InputArgument::OPTIONAL,
                '¿que archivo?'
            );
    }

    protected function fire()
    {
        if (!$this->checkFile(Command::config_file)) {
            $this->error("No has inicializado el archivo de configuración. Ejecuta el comando init");
            return;
        }

        $folder = $this->input->getArgument('folder');
        $file = $this->input->getArgument('file');

        $this->parseTemplate($folder, $file);

    }

    private function parseTemplate($folder, $file)
    {
        $compiler = $this->getPublic($folder);
        $path = $this->dir_root();
        if ($file) {
            $file_path = str_replace($compiler['files']['haml'], $compiler['dest'], $file);
            $path .= '/' . $file_path;
            $path = dirname($path);
            $file = basename($file, '.haml');
        } elseif ($folder) {
            $path .= '/' . $compiler['dest'];
        }
        var_dump($path, $file);
        new \Buuum\Template\Template($path, $file);
    }

    private function getPublic($folder)
    {
        foreach ($this->config['compiler_paths'] as $compiler) {
            if ($compiler['name'] == $folder) {
                return $compiler;
            }
        }
        return '';
    }

}