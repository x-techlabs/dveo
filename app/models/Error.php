<?php

class Error
{
    const ERROR_SOME_DATA_IS_EMPTY = "Wrong data";
    const ERROR_ID_DOES_NOT_EXIST = "id doesn't exist";
    const ERROR_PLAYLIST_ID_EMPTY = "Playlist id is empty";
    const ERROR_WRONG_DATA = "Wrong data";

    public static function returnError($error)
    {
        return $error;
    }
} 