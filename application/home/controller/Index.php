<?php
namespace app\home\controller;
use app\common\controller\HomeBase;

/**
 * Created by PhpStorm.
 * User: HeYiwei
 * Date: 2018/6/8
 * Time: 18:36
 */
class Index  extends HomeBase
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

    }


    public function index(){
        return $this->fetch('/home_1/index/index');
    }
}