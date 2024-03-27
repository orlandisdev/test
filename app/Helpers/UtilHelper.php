<?php
/**
*  Utilidades
**/
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UtilHelper
{

    Const NIF_CIF_NIE_MAL = 0;
    Const NIF_OK  = 1;
    Const CIF_OK  = 2;
    Const NIE_OK  = 3;
    Const NIF_MAL = -1;
    Const CIF_MAL = -2;
    Const NIE_MAL = -3;
    
    Const CARACTERES_CAMPO_TEXT = 65535;
    Const CORREO_RECORDAR  = 'recordar';
    Const CAMBIAR_PASSWORD = 'cpsswd';
    Const CORREO_CARGA_CSV = 'cargacsv';
    
    protected static $configuracion = [];

    /**
     * 
     */
    static public function ver( $datos_, $salir_ = true ){
        $t = implode('<br>', self::getPilaLlamadas());
        $pintado = false;
        if (is_object($datos_) && (
            get_class($datos_) == 'Illuminate\Database\Eloquent\Builder'
            || get_class($datos_) == 'Illuminate\Database\Query\Builder'
        )){
            $pintado = true;
            $sql = str_replace('%', '##PORCENTAJE##', $datos_ ->toSql());
            $sql = str_replace('?', '\'%s\'', $sql);
            $p = $datos_ ->getBindings();
            foreach($p as $k => $v){
                if (is_object($v) && get_class($v) == 'DateTime'){
                    $p[ $k ] = $v ->format('Y-m-d H:i:s');
                }
            }
            $r = [
                '%Y%m%d%H%i' => '$Y$m$d$H$i',
            ];
            $sql = str_replace(array_keys($r), array_values($r), $sql);
            $sql = vsprintf($sql, $p);
            $sql = str_replace(array_values($r), array_keys($r), $sql);
            $sql = str_replace('##PORCENTAJE##','%', $sql);
            $t .= "<pre>\n\n"
                .str_replace('<br>', '', self::sql2html($sql))
                ."\n\n</pre>"
            ;
        } elseif (is_string($datos_)){
            $pintado = true;
            $t .= "<pre><br><br>\n\n"
                .self::sql2html( $datos_ )
                ."\n\n<br><br></pre>"
            ;
        }
        if (config('app.env') == 'production') {
            $t .= "<pre>\n\n"
                .var_export( $datos_, true ) //print_R
                ."\n\n</pre>"
            ;
            Log::debug( $t );
            return;
        }
        
        if (config('app.env') != 'local'){
            Log::debug('*** util::ver *** '.$t ."\n". json_encode($datos_) ."\n" );
        } else {
            echo $t;
            if (!$pintado){
                echo "<pre>\n\n";
                dump( $datos_ );
                echo "\n\n</pre>";
            }
            if ($salir_) {
                exit;
            } else {
                echo '<hr>';
            }
        }
    }
    static public function logSql( $query_ ){
        $sql = str_replace(array('?'), array('\'%s\''), $query_ ->toSql());
        //Log::debug( $sql );
        $p = $query_ ->getBindings();
        foreach($p as $k => $v){
            if (is_object($v) && get_class($v) == 'DateTime'){
                $p[ $k ] = $v ->format('Y-m-d H:i:s');
            }
        }
        if (!empty($p)){
            $sql = vsprintf($sql, $p);
        }
        Log::debug( $sql );
    }
    
    /**
     *
    **/
    static private function sql2html( $sql_ ){
        $RETORNO = chr(13) .chr(10);
        if (strtolower(substr($sql_, 0, 7)) == 'select '){
            $sql_ = str_replace(' from ', '<br>'.$RETORNO .str_repeat('&nbsp;',5). 'from ', $sql_);
            $sql_ = str_replace(' left join ', '<br>'.$RETORNO .str_repeat('&nbsp;',9). 'left join ', $sql_);
            $sql_ = str_replace(' where ', '<br>'.$RETORNO .str_repeat('&nbsp;',5). 'where ', $sql_);
            $sql_ = str_replace(' and ', '<br>'.$RETORNO .str_repeat('&nbsp;',12). 'and ', $sql_);
            $sql_ = str_replace(' order by ', '<br>'.$RETORNO .str_repeat('&nbsp;',5). 'order by ', $sql_);
        }
        return $sql_;
    }
    
    /**
     * 
     */
    static public function getPilaLlamadas( $lineas = null ){
        $pila = [];
        $llamadas = debug_backtrace();
        foreach($llamadas as $func){
            if (!empty($func['file']) && strpos($func['file'], 'vendor/') === false){
                $pila[] = $func['file'] .'(' .$func['line']. ') ' .$func['function'];
            }
            if (!empty($lineas) && count($pila) >= $lineas){
                break;
            }
        }
        return $pila;
    }

    /**
     * 
     */
    public static function depurar( $t = null ){
        $r = '';
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
        for($i = 1; $i < min(3, count($bt)); $i++){
            $r .= (isset($bt[$i]['class']) ? $bt[$i]['class'] : '')
                .'(' .(isset($bt[$i]['line']) ? $bt[$i]['line'] : ''). ') '
                .(isset($bt[$i]['function']) ? $bt[$i]['function'] : '')
                .'(' .(isset($bt[$i]['args']) ? json_encode($bt[$i]['args']) : ''). ')' ."\n";
        }
        Log::debug( "\n".$r . (isset($t) ? $t : '' ) . "\n");
    }
    
    /**
     * 
     */
    static public function randomString( $length_ = 15 ){
        $chars = "bcdfghjklmnpqrstvwxyz"
               . "BCDFGHJKLMNPQRSTVWXYZ"
            //. "0123456789"
        ;
        while(1){
            $key = '';
            srand((double)microtime() * 1000000);
            for($i = 0; $i < $length_; $i++){
                $key .= substr($chars,(rand()%(strlen($chars))), 1);
            }
            break;
        }
        return $key;
    }
    

    /***************************************************************
     * Validación de nif, cif y nie.
    Decreto 2423/1975, de 25 de septiembre.
    Real Decreto 338/1990, de 9 de marzo.
    Real Decreto 1624/1992, de 29 de diciembre que modifica el 338/1990.
    Real Decreto 155/1996, de 2 de febrero.
    Orden de 3 de julio de 1998, por la que se modifica el Anexo del Decreto 2423/1975.
    Real Decreto 1065/2007, de 27 de julio.
    Orden EHA/451/2008, de 20 de febrero de 2008.
    Orden INT/2058/2008, de 14 de julio de 2008.
    *
    * Valores retornados: NIF_OK = 1, CIF_OK = 2, NIE_OK = 3, NIF_MAL = -1, CIF_MAL = -2, NIE_MAL = -3, NIF_CIF_NIE_MAL = 0
    *
    **/
    static public function validarNifCifNie($n_) {
	$cif = strtoupper($n_);
	$num = array();
	for ($i = 0; $i < 9; $i++) $num[] = '';
	$conta = 0;
	for ($i = 9-strlen($cif); $i < 9; $i ++){
            $num[$i] = substr($cif, $conta, 1);
            $conta++;
	}
	//Formatos validos
	if (!preg_match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{6,8}[A-Z]{1}$)', $cif)) return self::NIF_CIF_NIE_MAL;

	//NIF est�ndar
	if (preg_match('(^[0-9]{6,8}[A-Z]{1}$)', $cif))
	if ($num[count($num)-1] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, strlen($cif)-1) % 23, 1))
            return self::NIF_OK;
	else
            return self::NIF_MAL;

	//CIF
	$suma = $num[2] + $num[4] + $num[6];
	for ($i = 1; $i < 8; $i += 2){
            $suma += intval(substr((2 * $num[$i]),0,1)) + intval(substr((2 * $num[$i]),1,1));
        }
	$n = 10 - substr($suma, strlen($suma) - 1, 1);

	//comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
	if (preg_match('(^[KLM]{1})', $cif))
	if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1))
            return self::NIF_OK;
	else
            return self::NIF_MAL;

	//CIF
	if (preg_match('(^[ABCDEFGHJNPQRSUVW]{1})', $cif))
	if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1))
            return self::CIF_OK;
	else
            return self::CIF_MAL;

	//comprobacion de NIEs
	//T
	if (preg_match('(^[T]{1})', $cif))
	if ($num[8] == preg_match('(^[T]{1}[A-Z0-9]{8}$)', $cif))
            return self::NIE_OK;
	else
            return self::NIE_MAL;

	//XYZ
	if (preg_match('(^[XYZ]{1})', $cif))
	if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1))
            return self::NIE_OK;
	else
            return self::NIE_MAL;

	//si llega hasta aqui es que no lo ha reconocido
	return self::NIF_CIF_NIE_MAL;
    }
    
    /**
     * 
     */
    static public function encriptar( $str_ ){
        $id = (Auth::check() ? Auth::user() ->id : 'NO');
        return urlencode(base64_encode(Crypt::encrypt($str_).'#'.$id));
    }
    static public function desencriptar( $str_ ){
        if ($str_ == null){
            return null;
        }
        $id = (Auth::check() ? Auth::user() ->id : 'NO');
        $x = explode('#', base64_decode(urldecode($str_)));
        if (count($x) > 1 && $x[1] != 'NO' && $x[1] != $id ){
            Log::error('UtilHelper.desencriptar 404');
            abort(404);
        }
        return Crypt::decrypt($x[0]);
    }
    static public function ofuscar( $str_ ){
        return urlencode(base64_encode(Crypt::encrypt($str_)));
    }
    static public function desofuscar( $str_ ){
        if ($str_ == null){
            return null;
        }
        return Crypt::decrypt(base64_decode(urldecode($str_)));
    }
    static private function getClavesMiniEncriptarNumero( $indice ){
        if (empty(config('app.miniencriptar_key'))){
            Log::debug('Error UtilHelper.getClavesMiniEncriptarNumero falta clave app.miniencriptar_key');
        }
        $claves = str_split( config('app.miniencriptar_key'), 10);
        $aleat = str_split(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $clave   = str_split( $claves[$indice] );
        $clave10 = str_split( $claves[10] );
        $t = $clave10[$indice];
        return [ $claves, $clave, $aleat, $t ];
    }
    static public function miniEncriptarNumero( $n ){/* menores de 99 caracteres */
        $indice = rand(0,9);
        //self::Ver('indice='.$indice, false);
        list( $claves, $clave, $aleat, $t ) = self::getClavesMiniEncriptarNumero( $indice );
        
        $n2 = str_pad(strlen($n), 2, '0',STR_PAD_LEFT);
        $a = str_split( $n2 );
        foreach($a as $c){
            $t .= $clave[$c];
        }
        
        $a = str_split( $n );
        foreach($a as $c){
            $t .= $clave[$c] .$aleat[rand(0,count($aleat)-1)];
        }
        return $t;
    }
    static public function miniDesencriptarNumero( $t ){
        $a = str_split( $t );
        list( $claves, $clave, $aleat, $t ) = self::getClavesMiniEncriptarNumero( 0 );
        $indice = array_search($a[0], str_split($claves[10]));
        //self::Ver($a, false);
        //self::Ver($claves, false);
        //self::Ver('indice='.$indice, false);
        $clave = str_split($claves[ $indice ]);
        $len = intval(array_search($a[1], $clave).array_search($a[2], $clave));
        $n = '';
        for($i = 0; $i < ($len*2); $i += 2){
            $n .= array_search($a[ $i+3 ], $clave);
        }
        return $n;
    }
    
    /**
    *
    **/
    static public function desSerializar( $t_ ){
        $auctionDetails = '';
        if (!empty($t_)){
            try {
                $auctionDetails = @unserialize($auctionDetails);
                if ($auctionDetails === false || empty($auctionDetails)){
                    //no funciona en 5.5
                    // $auctionDetails = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $t_ ); 
                    $auctionDetails = preg_replace_callback ( '!s:(\d+):"(.*?)";!s', function($match) { 
                            return ($match[1] == strlen($match[2])) ? $match[0] : "s:" . strlen($match[2]) . ':"' . $match[2] . '";';
                    }, $t_ );
                    $auctionDetails = @unserialize($auctionDetails);
                }
            } catch (Exception $e) {
                $auctionDetails = '';
            }
        }
        return $auctionDetails;
    }
    static public function serializar($v_){
        return base64_encode(serialize($v_));
    }
    
    /**
     * 
     */
    static public function esPar($n){
	return (($n/2) == floor($n/2));
    }
    
    /**
     * 
     */
    public static function time2fechaLarga($t_, $conAnio_ = true, $conDiaSemana_ = true){
        return ($conDiaSemana_ ? self::fecha2DiaSemana( $t_, true) .', ' : '')
            . date('j', $t_) .' de '. self::fecha2Mes($t_, false, false) 
            .($conAnio_ ?  ' de '. date('Y', $t_) : '')
        ;
    }
    /**
    *
    **/
    public static function fecha2Mes($t_, $corto_ = false){
        $meses = self::getMeses();
        if (!empty($t_)){
            $t_ = $meses[date('n', self::fecha2time($t_))-1];
            if ($corto_){
                $t_ = substr($t_, 0, 3);
            }
        }
        return $t_;
    }
    public static function time2Mes( $time, $corto_ = true ){
        $meses = self::getMeses();
        $t_ = null;
        if (!empty($time)){
            $t_ = $meses[date('n', $time)-1];
            if ($corto_){
                $t_ = substr($t_, 0, 3);
            }
        }
        return $t_;
    }
    public static function getMeses(){
        return array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
    }
    /**
     * 
     */
    public static function fecha2DiaSemana($t_, $ya_ = false){
	global $APP;
	if (!empty($t_)){
		$dias = self::getDiasSemana();
		$t_ = $dias[date('N', self::fecha2time($t_))-1];
		$t_ = __($t_);
	}
	return $t_;
    }
    public static function getDiasSemana(){
	return array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
    }
    /***
    * pasa una cadena de tipo dd/mm/yyyy [H:i:s] a date
    **/
    public static function fecha2time($t_){
        if (!empty($t_)){
            if (strpos($t_, '-') === false){
                $t = explode(' ', $t_ );
                $x = explode('/', $t[0] );
                if (count($x) == 3){
                    if (count($t) == 1){
                        $t[1] = '0:0:0';
                    }
                    $t = explode(':', $t[1] );
                    $t_ = mktime( (isset($t[0]) ? $t[0] : 0), (isset($t[1]) ? $t[1] : 0), (isset($t[2]) ? $t[2] : 0), $x[1], $x[0], $x[2]);
                }
                unset($x);
            } else {
                $t_ = strtotime($t_);
            }
        }
        return intval($t_);
    }
    /**
     * Pasar fecha dd/mm/yyyy a yyyy-mm-dd
     */
    public static function fecha2sql( $f_ ){
        if (strpos($f_, '/') !== false){
            $x = explode('/', $f_);
            $f_ = $x[2] . '-'. $x[1] . '-'. $x[0];
        }
        return $f_;
    }
    
    /**
     * 
     */
    public static function getCurl( $url_, $modo_ = 'get', $datos_ = null ){
        $headr = array();
        //$headr[] = 'Authorization: '.config('app.');
        $ch = self::getCurlObject();
        curl_setopt($ch, CURLOPT_URL, $url_ );
        if ($modo_ == 'post'){
            curl_setopt($ch, CURLOPT_POST, 1);
            if (!empty($datos_)){
                $campos = (is_array($datos_)) ? http_build_query($datos_) : $datos_;
                curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
                $headr[] = 'Content-Type: application/json';
                $headr[] = 'Content-length: ' .(!empty($campos) ? strlen($campos) : 0);
            }
        }
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $server_output = curl_exec ($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);        
        curl_close ($ch);
        
        return $server_output;
    }
    public static function getCurlObject(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2'); //5
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (config('app.env') == 'local'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        if (!empty(config('app.proxy'))){
            $x = explode(':', config('app.proxy'));
            if (count($x == 2)){
                curl_setopt($ch, CURLOPT_PROXY, $x[0]);
                curl_setopt($ch, CURLOPT_PROXYPORT, $x[1]);
            } elseif (count($x == 3)){
                curl_setopt($ch, CURLOPT_PROXY, $x[0].':'.$x[1]);
                curl_setopt($ch, CURLOPT_PROXYPORT, $x[2]);
            } else {
                curl_setopt($ch, CURLOPT_PROXY, config('app.proxy'));
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        return $ch;
    }
    
    /**
     * 
     */
    static public function bytes2Humano($b_){
	$t = '';
	if ($b_ < 1024){
            $t = $b_ .' Bytes';
	} elseif ($b_ < 1024 * 1024){
            $t = number_format($b_/1024,2,',','.') .' Kb';
	} elseif ($b_ < 1024 * 1024 * 1024){
            $t = number_format($b_/(1024*1024),2,',','.') .' Mb';
	} else {
	//} elseif ($b_ < 1024 * 1024 * 1024 * 1024){
            $t = number_format($b_/(1024*1024*1024),2,',','.') .' Gb';
	}
	return $t;
    }    
    
    /**
    * This function returns the maximum files size that can be uploaded 
    * in PHP
    * @returns int File size in bytes
    **/
    public static function getMaximumFileUploadSize()  
    {  
        return min(self::convertPHPSizeToBytes(ini_get('post_max_size')), self::convertPHPSizeToBytes(ini_get('upload_max_filesize')));  
    }  

    /**
    * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    * 
    * @param string $sSize
    * @return integer The value in bytes
    */
    public static function convertPHPSizeToBytes($sSize)
    {
        //
        $sSuffix = strtoupper(substr($sSize, -1));
        if (!in_array($sSuffix,array('P','T','G','M','K'))){
            return (int)$sSize;  
        } 
        $iValue = substr($sSize, 0, -1);
        switch ($sSuffix) {
            case 'P':
                $iValue *= 1024;
                // Fallthrough intended
            case 'T':
                $iValue *= 1024;
                // Fallthrough intended
            case 'G':
                $iValue *= 1024;
                // Fallthrough intended
            case 'M':
                $iValue *= 1024;
                // Fallthrough intended
            case 'K':
                $iValue *= 1024;
                break;
        }
        return (int)$iValue;
    }
    
    /**
     * 
     */
    public static function hora2minutos( $h_ ){
        $min = 0;
        try {
            if (!empty($h_)){
                $x = explode(':', str_replace([';','.'], ':', $h_));
                $min = $x[0] * 60 + (isset($x[1]) ? intval($x[1]) : 0);
            }
        } catch ( \Exception $ex ){
            Log::debug('Error: UtilHelper.hora2minutos = ' .$h_.' '.$ex ->getMessage() );
        }
        return $min;
    }
    public static function minutos2hora( $min_ ){
        if (!empty($min_)){
            $neg = ($min_< 0);
            $min_ = abs($min_);
            $t = str_pad(floor($min_/60), 2, '0', STR_PAD_LEFT )
                .':'. str_pad(($min_ % 60), 2, '0', STR_PAD_LEFT);
            if ($neg){ $t = '-' .$t; }
        } else {
            $t = '00:00';
        }
        return $t;
    }
    public static function segundos2hora( $segs_ ){
        return self::minutos2hora( floor($segs_/60) )
            .':'. str_pad($segs_ % 60, 2, '0', STR_PAD_LEFT);
    }
    public static function time2segundos( $time_ ){
        //return ($time_ % 86400);
        return (intval(date('G', $time_ )) * 60*60) + (intval(date('i', $time_)) * 60) + intval(date('s', $time_));
    }
        
    
    /**
     * quitar acentos
     */
    public static function eliminar_tildes( $cadena ){

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }
    
    /**
     * 
     */
    static public function isValidIBAN ( $iban ){
        $iban = strtolower($iban);
        $Countries = array(
            'al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,
            'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,
            'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,
            'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,
            'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24
        );
        $Chars = array(
            'a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,
            'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35
        );

        if (!isset($Countries[ substr($iban,0,2) ]) || strlen($iban) != $Countries[ substr($iban,0,2) ]) { return false; }

        $MovedChar = substr($iban, 4) . substr($iban,0,4);
        $MovedCharArray = str_split($MovedChar);
        $NewString = "";

        foreach ($MovedCharArray as $k => $v) {
            if ( !is_numeric($MovedCharArray[$k]) ) {
                $MovedCharArray[$k] = $Chars[$MovedCharArray[$k]];
            }
            $NewString .= $MovedCharArray[$k];
        }
        if (function_exists("bcmod")) { return bcmod($NewString, '97') == 1; }

        // http://au2.php.net/manual/en/function.bcmod.php#38474
        $x = $NewString; $y = "97";
        $take = 5; $mod = "";

        do {
            $a = (int)$mod . substr($x, 0, $take);
            $x = substr($x, $take);
            $mod = $a % $y;
        }
        while (strlen($x));

        return (int)$mod == 1;
    }
    
    /**
     * 
     * @return type
     */
    static public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return '';
    }
    
    /**
     * 
     */
    public static function is_json( $string ){
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * 
     */
    public static function getNmLineasFichero( $ruta ){
        $comando = 'wc --lines ' .$ruta;
        $retorno = shell_exec( $comando );
        return intval($retorno);
    }
        
}

