<?php

namespace application\support\media;

class Image
{
    private $imgDir;

    public function upload()
    {
        $tmpName = $_FILES['img']['tmp_name'];
        $newName = uniqid('img');
        $this->imgDir = 'public/uploads/' . $newName . '.png';

        move_uploaded_file($tmpName, $this->imgDir);

    }

    public function getDir()
    {
        return $this->imgDir;
    }
}