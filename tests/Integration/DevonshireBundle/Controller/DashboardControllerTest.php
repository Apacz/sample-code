<?php

namespace Tests\Integration\DevonshireBundle\Controller;

use AttachmentBundle\Entity\Attachment;
use AttachmentBundle\File\FileUploader;
use CandidateActionBundle\Entity\Action;
use CandidateActionBundle\Repository\ActionTypesRepository;
use ClientBundle\Form\ClientContactSearchType;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\Integration\Fixtures\ActionPhoneMeetingFixtures;
use Tests\Integration\Fixtures\ActionTypeFixtures;
use Tests\Integration\Fixtures\ApplicationFixtures;
use Tests\Integration\Fixtures\ApplicationSourceFixtures;
use Tests\Integration\Fixtures\ApplicationStatusFixtures;
use Tests\Integration\Fixtures\CandidateFixtures;
use Tests\Integration\Fixtures\ClientContactFixtures;
use Tests\Integration\Fixtures\ClientFixtures;
use Tests\Integration\Fixtures\ClientVarietyFixtures;
use Tests\Integration\Fixtures\CurrencyFixtures;
use Tests\Integration\Fixtures\RecruiterFixtures;
use Tests\Integration\Fixtures\RecruitmentFixtures;
use Tests\Integration\Fixtures\SourceFixtures;
use Tests\Integration\IntegrationTestCase;

class DashboardControllerTest extends IntegrationTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->loadFixtures([
            RecruiterFixtures::class,
            ClientContactFixtures::class,
            ClientFixtures::class,
            ClientVarietyFixtures::class,
        ]);
    }

    public function testDashboard()
    {
        $this->client->request('GET', '/admin/dashboard', [], [], [
            'PHP_AUTH_USER' => 'art.niemczyk@gmail.com',
            'PHP_AUTH_PW'   => 'qwe123',
        ]);

        $this->assertStatusCode(200, $this->client);
        $this->assertGreaterThan(0, count($this->client->getResponse()->getContent()));

    }
    public function testChartData()
    {
        $this->client->request('GET', '/admin/api/chart-data', [], [], [
            'PHP_AUTH_USER' => 'art.niemczyk@gmail.com',
            'PHP_AUTH_PW'   => 'qwe123',
        ]);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertStatusCode(200, $this->client);
        $this->assertGreaterThan(0, count($this->client->getResponse()->getContent()));
    }
}