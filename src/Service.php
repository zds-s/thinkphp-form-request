<?php
/**
 * @author    : Death-Satan
 * @date      : 2021/8/14
 * @createTime: 21:47
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */


namespace SaTan\Think\Request;


use SaTan\Think\Request\Command\Request;
use think\facade\Lang;

class Service extends \think\Service
{
    public function register (): void
    {
        $this->loadLang();
        $this->commands([
            Request::class
        ]);
    }

    public function loadLang()
    {
        $this->app->lang->load(__DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'formrequest.php','form_request');
    }

    /**
     * 翻译
     *
     * @param       $filed
     * @param array $vars
     *
     * @return mixed
     */
    public static function trans($filed,$vars=[])
    {
        $lang_set = Lang::getLangSet().'.';
        return Lang::get($lang_set.$filed,$vars,$lang_set);
    }
}