<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property int $id
 * @property string $image_path
 * @property string $date (y-m-d)
 * @property int $number_pieces
 *
 * @property Pieces[] $pieces
 * @property Solution[] $solutions
 */
class Games extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'number_pieces', 'imageFile'], 'required'],
            [['date'], 'safe'],
            [['date'], 'unique'],
            ['imageFile', 'image', ],
            [['number_pieces'], 'integer'],
            [['image_path'], 'string', 'max' => 255],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $url = 'imgs/' . Yii::$app->security->generateRandomString(32) . '.' . $this->imageFile->extension;
            $blur = 'imgs/blur_' . Yii::$app->security->generateRandomString(32) . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($url);
            $this->imageFile->saveAs($blur);
            $this->image_path = $url;
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_path' => 'Image Path',
            'date' => 'Date',
            'number_pieces' => 'Number Pieces',
        ];
    }

    /**
     * Gets query for [[Pieces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPieces()
    {
        return $this->hasMany(Pieces::class, ['game_id' => 'id']);
    }

    /**
     * Gets query for [[Solutions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolutions()
    {
        return $this->hasMany(Solution::class, ['game_id' => 'id']);
    }

}
