<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 1-9-2017
 * Time: 06:59
 */

namespace Stormyy\B3\Helper;


use q3tool;
use Stormyy\B3\Models\B3Server;

abstract  class Cod4Server
{
    public static function getRcon(B3Server $server){

    }

    public static function screenshotAll(B3Server $server){
        $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
        $response = $tool->send_rcon('getss all');
        return $response;
    }

    public static function screenshot(B3Server $server, $guid, $user=null){
        try {
            $filename = '';
            if ($user != null) {
                $filename = ' user-' . $user->id . '-';
            }

            $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
            $response = $tool->send_rcon('getss ' . $guid . $filename);

            if(str_contains($response, 'Bad rcon')){
                throw new \Exception('Bad rcon');
            }

            return $response;
        } catch (\Exception $exception){
            return response()->json([
                'code' => 'InvalidRcon',
                'message' => 'Invalid rcon try reloading the nehoscreenshotuploader plugin'
            ], 500);
        }
    }
}