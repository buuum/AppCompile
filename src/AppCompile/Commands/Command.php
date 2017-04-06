<?php

namespace AppCompile\Commands;

use Symfony\Component\Yaml\Yaml;

class Command extends AbstractCommand
{

    CONST config_file = 'compile.yml';
    CONST grunt_file = 'Gruntfile.coffee';
    CONST package_file = 'package.json';
    CONST js_file = 'js.php';
    CONST css_file = 'css.php';

    /**
     * @var array
     */
    protected $config = [];

    protected function selectDB($question)
    {
        $environments_list = [];
        $environments_by_host = [];

        foreach ($this->config['environments'] as $name => $config) {
            $environments_list[] = $name;
            $environments_by_host[$name] = $config;
        }

        $environment_host = $this->choiceQuestion("$question\n", $environments_list);

        return $environments_by_host[$environment_host];
    }

    protected function dir_root()
    {
        return realpath(getcwd());
    }

    protected function checkFile($name, $assets = false)
    {
        $file = $this->getFile($name, $assets);
        $exist = file_exists($file);
        if ($name == Command::config_file && $exist) {
            $this->config = Yaml::parse(file_get_contents($file));
        }
        return $exist;
    }

    protected function getFile($name, $assets = false)
    {
        if ($assets) {
            return $this->dir_root() . '/' . $this->config['paths']['assets']['root'] . '/' . $name;
        }

        return $this->dir_root() . '/' . $name;
    }

    protected function getDefault($name)
    {
        return __DIR__ . '/../../app/files/' . $name;
    }

    protected function fire()
    {
    }


}