<?php
/**
 * @author    : Death-Satan
 * @date      : 2021/8/14
 * @createTime: 21:07
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */


namespace SaTan\Think\Request;

use ReflectionMethod;
use think\Exception;
use think\exception\HttpResponseException;
use think\facade\Session;
use think\Validate;

/**
 * form请求验证类
 * Class FormRequest
 * @package SaTan\Think\Request
 */
class FormRequest
{
    public function __construct ()
    {
//        parent::__construct();
        if (method_exists($this, 'initialization')) {
            $this->initialization();
        }

        $this->verificationValidate();
    }

    /**
     * 验证
     */
    protected function verificationValidate():void
    {
        if ($this->check())
        {
            //获取验证器
            $validate = $this->resolve($this->validate);

            //验证前置操作
            $this->withValidate($validate);

            //验证数据
            $check = $validate->check($this->param());

            if ($check!==true)
            {
                $this->throwCheckError((array)$validate->getError());
            }
        }else{
            $this->throwCheckError(['check error']);
        }

    }

    /**
     * 抛出异常
     * @param array $errors
     */
    protected function throwCheckError(array $errors,string $message='this fields errors!')
    {
        //如果是ajax访问
        if ($this->isAjax())
        {
            throw new HttpResponseException(json(compact('errors','message')));
        }else{
            Session::flash('errors',$errors);
            $response = redirect($this->header('REQUEST_URI')?:'');
            throw new HttpResponseException($response);
        }
    }

    /**
     * 生成新的验证类
     * @param null $class
     *
     * @return Validate
     */
    protected function resolve($class=null):Validate{
        if (is_null($class))
        {
            $validate = new Validate();
            $validate->rule($this->rules())
                ->message($this->messages())
                ->batch($this->batch);
        }else{
            if (is_string($class))
            {
                //如果类存在
                if (class_exists($class))
                {
                    $validate = app()->invokeClass($class);
                    if (!is_subclass_of($validate,Validate::class))
                    {
                        throw new Exception(Service::trans('not_extends',['name'=>$class]));
                    }
                    $validate->batch($this->batch)
                        ->rule($this->rules())
                        ->message($this->messages());
                }
                else{
                    throw new Exception(Service::trans('not_find',['name'=>$class]));
                }
            }else{
                throw new Exception(Service::trans('unknown'));
            }
        }
        return $validate;
    }

    /*
     * 初始化
     */
    protected function initialization ():void
    {

    }

    /**
     * @var Validate|string 验证器
     */
    protected $validate;

    /**
     *
     * @var bool $batch 批量验证
     */
    protected $batch = false;

    /**
     * 验证权限
     * @return bool
     */
    protected  function check ():bool
    {
        return true;
    }

    /**
     * 验证规则
     * @return array
     */
    protected function rules():array
    {
        return [

        ];
    }

    /**
     * 错误消息
     * @return array
     */
    protected function messages():array
    {
        return [

        ];
    }

    /**
     * 验证前置操作
     * @param Validate $validate
     */
    protected function withValidate(Validate $validate):void
    {

    }

    public function __call ($name, $arguments)
    {
        $instance = \app()->request;
        $reflect = new ReflectionMethod($instance, $name);
        return app()->invokeReflectMethod(request(),$reflect,$arguments);
    }

}