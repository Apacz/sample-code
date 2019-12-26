<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 23.08.2019
 * Time: 11:58
 */

namespace Tests\Unit\IntegrationKrsBundle\Mock;

use DevonshireBundle\Interfaces\iHttpRequest;
use Integration\KRSBundle\Model\KRSAddress;
use Integration\KRSBundle\Model\KRSExtraFieldsObject;
use Integration\KRSBundle\Model\KRSMainObject;
use Integration\KRSBundle\Services\KrsDataService;
use Mockery as m;

class KRSObjectMock
{
    const ID = 271562;
    const KRS = '0000271562';
    const NAME = 'company sp.z.o.o';
    const SHORT_NAME = 'company';
    const CITY = 'Katowice';
    const CODE = '40-114';
    const COUNTRY = 'Polska';
    const HOUSE_NR = 3;
    const POST_OFFICE = 'Katowice';
    const STREET = 'TAURON POLSKA ENERGIA';
    const REGON = '465657676';
    const EMAIL = 'email@pl.pl';
    const WWW = 'www.pl.pl';
    const PHONE = '+48213773923';

    public static function createKRSObjectMock($nip) :KRSMainObject
    {
        $address = new KRSAddress(self::CITY, self::CODE, self::COUNTRY, self::HOUSE_NR,
            self::POST_OFFICE, self::STREET);
        return new KRSMainObject(self::ID, $nip, self::KRS, self::SHORT_NAME, self::REGON, $address);
    }

    public static function createFullKRSObjectMock($nip) :KRSMainObject
    {
        $address = new KRSAddress(self::CITY, self::CODE, self::COUNTRY, self::HOUSE_NR,
            self::POST_OFFICE, self::STREET);
        $extra = new KRSExtraFieldsObject(self::WWW, self::PHONE, self::EMAIL);
        return new KRSMainObject(self::ID, $nip, self::KRS, self::SHORT_NAME, self::REGON, $address, $extra);
    }
}