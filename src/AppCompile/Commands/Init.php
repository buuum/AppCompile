<?php

namespace AppCompile\Commands;

class Init extends Command
{

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('initialize yaml configuration file');
    }

    protected function fire()
    {
        if ($file = $this->checkFile(Command::config_file)) {
            $this->error("El archivo de configuración ya está creado.");
            $overwrite = $this->choiceQuestion("¿Quieres sobreescribirlo?", ["Si", "No"]);
            if ($overwrite == "Si") {
                file_put_contents($this->getFile(Command::config_file),
                    file_get_contents($this->getDefault(Command::config_file)));
                $this->success("Archivo de configuración creado correctamente.");
            }
        } else {
            file_put_contents($this->getFile(Command::config_file),
                file_get_contents($this->getDefault(Command::config_file)));
            $this->success("Archivo de configuración creado correctamente.");
            $this->checkFile(Command::config_file);
        }

        if ($file = $this->checkFile(Command::grunt_file)) {
            $this->error("El archivo grunt ya está creado.");
            $overwrite = $this->choiceQuestion("¿Quieres sobreescribirlo?", ["Si", "No"]);
            if ($overwrite == "Si") {
                file_put_contents($this->getFile(Command::grunt_file),
                    file_get_contents($this->getDefault(Command::grunt_file)));
                $this->success("Archivo Grunt creado correctamente.");
            }
        } else {
            file_put_contents($this->getFile(Command::grunt_file),
                file_get_contents($this->getDefault(Command::grunt_file)));
            $this->success("Archivo Grunt creado correctamente.");
        }

        if ($file = $this->checkFile(Command::package_file)) {
            $this->error("El archivo package ya está creado.");
            $overwrite = $this->choiceQuestion("¿Quieres sobreescribirlo?", ["Si", "No"]);
            if ($overwrite == "Si") {
                file_put_contents($this->getFile(Command::package_file),
                    file_get_contents($this->getDefault(Command::package_file)));
                $this->success("Archivo Package creado correctamente.");
            }
        } else {
            file_put_contents($this->getFile(Command::package_file),
                file_get_contents($this->getDefault(Command::package_file)));
            $this->success("Archivo Package creado correctamente.");
        }

        if ($file = $this->checkFile(Command::css_file, true)) {
            $this->error("El archivo css ya está creado.");
            $overwrite = $this->choiceQuestion("¿Quieres sobreescribirlo?", ["Si", "No"]);
            if ($overwrite == "Si") {
                file_put_contents($this->getFile(Command::css_file, true),
                    file_get_contents($this->getDefault(Command::css_file)));
                $this->success("Archivo css creado correctamente.");
            }
        } else {

            $folder = $this->dir_root() . '/' . $this->config['paths']['assets']['root'];

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            file_put_contents($this->getFile(Command::css_file, true),
                file_get_contents($this->getDefault(Command::css_file)));
            $this->success("Archivo css creado correctamente.");
        }

        if ($file = $this->checkFile(Command::js_file, true)) {
            $this->error("El archivo js ya está creado.");
            $overwrite = $this->choiceQuestion("¿Quieres sobreescribirlo?", ["Si", "No"]);
            if ($overwrite == "Si") {
                file_put_contents($this->getFile(Command::js_file, true),
                    file_get_contents($this->getDefault(Command::js_file)));
                $this->success("Archivo js creado correctamente.");
            }
        } else {
            file_put_contents($this->getFile(Command::js_file, true),
                file_get_contents($this->getDefault(Command::js_file)));
            $this->success("Archivo js creado correctamente.");
        }

    }

}