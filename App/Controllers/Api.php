<?php

namespace App\Controllers;

use \Core\Views;

class Api extends \Core\Controller
{
    protected function errorMessageBuilder($code)
    {
        switch ($code) {
            case 500:
                $msg = 'Sorry, Unauthorized';
                break;
                
            case 400:
                $msg = 'Sorry, Method not supported';
                break;
                
            case 404:
                $msg = 'Sorry, resource does not exist';
                break;
        }
        return json_encode([
            'code' => $code,
            'message' => $msg
        ]);
    }
    
    public function getChartAction()
    {
        $headers = getallheaders();
        echo json_encode([
            'cols' => [
                [
                    'label' => 'Player Name',
                    'type' => 'string'
                ],
                [
                    'label' => 'Player Height',
                    'type' => 'number'
                ]
            ],
            'rows' => [
                [
                    'c' => [
                        ['v' => 'James Harden', 'f' => null],
                        ['v' => 195, 'f' => null]
                    ]
                ],
                [
                    'c' => [
                        ['v' => 'James Johnson', 'f' => null],
                        ['v' => 205, 'f' => null]
                    ]
                ]
            ]
        ]);
    }
}
header("Access-Control-Allow-Origin: http://localhost:8090");