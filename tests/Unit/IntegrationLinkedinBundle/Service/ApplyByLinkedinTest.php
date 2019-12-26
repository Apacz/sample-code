<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 23.08.2019
 * Time: 11:57
 */

namespace Tests\Unit\IntegrationLinkedinsBundle\Service;

use DevonshireBundle\Interfaces\iHttpRequest;
use Integration\KRSBundle\Creator\KRSObjectCreator;
use Integration\KRSBundle\Exceptions\KRSException;
use Integration\KRSBundle\Exceptions\KRSNotFoundException;
use Integration\KRSBundle\Services\KrsDataService;
use Integration\LinkedinBundle\Services\ApplyByLinkedIn;
use PHPUnit\Framework\TestCase;
use RecruitmentBundle\Entity\Candidate;
use Tests\Unit\IntegrationKrsBundle\Mock\KRSObjectMock;
use Tests\Unit\IntegrationKrsBundle\Mock\ResponseFromKrsMock;
use Tests\Unit\IntegrationLinkedinBundle\Mock\LinkedinMock;
use Tests\Unit\IntegrationLinkedinBundle\Mock\ResponseFromLinkedinMock;
use Tests\Unit\Mocks\CandidateMocks;
use Tests\Unit\Mocks\LoggerMocks;

class ApplyByLinkedinTest extends TestCase
{


    public function testSuccessRequest()
    {
        $applyByLinkedinMock = new ApplyByLinkedIn(LinkedinMock::creatLinkedinWithBasicMock());
        $candidate = new Candidate();
        $applyByLinkedinMock->init(ResponseFromLinkedinMock::CALLBACK, ResponseFromLinkedinMock::CODE);
        $applyByLinkedinMock->applyByLinkedin(ResponseFromLinkedinMock::CALLBACK);
        $applyByLinkedinMock->getInformationAboutMe($candidate);

        $this->assertSame($candidate->getFirstName(), CandidateMocks::CANDIDATE_FIRST_NAME);
        $this->assertSame($candidate->getLastName(), CandidateMocks::CANDIDATE_LAST_NAME);
        $this->assertEmpty($candidate->getEmail());
    }

    public function testExtendSuccessRequest()
    {
        $applyByLinkedinMock = new ApplyByLinkedIn(LinkedinMock::creatLinkedinWithEmailMock());
        $candidate = new Candidate();
        $applyByLinkedinMock->init(ResponseFromLinkedinMock::CALLBACK, ResponseFromLinkedinMock::CODE);
        $applyByLinkedinMock->applyByLinkedin(ResponseFromLinkedinMock::CALLBACK);
        $applyByLinkedinMock->getInformationAboutMe($candidate);

        $this->assertSame($candidate->getFirstName(), CandidateMocks::CANDIDATE_FIRST_NAME);
        $this->assertSame($candidate->getLastName(), CandidateMocks::CANDIDATE_LAST_NAME);
        $this->assertSame($candidate->getEmail(), CandidateMocks::CANDIDATE_EMAIL);
    }

    public function testApplyByLinkedinRequest()
    {
        $applyByLinkedinMock = new ApplyByLinkedIn(LinkedinMock::creatLinkedinWithBasicMock());
        $this->assertSame($applyByLinkedinMock->applyByLinkedin(
            ResponseFromLinkedinMock::CALLBACK),
            ResponseFromLinkedinMock::LOGIN_URL
        );

    }

}
