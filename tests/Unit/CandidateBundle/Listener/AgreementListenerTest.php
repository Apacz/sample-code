<?php
/**
 * Created by PhpStorm.
 * User: Paweł Nowak
 * Date: 01.07.2019
 * Time: 10:00
 */

namespace Unit\CandidateBundle\Listener;

use CandidateBundle\Creator\AgreementCreator;
use CandidateBundle\Entity\Agreement;
use CandidateBundle\Event\AgreementCreateEvent;
use CandidateBundle\Listener\AgreementListener;
use CandidateBundle\Repository\AgreementRepository;
use PHPUnit\Framework\TestCase;
use RecruitmentBundle\Entity\Candidate;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tests\Unit\Mocks\AgreementsServiceMocks;
use Tests\Unit\Mocks\CandidateMocks;
use Tests\Unit\Mocks\EntityManagerMocks;
use Tests\Unit\Mocks\TranslatorMocks;
use Tests\Unit\Mocks\UserMocks;

class AgreementListenerTest extends TestCase
{
    /**
     * @var AgreementListener
     */
    private $agreementListener;

    protected function setUp()
    {
        $em = EntityManagerMocks::createEntityManagerInterface();
        EntityManagerMocks::loadGdprRepository();
        $this->agreementListener = new AgreementListener($em, new AgreementCreator(TranslatorMocks::create()));
    }

    public function testOnNewCandidateWithoutAgreementCreate()
    {
        $GDPRCreateEvent = new AgreementCreateEvent(
            CandidateMocks::createCandidate(),
            null,
            AgreementRepository::AGREEMENT_EMAIL_SOURCE
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));
        $this->assertEmpty($this->filterOnlyActiveAgreements($agreements));
    }

    public function testOnNewCandidateWithAgreementCreate()
    {
        $GDPRCreateEvent = new AgreementCreateEvent(
            $this->createCandidateWithAgreements(false, false),
            null,
            AgreementRepository::AGREEMENT_EMAIL_SOURCE
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));
        $this->assertSame(1, count($this->filterOnlyActiveAgreements($agreements)));
        $this->assertTrue($agreements[0]->IsActive());
        $this->assertSame( AgreementRepository::AGREEMENT_EMAIL_SOURCE, $agreements[0]->getSource());
        $this->assertSame(AgreementRepository::AGREEMENT_GENERAL_TYPE, $agreements[0]->getType());
    }

    public function testOnAgreementCreate()
    {
        $candidate = $this->createCandidateWithAgreements(false);
        $GDPRCreateEvent = new AgreementCreateEvent(
            $candidate,
            CandidateMocks::createCandidate(),
            AgreementRepository::AGREEMENT_EMAIL_SOURCE
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));
        $this->assertSame(2, count($this->filterOnlyActiveAgreements($agreements)));
        $this->assertTrue($candidate->isFirstAgreement());
        $this->assertTrue($candidate->isAgreementRecruitment());
        foreach ($this->filterOnlyChnagedAgreements($agreements) as $agreement) {
            /** @var Agreement $agreement */
            $this->assertSame(AgreementRepository::AGREEMENT_EMAIL_SOURCE, $agreement->getSource());
            $this->assertContains($agreement->getType(), [
                AgreementRepository::AGREEMENT_GENERAL_TYPE,
                AgreementRepository::AGREEMENT_RECRUITMENTS_TYPE
            ]);
        }
    }

    public function testOnAgreementCreateByConsultant()
    {
        $GDPRCreateEvent = new AgreementCreateEvent(
            $this->createCandidateWithAgreements(),
            CandidateMocks::createCandidate(),
            AgreementRepository::AGREEMENT_PANEL_CANDIDATE_SOURCE,
            UserMocks::createUser()
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));
        $this->assertSame(3, count($this->filterOnlyActiveAgreements($agreements)));
        foreach ($agreements as $agreement) {
            /** @var Agreement $agreement */
            $this->assertTrue($agreement->IsActive());
            $this->assertSame(AgreementRepository::AGREEMENT_PANEL_CANDIDATE_SOURCE, $agreement->getSource());
            $this->assertContains($agreement->getType(), [
                AgreementRepository::AGREEMENT_GENERAL_TYPE,
                AgreementRepository::AGREEMENT_MARKETING_TYPE,
                AgreementRepository::AGREEMENT_RECRUITMENTS_TYPE
            ]);
        }
    }

    public function testOnAgreementCreateChange()
    {
        $candidate = $this->createCandidateWithAgreements(false, false);
        $GDPRCreateEvent = new AgreementCreateEvent(
            $candidate,
            $this->createCandidateWithAgreements(),
            AgreementRepository::AGREEMENT_PANEL_CONSULTANT_SOURCE,
            UserMocks::createUser()
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));
        $this->assertSame(2, count($this->filterOnlyChnagedAgreements($agreements)));
        $this->assertFalse($candidate->isAgreementMarketing());
        $this->assertFalse($candidate->isAgreementRecruitment());
        $this->assertTrue($candidate->isFirstAgreement());
        foreach ($this->filterOnlyChnagedAgreements($agreements) as $agreement) {
            /** @var Agreement $agreement */
            $this->assertSame(AgreementRepository::AGREEMENT_PANEL_CONSULTANT_SOURCE, $agreement->getSource());
            $this->assertContains($agreement->getType(), [
                AgreementRepository::AGREEMENT_MARKETING_TYPE,
                AgreementRepository::AGREEMENT_RECRUITMENTS_TYPE
            ]);
        }
    }

    public function testOnAgreementNoCreateChange()
    {
        $GDPRCreateEvent = new AgreementCreateEvent(
            $this->createCandidateWithAgreements(true, true),
            $this->createCandidateWithAgreements(true, true),
            AgreementRepository::AGREEMENT_PANEL_CONSULTANT_SOURCE,
            UserMocks::createUser()
        );
        $agreements = $this->agreementListener->onAgreementCreate($GDPRCreateEvent);
        $this->assertSame(3, count($agreements));           //save decizions in database
        $this->assertEmpty($this->filterOnlyChnagedAgreements($agreements));    //dont mark to show as last changed
    }

    private function createCandidateWithAgreements($marketing = true, $recruitment = true) : Candidate
    {
        $candidate = CandidateMocks::createCandidate();
        $candidate->setFirstAgreement(true);
        $candidate->setAgreementMarketing($marketing);
        $candidate->setAgreementRecruitment($recruitment);
        return $candidate;
    }

    private function filterOnlyActiveAgreements(array $agreements) : array
    {
        return array_filter($agreements, function ($val) {
            if ($val instanceof Agreement) {
                return $val->IsActive() === true;
            }
            return false;
        });
    }

    private function filterOnlyChnagedAgreements(array $agreements) : array
    {
        return array_filter($agreements, function ($val) {
            if ($val instanceof Agreement) {
                return $val->IsActive() !== null;
            }
            return false;
        });
    }
}
