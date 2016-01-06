<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby\Interfaces;

interface LocaleInterface 
{
    /**
     * @param mixed[] Custom services.
     */
    public function __construct( array $services = [] );
    
    
    /**
     *
     */
    public function formatCurrency( $number );
    
    
    /**
     * This default date formatter assumes taht $format is a valid PHP 
     * date format string.
     *
     * @param string $format A format string
     * @param int $time A unix time. If not given, time() is assumed.
     *
     * @return string A formatted date
     */
    public function formatDate( $format, $time = null );
    
    
    /**
     *
     */
    public function formatNumber( $number );
    
    
    /**
     * If $codes is not null it must contain at least one localiztion code. 
     * When the URI is parsed, if no locale code is present, the default $codes[0] 
     * must be assumed for localization purposes. 
     * If no codes are given, the $request object is not modified and the application 
     * won't have any special localization settings. 
     *
     * @param Psr\Http\Message\ServerRequestInterface $request The request probably having locale code inside the URI
     * @param string[] $codes A list of localization codes as per ISO 639-1 that the application supports.
     *
     * @return Psr\Http\Message\ServerRequestInterface A modified request.
     */
    public function init( \Psr\Http\Message\ServerRequestInterface $request, array $codes = null );
    
    
    /**
     * @param string $key A dictionary key.
     *
     * @return string Translated version of the input as per active dictionary.
     */
    public function say( $key );
    
} // interface

// EOF 
