<?php
/**
*  Utilidades IL3
**/
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\UtilHelper as util;

class IL3Helper
{

    /**
     * 
     */
    public static function registro( $sql, $parametros = null ){
        $regs = self::registros( $sql, $parametros );
        return (isset($regs[0]) ? $regs[0] : null);
    }
    
    /**
     * 
     */
    public static function registros( $sql, $parametros = null ){
        $regs = null;
        try {
            if (empty($parametros)){
                return DB::select( $sql );
            } else {
                return DB::select( $sql, $parametros );
            }
        } catch(\Exception $ex){
            Log::error( $ex );
        }
        return $regs;
    }
    
    /**
     * 
     */
    public static function ejecutar( $sql, $parametros = null ){
        try {
            if (empty($parametros)){
                return DB::unprepared( $sql );
            } else {
                return DB::statement( $sql, $parametros );
            }
        } catch(\Exception $ex){
            Log::error( $ex );
        }
    }
    
}

