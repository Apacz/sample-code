<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 23.08.2019
 * Time: 11:57
 */

namespace Tests\Unit\IntegrationKrsBundle\Service;

use DevonshireBundle\Interfaces\iHttpRequest;
use Integration\KRSBundle\Creator\KRSObjectCreator;
use Integration\KRSBundle\Exceptions\KRSException;
use Integration\KRSBundle\Exceptions\KRSNotFoundException;
use Integration\KRSBundle\Services\KrsDataService;
use PHPUnit\Framework\TestCase;
use Tests\Unit\IntegrationKrsBundle\Mock\KRSObjectMock;
use Tests\Unit\IntegrationKrsBundle\Mock\ResponseFromKrsMock;
use Tests\Unit\Mocks\LoggerMocks;

class KrsDataServiceTest extends TestCase
{
    const API_KEY = 'any';
    const WRONG_NIP = '!adsd';
    const GOOD_NIP = '1233434454';

    /**
     * @expectedException \Integration\KRSBundle\Exceptions\KRSException
     */
    public function testBadRequest()
    {
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondInvalid(self::WRONG_NIP));
        $krsService->searchByNip(self::WRONG_NIP);
    }

    /**
     * @expectedException \Integration\KRSBundle\Exceptions\KRSException
     */
    public function test500Request()
    {
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondError(self::GOOD_NIP));
        $krsService->searchByNip(self::WRONG_NIP);
    }

    /**
     * @expectedException \Integration\KRSBundle\Exceptions\KRSNotFoundException
     */
    public function testNotFoundRequest()
    {
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondNotFound(self::GOOD_NIP));
        $krsService->searchByNip(self::WRONG_NIP);
    }

    public function testSuccessRequest()
    {
        $mock = KRSObjectMock::createKRSObjectMock(self::GOOD_NIP);
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondSuccess(self::GOOD_NIP));
        $object = $krsService->searchByNip(self::GOOD_NIP);
        $this->assertSame($object->getNip(), $mock->getNip());
        $this->assertSame($object->getName(), $mock->getName());
        $this->assertSame($object->getKrs(), $mock->getKrs());
        $this->assertSame($object->getAddress()->getStreet(), $object->getAddress()->getStreet());
        $this->assertSame($object->getAddress()->getCity(), $mock->getAddress()->getCity());
    }

    public function testExtendSuccessRequest()
    {
        $mock = KRSObjectMock::createFullKRSObjectMock(self::GOOD_NIP);
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondExtendSuccess(self::GOOD_NIP));
        $object = $krsService->searchByNip(self::GOOD_NIP);
        $this->assertSame($object->getNip(), $mock->getNip());
        $this->assertSame($object->getName(), $mock->getName());
        $this->assertSame($object->getKrs(), $mock->getKrs());
        $this->assertSame($object->getAddress()->getStreet(), $object->getAddress()->getStreet());
        $this->assertSame($object->getAddress()->getCity(), $mock->getAddress()->getCity());
        $this->assertSame($object->getKrsExtraFieldsObject()->getWww(), $mock->getKrsExtraFieldsObject()->getWww());
        $this->assertSame($object->getKrsExtraFieldsObject()->getPhone(), $mock->getKrsExtraFieldsObject()->getPhone());
    }

    public function testExtendErrorRequest()
    {
        $mock = KRSObjectMock::createKRSObjectMock(self::GOOD_NIP);
        $krsService = $this->createKrsDataServiceMock(ResponseFromKrsMock::createRespondExtendError(self::GOOD_NIP));
        $object = $krsService->searchByNip(self::GOOD_NIP);
        $this->assertSame($object->getNip(), $mock->getNip());
        $this->assertSame($object->getName(), $mock->getName());
        $this->assertSame($object->getKrs(), $mock->getKrs());
        $this->assertSame($object->getAddress()->getStreet(), $object->getAddress()->getStreet());
        $this->assertSame($object->getAddress()->getCity(), $mock->getAddress()->getCity());
    }


    private function createKrsDataServiceMock(iHttpRequest $IHttpRequest) : KrsDataService
    {
        $krsObjectCreator = new KRSObjectCreator();
        return new KrsDataService(self::API_KEY, $IHttpRequest, $krsObjectCreator, LoggerMocks::createLogger());
    }
}
