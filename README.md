# thinkphp6 FormRequest 扩展

## 参考laravel的[表单验证](https://learnku.com/docs/laravel/8.x/validation/9374#ecf8bc)

#安装
```shell
composer require death_satan/thinkphp-form-request -vvv
```
##创建验证器

---
```shell
# 执行下面的指令可以生成index应用的Blog表单验证器类库文件
php think make:request index@IndexRequest
# app/index/FormRequest/IndexRequest

#如果是单应用模式，则无需传入应用名
php think make:request IndexRequest
# app/FormRequest/IndexRequest

#如果需要生成多级表单验证器，可以使用
php think make:controller index@test/IndexRequest
# app/index/test/Blog/IndexRequest

```
---

### 默认生成的是一个基础表单验证器,类文件如下

---
```php
<?php

namespace app\FormRequest;

use SaTan\Think\Request\FormRequest;
use think\Validate;

class IndexRequest extends FormRequest
{
    /*
     *初始化
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

}
```
---

## 结合validate验证器使用

### 生成验证器 Test

---
```shell
php think make:validate Test
#会在 app/validate/目录下生成一个 Test验证器
```
---

### 再生成一个表单验证器

---
```shell
php think make:request TestRequest
#会在 app/FormRequest目录下生成一个TestRequest表单验证器类库
```
---
### 修改表单验证器中的validate属性

---
```php 

    /**
     * @var Validate|string 验证器
     */
    protected $validate = \app\validate\Test::class;
```
---

### 在控制器中使用

---
```php
class Index extends BaseController
{
    public function index(TestRequest $request)
    {
        //验证成功后数据处理...
        dd($request->all());
    }
}
```
---

## 不与validate结合
### 重写 *rules* *messages*方法 以及*batch*属性
```php 

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

```

## 验证

#### 验证失败

- form验证器验证失败的话会进行判断,如果是ajax请求那将会返回一个JsonResponse对象

---
```json
{
  "message":"this fields errors!",
  "errors": [
    "id不能为空"
  ]
}
```
---

- 如果不是ajax则会返回一个redirect到上一页,并且在session内写入 键名errors的错误消息，以便调用
### 接管自定义验证

- 在生成的form request验证器内重写 *withValidate* 方法

```php
 
    /**
     * 验证前置操作
     * @param Validate $validate
     */
    protected function withValidate(Validate $validate):void
    {
            //验证数据
            $check = $validate->check($this->param());

            if ($check!==true)
            {
                //接管自定义的验证
//                $this->throwCheckError((array)$validate->getError());
            }
    }
```

* 其实就是参考laravel的表单验证用tp结合validate写了一个
* 目的是把数据验证和业务处理分离出来
* 会laravel的话应该很容易上手
