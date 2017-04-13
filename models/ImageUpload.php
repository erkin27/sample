<?php
/**
 * Created by PhpStorm.
 * User: erkin
 * Date: 13.04.17
 * Time: 23:37
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }


    public function uploadFile(UploadedFile $file, $currentImage)
    {

        $this->image = $file;

        //delete old image if upload new

        if ($this->validate()) {

            $this->deleteCurrentImage($currentImage);

            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename()
    {
        //md5 - хеширует имя, uniqid - добавляет уникальность в имя,
        //strtolower - переводит все в нижний регистр
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {

        if ($this->fileExist($currentImage)) {

            unlink($this->getFolder() . $currentImage);

        }

    }

    public function fileExist ($currentImage) {
        if(!empty($currentImage)){
            return file_exists($this->getFolder() . $currentImage);
        }
    }

    public function saveImage () {

        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}