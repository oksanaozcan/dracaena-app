<?php

namespace App\Utils;

class DBSeederHelper {

    public static function defineTagId($categoryId)
    {
        $tagId;

        switch ($categoryId) {
            case 1:
                $tagId = random_int(1, 22);
                break;
            case 2:
                $tagId = random_int(23, 37);
                break;
            case 3:
                $tagId = random_int(38, 52);
                break;
            case 4:
                $tagId = random_int(53, 68);
                break;
        }

        return $tagId;
    }
}

