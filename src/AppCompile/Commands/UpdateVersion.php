<?php

namespace AppCompile\Commands;

class UpdateVersion extends Command
{

    protected function configure()
    {
        $this
            ->setName('updateversion')
            ->setDescription('Update version in version.json');
    }

    protected function fire()
    {
        if (!$this->checkFile(Command::config_file)) {
            $this->error("No has inicializado el archivo de configuración. Ejecuta el comando init");
            return;
        }

        $version = $this->dir_root() . '/version.json';

        if (file_exists($version)) {
            $config = json_decode(file_get_contents($version), true);
            $config['version'] = $config['version'] + 0.01;
        } else {
            $config = [];
            $config['version'] = 0.01;
        }
        file_put_contents($version, json_encode($config, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    }

}