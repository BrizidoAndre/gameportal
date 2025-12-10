<?php

namespace app\controllers;

use app\models\Games;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\UploadedFile;

class GameController extends Controller
{
    public function actionCreate()
    {
        $model = new Games();
        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                $model->save(false);
                return $this->redirect('index');
            }
            VarDumper::dump($model->errors, 10, true);
            return '';
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('new-game', [
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        $query = Games::find()->orderBy('date desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('games', [
            'dataProvider' => $dataProvider
        ]);
    }
}
