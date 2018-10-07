<?php
namespace app\modules\api\modules\v1\controllers;

use yii\rest\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

class PostController extends Controller
{
    const POSTS_PER_PAGE = 5;
    private $cache;
    public $modelClass = 'app\models\Post';

    public function beforeAction($action) {
        $this->cache = Yii::$app->cache;
        parent::beforeAction($action);
        return true;
    }

    protected function verbs(){
        return [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH','POST'],
            'delete' => ['DELETE'],
            'view' => ['GET'],
            'index'=>['GET'],
        ];
    }
    
    public function actionIndex($page = 0) {
        $offset = $page*self::POSTS_PER_PAGE;
        $posts = $this->cache->getOrSet("api_posts_$page", function () use ($offset) {
            return $this->modelClass::find()
                ->asArray()
                ->offset($offset)
                ->limit(self::POSTS_PER_PAGE)
                ->all();
        });        
        return $posts;
    }

    public function actionUpdate($id) {
        $post = $this->getCachedPost($id);
        $post->attributes = (Yii::$app->request->post());
        if($post->validate() && $post->save()){
            $this->cache->set("api_post_$id", $post);
            return ['status' => true, 'message' => 'post updated', 'data' => $post];
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(422, 'Data Validation Failed');
        return ['status' => false, 'message' => $post->getErrors()];
    }

    public function actionCreate() {
        $post = new $this->modelClass();
        $post->attributes = (Yii::$app->request->post());
        if($post->validate() && $post->save()){
            $this->cache->set("api_post_$post->id", $post);
            return ['status' => true, 'message' => 'post created', 'data' => $post];
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(422, 'Data Validation Failed');
        return ['status' => false, 'message' => $post->getErrors()];
    }
    
    public function actionView($id) {
        $post = $this->getCachedPost($id);
        return $post;
    }

    public function actionDelete($id) {
        $post = $this->modelClass::findOne($id);
        if(!$post) throw new NotFoundHttpException();
        if($post->delete()){
            $this->cache->delete("api_post_$post->id");
            return ['status' => true, 'message' => 'post deleted', 'data' => $post];
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(500);
        return ['status' => false, 'message' => $post];
    }
    
    protected function getCachedPost($id){
        $post = $this->cache->getOrSet("api_post_$id", function () use ($id) {
            return $this->modelClass::findOne($id);
        }); 
        if(!$post) throw new NotFoundHttpException("Post with id $id not found");
        return $post;
    }
}