<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 23.08.2019
 * Time: 11:58
 */

namespace Tests\Unit\IntegrationLinkedinBundle\Mock;

use DevonshireBundle\Interfaces\iHttpRequest;
use Integration\KRSBundle\Services\KrsDataService;
use Integration\LinkedinBundle\Services\LinkedIn;
use Mockery as m;

class ResponseFromLinkedinMock
{
    const TOKEN ='sadasdasf';
    const EXPIRE = 2121332332;
    const KEY = 'KEY';
    const SECRET = 'SECRET';
    const CALLBACK = 'http://localhost/apply';
    const CODE = 'code';
    const BASE_DATA_URL = LinkedIn::API_BASE. '/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams)';
    const LOGIN_URL = LinkedIn::OAUTH_BASE.  "/authorization?response_type=code&client_id=".self::KEY."&redirect_uri=" . self::CALLBACK;

}