<?php

namespace Tests\Integration\MailBundle\Sender;

use MailBundle\Sender\EmailSender;
use \Mockery as m;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class EmailSenderTest
 * @package MailBundle\Tests\Sender
 */
class EmailSenderTest extends TestCase
{
    /**
     * tear down
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * @return EmailSender
     */
    private function createEmailSender()
    {
        $mailer = m::mock(\Swift_Mailer::class, [
            'send' => ''
        ]);
        $templateEngine = m::mock(\Twig_Environment::class, [
            'render' => ''
        ]);
        $translator = m::mock(TranslatorInterface::class);
        $router = m::mock(RouterInterface::class);
        $address = 'http://localhost/';

        return new EmailSender($mailer, 'test@wp.pl', $templateEngine, $translator, $router, $address);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @dataProvider validAddresses
     */
    public function testIfAddresseesAreCorrectySet($ccAddresses, $bccAddresses, $ccExpected, $bccExpected)
    {
        $emailSender = $this->createEmailSender();

        $message = $emailSender->createCandidateMail(
            'test@gmail.com',
            'test',
            [],
            'content',
            $ccAddresses,
            $bccAddresses
        );

        $this->assertEquals($message->getCc(), $ccExpected);

        $this->assertEquals($message->getBcc(), $bccExpected);
    }

    /**
     * @return array
     */
    public function validAddresses()
    {
        return [
            [
                'cc1@gmail.com, cc2@gmail.com',
                'bcc1@gmail.com, bcc2@gmail.com',
                [
                    'cc1@gmail.com' => '',
                    'cc2@gmail.com' => ''
                ],
                [
                    'bcc1@gmail.com' => '',
                    'bcc2@gmail.com' => ''
                ]
            ],
            [
                'cc1@gmail.com;cc2@gmail.com',
                'bcc1@gmail.com;bcc2@gmail.com',
                [
                    'cc1@gmail.com' => '',
                    'cc2@gmail.com' => ''
                ],
                [
                    'bcc1@gmail.com' => '',
                    'bcc2@gmail.com' => ''
                ]
            ]
        ];
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @dataProvider invalidAddresses
     * @expectedException \Swift_RfcComplianceException
     */
    public function testInvalidEmailException($invalidAddress)
    {
        $emailSender = $this->createEmailSender();

        $emailSender->createCandidateMail(
            $invalidAddress,
            'test',
            [],
            'content'
        );
    }

    /**
     * @return array
     */
    public function invalidAddresses()
    {
        return [
            ['SomeString'],
            ['invalid@gmail.'],
            ['invalid1@gmail.com+=- invalid2@gmail.com']
        ];
    }
}
