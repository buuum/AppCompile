<?php

namespace AppCompile\Commands;

class Bower extends Command
{

    protected function configure()
    {
        $this
            ->setName('bower')
            ->setDescription('install bower components in project');
    }

    protected function fire()
    {
        if (!$this->checkFile(Command::config_file)) {
            $this->error("No has inicializado el archivo de configuración. Ejecuta el comando init");
            return;
        }

        $this->changePaths();

    }

    private function changePaths()
    {
        $path = $this->dir_root() . '/' . $this->config['paths']['assets']['plugins'] . '/';
        $fontplugins = $this->config['bower'];

        foreach ($fontplugins as $folder => $plugin) {

            $folder_ = $path . $folder . '/';
            if (!empty($plugin['replaces']['files'])) {
                foreach ($plugin['replaces']['files'] as $name => $replaces) {
                    if (file_exists($folder_ . $name)) {
                        $output = file_get_contents($folder_ . $name);
                        foreach ($replaces as $replace => $new) {
                            $output = str_replace($replace, $new, $output);
                        }
                        file_put_contents($folder_ . $name, $output);
                    }
                }
            }

            if (!empty($plugin['renames'])) {
                foreach ($plugin['renames'] as $name => $rename) {
                    rename($folder_ . $name, $folder_ . $rename);
                }
            }

        }

    }

}