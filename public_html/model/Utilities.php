<?php

class Utilities
{

    public static function uploadFile($idMember)
    {
        $size = 2097152;
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/uploads/';
        $errorFile = false;

        if (($_FILES["image"]["size"] < $size)) {
            if ($_FILES["image"]["error"] > 0) {
                $error = "Return Code: " . $_FILES["image"]["error"] . "<br />";
                if ($_FILES["image"]["error"] == 4) {
                    return "";
                }

            } else {
                $extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
                $extension_upload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                $name = $idMember . '.' . $extension_upload;
                if (in_array($extension_upload, $extensions_valides)) {

                    $moved = move_uploaded_file($_FILES["image"]["tmp_name"], $path . $name);

                    if ($moved) {
                        $error = "Move: Success";
                    } else {
                        $error = "Move Failed";
                    }

                    return $name;

                } else {
                    $error = "Extension correcte" . '</br>';
                }
//echo "Stored in: " . "uploads/" . $_FILES["image"]["name"];
            }
        } else {
            $error = "Invalid file";
        }

    }

}