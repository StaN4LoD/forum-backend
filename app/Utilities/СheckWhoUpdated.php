<?php
/**
 * Created by PhpStorm.
 * User: kayn23
 * Date: 15.12.2018
 * Time: 15:36
 */

namespace App\Utilities;


class СheckWhoUpdated
{
    public static function check($id = '')
    {
        $user = auth()->user();
        if ($id == $user['id'] || $user['role'] === 'admin')
        {
            return true;
        } else {
            return false;
        }
    }
}