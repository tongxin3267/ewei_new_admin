<?php
/**
 * Created by PhpStorm.
 * User: HeYiwei
 * Date: 2018/6/9
 * Time: 21:12
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use think\Db;

class Notice extends BaseController
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function getList(){
        $row = ['errmsg'=>'','errno'=>0,'data'=>[]];

        $row['data'] = Db::name('notice')->where([
            's_id' =>['in',$this->request->param('sid').',1'],
            'status' => 1
        ])->select();
        return $row;
    }
}