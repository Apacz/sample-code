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
use Tests\Unit\Mocks\CandidateMocks;

class LinkedinMock
{
    public static function creatLinkedinWithBasicMock() : LinkedIn
    {
        return LinkedinMock::createBaseMock([
            'firstName' => [
                'localized' => [
                    'pl' => CandidateMocks::CANDIDATE_FIRST_NAME
                ]
            ],
            'lastName' => [
                'localized' => [
                    'pl' => CandidateMocks::CANDIDATE_LAST_NAME
                ]
            ],
            'preferredLocale' => [
                'language' => 'pl'
            ],

        ],
            [
                'elements' => [

                ]
            ]);
    }

    public static function creatLinkedinWithEmailMock() : LinkedIn
    {
        return LinkedinMock::createBaseMock([
            'firstName' => [
                'localized' => [
                    'pl' => CandidateMocks::CANDIDATE_FIRST_NAME
                ]
            ],
            'lastName' => [
                'localized' => [
                    'pl' => CandidateMocks::CANDIDATE_LAST_NAME
                ]
            ],
            'preferredLocale' => [
                'language' => 'pl'
            ],

        ],
            [
                'elements' => [
                    [
                        'handle~' => [
                            'emailAddress' => CandidateMocks::CANDIDATE_EMAIL
                        ]
                    ]
                ]
            ]);
    }

    private static function createBaseMock($meContent, $emailContent = false) :LinkedIn
    {
        $httpRequest =  m::mock(LinkedIn::class, [
            'getAccessToken' => ResponseFromLinkedinMock::TOKEN,
            'getAccessTokenExpiration' => ResponseFromLinkedinMock::EXPIRE,
            '__destruct' => null,
            'getLoginUrl' => ResponseFromLinkedinMock:: LOGIN_URL,
            'setCallbackUrl' => null,
            'getMe' => $meContent,
            'getEmail' => $emailContent,
        ]);

        return $httpRequest;
    }
}