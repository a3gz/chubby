<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby\Locale;

class Locale implements \Chubby\Interfaces\LocaleInterface
{
    /**
     * @var \Chubby\Interfaces\CurrencyFormatterInterface
     */
    protected $currencyFormatter = null;
    
    /**
     * @var \Chubby\Interfaces\DateFormatterInterface
     */
    protected $dateFormatter = null; 
    
    /**
     * @var \Chubby\Interfaces\DictionaryLoaderInterface
     */
    protected $dictionaryLoader = null;
    
    /**
     * @var \Chubby\Interfaces\numberFormatterInterface
     */
    protected $numberFormatter = null;
    
    /**
     * @var string An ISO 639-1 with the current localization code as given in the URI
     */
    protected $regionCode = null;
    

    
    /**
     * @param mixed[] Custom services.
     */
    public function __construct( array $services = [] )
    {
        $this->registerServices( $services );
    } // __construct()
    
    
    /**
     *
     */
    public function formatCurrency( $number )
    {
        if ( isset($this->currencyFormatter) ) {
            $number = $this->currencyFormatter->format( $number );
        }
        return $number;
    } // formatCurrency()
    
    
    /**
     * @param string $format A format string
     * @param int $time A unix time. If not given, time() is assumed.
     *
     * @return string A formatted date
     */
    public function formatDate( $format, $time = null )
    {
        if ( isset($this->dateFormatter) ) {
            $date = $this->dateFormatter->format( $format, $time );
        } else {
            $date = date( $format, $time );
        }
        
        return $date;
    } // formatDate()
    
    
    /**
     *
     */
    public function formatNumber( $number )
    {
        if ( isset($this->numberFormatter) ) {
            $number = $this->numberFormatter->format( $number );
        }
        return $number;
    } // formatNumber()
    
    
    /**
     * @inheritdoc
     */
    public function init( \Psr\Http\Message\ServerRequestInterface $request, array $codes = null )
    {
        if ( isset($codes) && is_array($codes) && count($codes) ) {
            
            // Scan for localization informatino within the URI. 
            // If found, it will be used for localization purposes and stripped from the request. 
            
            $uri = $request->getUri();
            $path = $uri->getPath();

            $matches = [];
            if ( preg_match( "#^(" . implode('|', $codes) . ")\/#", $path, $matches ) ) {
                $this->regionCode = $matches[1];
                $newPath = substr( $path, strlen($matches[0]) );
                $uri = $uri->withPath( $newPath );
                $request = $request->withUri( $uri, true );
            } else {
                $this->regionCode = $codes[0];
            }
        }
        
        return $request;
    } // init()
    
    
    /**
     *
     */
    private function registerServices( $services )
    {
        $this->currencyFormatter = function( $services ) {
            if ( !isset($services['currencyFormatter']) ) {
                return new \Chubby\Locale\CurrencyFormatter();
            } elseif ( !in_array( '\Chubby\Interfaces\CurrencyFormatterInterface', class_implements($services['currencyFormatter']) ) ) {
                throw new \Exception( "Currency services must implement: Chubby\Interfaces\CurrencyFormatterInterface" );
            }
        };

        $this->numberFormatter = function( $services ) {
            if ( !isset($services['numberFormatter']) ) {
                return new \Chubby\Locale\NumberFormatter();
            } elseif ( !in_array( '\Chubby\Interfaces\NumberFormatterInterface', class_implements($services['numberFormatter']) ) ) {
                throw new \Exception( "Number services must implement: Chubby\Interfaces\NumberFormatterInterface" );
            }
        };
        
        $this->dateFormatter = function( $services ) {
            if ( !isset($services['dateFormatter']) ) {
                return new \Chubby\Locale\DateFormatter();
            } elseif ( !in_array( '\Chubby\Interfaces\DateFormatterInterface', class_implements($services['dateFormatter']) ) ) {
                throw new \Exception( "Date services must implement: Chubby\Interfaces\DateFormatterInterface" );
            }
        };
        
        $this->dictionaryLoader = function( $services ) {
            if ( !isset($services['dictionaryLoader']) ) {
                return new \Chubby\Locale\DictionaryLoader();
            } elseif ( !in_array( '\Chubby\Interfaces\DictionaryLoaderInterface', class_implements($services['dictionaryLoader']) ) ) {
                throw new \Exception( "Dictionary loaders must implement: Chubby\Interfaces\DictionaryLoaderInterface" );
            }
        };
    } // registerServices()
    
    
    /**
     * @param string $key A dictionary key.
     *
     * @return string Translated version of the input as per active dictionary.
     */
    public function say( $key )
    {
        $text = $key;
        
        if ( isset($this->dictionary) ) {
            $text = call_user_func_array( [$this->dictionary, 'get'], func_get_args() );
        }
        
        return $text;
    } // say()
    
} // class 

// EOF 
