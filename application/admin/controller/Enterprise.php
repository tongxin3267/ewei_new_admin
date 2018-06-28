<?php
/**
 * 企业网站模块
 * User: HeYiwei
 * Date: 2018/6/16
 * Time: 15:07
 */
namespace app\admin\controller;
use app\common\model\Article;
use app\common\model\ArticleCategory;
use app\common\model\Cases;
use app\common\model\CasesCategory;
use app\common\model\EnterpriseServer;
use app\common\model\EnterpriseSystem;
use app\common\model\Nav as NavModel;
use app\common\controller\PlugInBase;
use app\common\model\Slide;
use think\Db;

class Enterprise extends PlugInBase
{
    public $pageList;
    public $pagesList;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $this->pageList = Db::name('pages')->where(['s_id'=>999])->select();
        foreach ($this->pageList as $v){
            $this->pagesList[$v['id']] = $v;
        }
    }

    public function index(){
        return $this->fetch();
    }

    /**
     * 菜单管理
     */
    public function menuList(){
        $nav_model = new NavModel();
        $nav_list        = $nav_model->order(['sort' => 'ASC', 'id' => 'ASC'])->where(['s_id'=>999])->select();
        $nav_level_list  = array2level($nav_list);

        $this->assign('nav_level_list', $nav_level_list);

        return $this->fetch('/enterprise/menu');
    }

    /**
     * 菜单添加
     * @return mixed
     */
    public function menuAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $slide_model = new Slide();
            if ($slide_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            return $this->fetch('/enterprise/menu_add');
        }
    }

    /**
     * 菜单修改
     * @return mixed
     */
    public function menuUpdate( $id ){
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Nav');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $nav_model = new NavModel();
                if ($nav_model->allowField(true)->save($data, ['id'=>$id]) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }else{
            $info     = Db::name('nav')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/menu_update',['info' => $info]);
        }
    }

    /**
     * 轮播图展示
     * @return mixed
     */
    public function banner(){
        $list = Db::name('slide')->where(['s_id'=>999])->select();

        return $this->fetch('/enterprise/banner',['slide_list'=>$list,'pageList'=>$this->pagesList]);
    }

    /**
     * 轮播图添加
     * @return mixed
     */
    public function bannerAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $slide_model = new Slide();
            if ($slide_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            return $this->fetch('/enterprise/banner_add',['pageList'=>$this->pagesList]);
        }
    }

    /**
     * 轮播图修改
     * @return mixed
     */
    public function bannerUpdate( $id ){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $slide_model = new Slide();
            if ($slide_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $info     = Db::name('slide')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/banner_update',['pageList'=>$this->pagesList,'info' => $info]);
        }
    }

    /**
     * 服务信息列表
     */
    public function server(){
        $list = Db::name('enterprise_server')->where([
            's_id' => 999
        ])->select();

        return $this->fetch('/enterprise/server',['list'=>$list]);
    }

    /**
     * 服务信息添加
     * @return mixed
     */
    public function serverAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $server_model = new EnterpriseServer();
            if ($server_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            return $this->fetch('/enterprise/server_add',['pageList'=>$this->pagesList]);
        }
    }

    /**
     * 修改服务信息
     */
    public function serverUpdate( ){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $server_model = new EnterpriseServer();
            if ($server_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('enterprise_server')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/server_update',['pageList'=>$this->pagesList,'info' => $info]);
        }
    }

    /**
     * 删除服务信息
     * @param $id
     */
    public function serverDel($id){
        $server_model = new EnterpriseServer();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 案例列表
     * @return mixed
     */
    public function cases(){
        $list =  Db::name('cases')->where(['s_id'=>999])->select();
        return $this->fetch('/enterprise/cases',['list'=>$list]);
    }

    /**
     * 案例添加
     * @return mixed
     */
    public function casesAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $cases_model = new Cases();
            if ($cases_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $categoryList =  Db::name('cases_category')->where(['s_id'=>999])->select();
            if( empty($categoryList) ){
                echo '<script>alert("请先添加案例分类");window.location.href="/admin/enterprise/casesCategory.html"</script>';
            }
            return $this->fetch('/enterprise/cases_add',['categoryList'=>$categoryList]);
        }
    }

    /**
     * 案例修改
     */
    public function casesUpdate($id ){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $cases_model = new Cases();
            if ($cases_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('cases')->where(['id'=>$id])->find();
            $categoryList =  Db::name('cases_category')->where(['s_id'=>999])->select();

            return $this->fetch('/enterprise/cases_update',['categoryList'=>$categoryList,'info' => $info]);
        }
    }


    /**
     * 案例删除
     */
    public function casesDel($id){
        $server_model = new Cases();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }


    /**
     * 案例分类列表
     * @return mixed
     */
    public function casesCategory(){
        $list =  Db::name('cases_category')->where(['s_id'=>999])->select();
        return $this->fetch('/enterprise/cases_category',['list'=>$list]);
    }

    /**
     * 案例分类添加
     * @return mixed
     */
    public function casesCategoryAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $cases_category_model = new CasesCategory();
            if ($cases_category_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{

            return $this->fetch('/enterprise/cases_category_add');
        }
    }

    /**
     * 案例分类修改
     */
    public function casesCategoryUpdate(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $cases_model = new CasesCategory();
            if ($cases_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('cases_category')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/cases_category_update',['info' => $info]);
        }
    }

    /**
     * 案例分类删除
     */
    public function casesCategoryDel($id){
        $server_model = new CasesCategory();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 解决方案
     * @return mixed
     */

    public function scheme(){
        $list = Db::name('article')->where(['s_id' => 999,'cid'=>999 ])->select();
        return $this->fetch('/enterprise/scheme',['list'=>$list]);
    }

    /**
     * 解决方案添加
     * @return mixed
     */
    public function schemeAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 999;
            $params['cid'] = 999;
            $article_model = new Article();
            if ($article_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{

            return $this->fetch('/enterprise/scheme_add');
        }
    }

    /**
     * 解决方案修改
     */
    public function schemeUpdate(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $article_model = new Article();
            if ($article_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('article')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/scheme_update',['info' => $info]);
        }
    }

    /**
     * 解决方案删除
     */
    public function schemeDel($id){
        $server_model = new Article();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 新闻文章列表
     * @return mixed
     */
    public function news(){
        $list =  Db::name('article')->where(['s_id'=>888])->select();
        $categoryList =  Db::name('article_category')->where(['s_id'=>888])->select();
        $categoryLists = [];
        foreach ($categoryList as $v){
            $categoryLists[$v['id']] = $v;
        }
        return $this->fetch('/enterprise/news',['list'=>$list,'categoryList'=>$categoryLists]);
    }

    /**
     * 新闻文章添加
     * @return mixed
     */
    public function newsAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 888;
            $news_model = new Article();
            if ($news_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $categoryList =  Db::name('article')->where(['s_id'=>888])->select();
            if( empty($categoryList) ){
                echo '<script>alert("请先添加新闻文章分类");window.location.href="/admin/enterprise/newsCategory.html"</script>';
            }
            return $this->fetch('/enterprise/news_add',['categoryList'=>$categoryList]);
        }
    }

    /**
     * 新闻文章修改
     */
    public function newsUpdate($id ){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 888;
            $news_model = new Article();
            if ($news_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('article')->where(['id'=>$id])->find();
            $categoryList =  Db::name('article_category')->where(['s_id'=>888])->select();

            return $this->fetch('/enterprise/news_update',['categoryList'=>$categoryList,'info' => $info]);
        }
    }


    /**
     * 新闻文章删除
     */
    public function newsDel($id){
        $server_model = new Article();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }


    /**
     * 新闻文章分类列表
     * @return mixed
     */
    public function newsCategory(){
        $list =  Db::name('article_category')->where(['s_id'=>888])->select();
        return $this->fetch('/enterprise/news_category',['list'=>$list]);
    }

    /**
     * 新闻文章分类添加
     * @return mixed
     */
    public function newsCategoryAdd(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 888;
            $params['pid'] = 0;
            $news_category_model = new ArticleCategory();

            if ($news_category_model->allowField(true)->save($params)) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{

            return $this->fetch('/enterprise/news_category_add');
        }
    }

    /**
     * 新闻文章分类修改
     */
    public function newsCategoryUpdate(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $params['s_id'] = 888;
            $params['pid'] = 0;
            $news_category_model = new ArticleCategory();
            if ($news_category_model->allowField(true)->save($params,['id'=>$params['id']])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $id = $this->request->param('id');
            $info     = Db::name('article_category')->where(['id'=>$id])->find();

            return $this->fetch('/enterprise/news_category_update',['info' => $info]);
        }
    }

    /**
     * 新闻文章分类删除
     */
    public function newsCategoryDel($id){
        $server_model = new ArticleCategory();
        if ($server_model->destroy(['id'=>$id])) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 模块配置
     */
    public function  system(){
        if( $this->request->isPost()){
            $params = $this->request->param();
            $id = 999;
            $news_category_model = new EnterpriseSystem();
            if ($news_category_model->allowField(true)->save($params,['id'=>$id])) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }else{
            $info = Db::name('enterprise_system')->where(['id'=>999])->find();

            return $this->fetch('/enterprise/system',['info' => $info]);
        }
    }
}