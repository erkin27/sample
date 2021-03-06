<?php
/**
 * Created by PhpStorm.
 * User: erkin
 * Date: 04.06.17
 * Time: 15:07
 */

namespace app\models;


use yii\base\Model;

class CommentForm extends Model
{
    public $comment;

    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['comment'], 'string', 'length' => ['3,250']]
        ];
    }

    public function saveComment($article_id)
    {
        $comment = new Comment();
        $comment->text = $this->comment;
        $comment->user_id = \Yii::$app->user->id;
        if (!empty(\Yii::$app->user->identity->profile)) {
            $userId = User::findOne(['name' => \Yii::$app->user->identity->profile['name']])->id;
            $comment->user_id = $userId;
        }
        $comment->article_id = $article_id;
        $comment->status = 0; //dont submit by admin
        $comment->date = date('Y-m-d');
        return $comment->save();
    }
}