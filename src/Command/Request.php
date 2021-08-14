<?php

namespace SaTan\Think\Request\Command;

use think\console\input\Argument;

/**
 * @author    : Death-Satan
 * @date      : 2021/8/14
 * @createTime: 23:59
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */


class Request extends \think\console\command\Make
{
    protected $type = 'Request';

    protected function configure()
    {
        parent::configure();
        $this->setName('make:request')
            ->addArgument('FormRequestName', Argument::OPTIONAL, "The name of the form request")
            ->setDescription('Create a new FormRequest class');
    }

    protected function getStub ()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'FormRequest.stub';
    }

    protected function buildClass(string $name): string
    {
        $commandName = $this->input->getArgument('FormRequestName') ?: strtolower(basename($name));

        $namespace   = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);
        $stub  = file_get_contents($this->getStub());

        return str_replace(['{%commandName%}', '{%className%}', '{%namespace%}', '{%app_namespace%}'], [
            $commandName,
            $class,
            $namespace,
            $this->app->getNamespace(),
        ], $stub);
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\FormRequest';
    }

}