<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 23.08.2019
 * Time: 11:58
 */

namespace Tests\Unit\IntegrationKrsBundle\Mock;

use DevonshireBundle\Interfaces\iHttpRequest;
use Integration\KRSBundle\Services\KrsDataService;
use Mockery as m;

class ResponseFromKrsMock
{

    public static function createRespondSuccess($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "total": 1,
            "items": [
            {
                "address": {
                    "city": "'.KRSObjectMock::CITY.'",
                    "code": "'.KRSObjectMock::CODE.'",
                    "country": "'.KRSObjectMock::COUNTRY.'",
                    "house_no": "'.KRSObjectMock::HOUSE_NR.'",
                    "post_office": "'.KRSObjectMock::POST_OFFICE.'",
                    "street": "'.KRSObjectMock::STREET.'"
                },
                "business_insert_date": "2007-01-08",
                "ceo": {
                    "first_name": "Przykład",
                    "krs_person_id": 876543210,
                    "last_name": "Przykładowski",
                    "name": "Przykład Examplus Przykładowski"
                },
                "current_relations_count": 41,
                "duns": "522946342",
                "first_entry_date": "2007-01-08",
                "historical_relations_count": 83,
                "id": '.KRSObjectMock::ID.',
                "is_opp": false,
                "is_removed": false,
                "krs": "0000271562",
                "last_entry_date": "2019-05-16",
                "last_entry_no": 97,
                "last_state_entry_date": "2019-05-16",
                "last_state_entry_no": 97,
                "legal_form": "SPÓŁKA AKCYJNA",
                "name": "'.KRSObjectMock::NAME.'",
                "name_short": "'.KRSObjectMock::SHORT_NAME.'",
                "nip": "'.$nip.'",
                "regon": "'.KRSObjectMock::REGON.'",
                "type": "KrsOrganization"
            }]
        }');
    }

    public static function createRespondWithBasicSuccess($nip, $content2 = null) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "total": 1,
            "items": [
            {
                "address": {
                    "city": "'.KRSObjectMock::CITY.'",
                    "code": "'.KRSObjectMock::CODE.'",
                    "country": "'.KRSObjectMock::COUNTRY.'",
                    "house_no": "'.KRSObjectMock::HOUSE_NR.'",
                    "post_office": "'.KRSObjectMock::POST_OFFICE.'",
                    "street": "'.KRSObjectMock::STREET.'"
                },
                "business_insert_date": "2007-01-08",
                "ceo": {
                    "first_name": "Przykład",
                    "krs_person_id": 876543210,
                    "last_name": "Przykładowski",
                    "name": "Przykład Examplus Przykładowski"
                },
                "current_relations_count": 41,
                "duns": "522946342",
                "first_entry_date": "2007-01-08",
                "historical_relations_count": 83,
                "id": '.KRSObjectMock::ID.',
                "is_opp": false,
                "is_removed": false,
                "krs": "0000271562",
                "last_entry_date": "2019-05-16",
                "last_entry_no": 97,
                "last_state_entry_date": "2019-05-16",
                "last_state_entry_no": 97,
                "legal_form": "SPÓŁKA AKCYJNA",
                "name": "'.KRSObjectMock::NAME.'",
                "name_short": "'.KRSObjectMock::SHORT_NAME.'",
                "nip": "'.$nip.'",
                "regon": "'.KRSObjectMock::REGON.'",
                "type": "KrsOrganization",
                "chapters" : [
                    "basic"
                ]
            }]
        }', $content2);
    }

    public static function createRespondExtendSuccess($nip) :iHttpRequest
    {
        $mock = self::createRespondWithBasicSuccess($nip,'
            {
                "address": {
                    "city": "'.KRSObjectMock::CITY.'",
                    "code": "'.KRSObjectMock::CODE.'",
                    "country": "'.KRSObjectMock::COUNTRY.'",
                    "house_no": "'.KRSObjectMock::HOUSE_NR.'",
                    "post_office": "'.KRSObjectMock::POST_OFFICE.'",
                    "street": "'.KRSObjectMock::STREET.'"
                },
                "business_insert_date": "2007-01-08",
                "ceo": {
                    "first_name": "Przykład",
                    "krs_person_id": 876543210,
                    "last_name": "Przykładowski",
                    "name": "Przykład Examplus Przykładowski"
                },
                "current_relations_count": 41,
                "duns": "522946342",
                "first_entry_date": "2007-01-08",
                "historical_relations_count": 83,
                "id": '.KRSObjectMock::ID.',
                "is_opp": false,
                "is_removed": false,
                "krs": "0000271562",
                "last_entry_date": "2019-05-16",
                "last_entry_no": 97,
                "last_state_entry_date": "2019-05-16",
                "last_state_entry_no": 97,
                "legal_form": "SPÓŁKA AKCYJNA",
                "name": "'.KRSObjectMock::NAME.'",
                "name_short": "'.KRSObjectMock::SHORT_NAME.'",
                "nip": "'.$nip.'",
                "regon": "'.KRSObjectMock::REGON.'",
                "type": "KrsOrganization",
                "chapters" : [
                    "basic"
                ],
                "fields" : {
                    "email" : {
                        "_value" : "'.KRSObjectMock::EMAIL.'"
                    },
                    "phone" : {
                        "_value" : "'.KRSObjectMock::PHONE.'"
                    },
                    "www" : {
                        "_value" :"'.KRSObjectMock::WWW.'"
                    }
                }
            }');
        return $mock;
    }

    public static function createRespondExtendError($nip) :iHttpRequest
    {
        $mock = self::createRespondWithBasicSuccess($nip,'{
            "code": 500,
            "message": "Internal server error."
        }');
        return $mock;
    }

    public static function createRespondNotFound($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "total": 0,
            "items": []
        }');
    }


    public static function createRespondQuotaExceeded($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "code": 403,
            "message": "API quota exceeded."
        }');
    }

    public static function createRespondTooManyRequest($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "code": 429,
            "message": "Too many requests."
        }');
    }

    public static function createRespondInvalid($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "code": 400,
            "message": "The nip must be a number."
        }');
    }

    public static function createRespondError($nip) :iHttpRequest
    {
        return self::createBaseMock($nip, '{
            "code": 500,
            "message": "Internal server error."
        }');
    }

    private static function createBaseMock($nip, $content, $content2 = null) :iHttpRequest
    {
        $httpRequest =  m::mock(iHttpRequest::class, [
            'close' => null,
            'setOptions' => null
        ]);
        $httpRequest->shouldReceive('executeWithUrlAndOption', [KrsDataService::BASE_URL.'?nip='.$nip])
            ->andReturn($content, $content2);
        return $httpRequest;
    }
}