<?php

validateParams($argv);
$provider = validateProvider($argv);

/*
    Kiedy repo nie istnieje git pyta o passy do zalogowania w celu sprawdzenia czy repo nie jest prywatne,
    it's not a bug it's a feature? :)
*/

$output = exec("git ls-remote $provider/$argv[1] $argv[2] --exit-code=2", $output, $return);
preg_match('/^[a-z0-9]+/', $output, $matches);
echo isset($matches[0]) ? "$matches[0] \n" : "Branch not found \n";



function validateParams($params)
{
    if (!isset($params[1])) {
        echo "You need to pass an argument owner/repo \n";
        exit;
    } else if (!isset($params[2])) {
        echo "You need to add branch argument \n";
        exit;
    }
}

function validateProvider($params)
{
    //Nie pozwalam wywolac jakiegokolwiek serwisu gita, uwazam ze lepiej miec kontrole nad tym co klient moze wywolac
    $valid = [
        'github' => 'https://github.com',
        'bitbucket' => 'https://bitbucket.com'
    ];

    if (count($params) == 3) {
        return $valid['github'];

    } else if (count($params) == 4) {

        $slice = explode('--service=', $params[3]);

        if (count($slice) == 1){
            echo "Invalid argument, provide service by --service=<provider> \n";
            exit;
        } else {
            if (in_array($slice[1], array_keys($valid)))
                return $valid[$slice[1]];
            else
                echo "Unknown service \n";
            exit;
        }
    }
}