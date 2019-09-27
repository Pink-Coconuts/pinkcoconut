<?php

/**
 * Country Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Country
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';

    public $timestamps = false;

    // Join to rooms_address table
    public function rooms_address()
    {
        return $this->belongsToMany('App\Models\RoomsAddress');
    }
    // get Iban required country in stripe
    public static function getIbanRequiredCountries()
    {
        $iban_required_countries = ['DK','FI','FR','DE','GI','IE','IT','LU','NL','NO','PT','ES','SE','CH','AT','BE'];
        return $iban_required_countries;
    }
    // get branch code required country in stripe
    public static function getBranchCodeRequiredCountries()
    {
        $iban_required_countries = ['HK','CA','JP','SG'];
        return $iban_required_countries;
    }
   
    // stripe supported country
    public static function getPayoutCoutries()
    {
        $payout_countries = array(     
            'AT' =>        'Austria',                     
            'AU' =>      'Australia',
            'BE' =>         'Belgium',
            'CA' =>         'Canada',
            'DK' =>        'Denmark',
            'FI' =>        'Finland',
            'FR' =>         'France',
            'DE' =>        'Germany',
            'HK' =>      'Hong Kong',
            'IE' =>        'Ireland',
            'IT' =>          'Italy',
            'JP' =>          'Japan',
            'LU' =>     'Luxembourg',
            'NL' =>    'Netherlands',
            'NZ' =>    'New Zealand',
            'NO' =>         'Norway',
            'PT' =>       'Portugal',
            'SG' =>      'Singapore',
            'ES' =>          'Spain',
            'SE' =>         'Sweden',
            'CH' =>    'Switzerland',
            'GB' => 'United Kingdom',
            'US' =>  'United States',                            
            );
        return $payout_countries;
    }
}
