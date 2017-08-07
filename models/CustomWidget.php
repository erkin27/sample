<?php

namespace app\models;


use nodge\eauth\Widget;

class CustomWidget extends Widget
{
    public function run()
    {
        return $this->render('widgets/customWidget', [
            'id' => $this->getId(),
            'services' => $this->services,
            'action' => $this->action,
            'popup' => $this->popup,
            'assetBundle' => $this->assetBundle,
        ]);
    }
}