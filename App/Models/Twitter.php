<?php

/**
 * Sample Twitter Model
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace App\Models;

class Twitter
{
    /**
     * Return a JSON-encoded array of values
     *
     * @return string $twitter JSON string
     *
     */

    public function getInfo()
    {
        return json_encode([
            'package' => 'Bingo Framework',
            'author' => 'Lochemem Bruno Michael',
            'username' => 'Darth Lokus',
            'email' => 'lochbm@gmail.com'
        ]);
    }
}
