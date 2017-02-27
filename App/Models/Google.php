<?php

namespace App\Models;

use \Core\Vendor;
use \Config\GoogleAuth;
//use 

class Google
{
    public function connectToGoogle()
    {
        $vendor = new Vendor;
        $vendor->loadPackage();
    }
    
    public static function getGoogleOptions()
    {
        
    }
}