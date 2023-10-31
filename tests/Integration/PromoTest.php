<?php

namespace Tests\Integration;

use ExercisePromo\App;
use Odan\Session\SessionInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Tests\Fixtures\PromoFixture;
use Tests\Fixtures\PromoIssueFixture;
use Tests\Fixtures\SequenceFixture;
use Tests\Fixtures\SequenceMaxFixture;

class PromoTest extends TestCase
{
    use ContainerTestTrait;
    use HttpTestTrait;
    use DatabaseTestTrait;

    protected App $app;

    protected function setUp(): void
    {
        $this->app = $GLOBALS['app'];
        $this->setUpContainer($GLOBALS['container']);
        $session = $this->container->get(SessionInterface::class);
        $session->clear();
    }

    public function testIssuePromo()
    {
        $this->truncateTables();
        $this->insertFixtures([PromoFixture::class, SequenceFixture::class]);
        $this->assertTableRowCount(0, 'promos_issues', 'No promos has been issued yet');

        $request = $this->createRequest('GET', '/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=C4L0U8PIG8', $response->getHeader('Location')[0], 'It redirects to external site with issued promo');
        $this->assertTableRowCount(1, 'promos_issues', 'Promo has been issued');
        $this->assertTableRowCount(1, 'ips', 'Ip has been tracked');
    }

    public function testItReusesIssuedPromoFromSession()
    {
        $this->truncateTables();
        $this->insertFixtures([PromoFixture::class, SequenceFixture::class, PromoIssueFixture::class]);
        $session = $this->container->get(SessionInterface::class);
        $session->set('promoId', 1);

        $this->assertTableRowCount(1, 'promos_issues', 'Promo has been issued earlier');

        $request = $this->createRequest('GET', '/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=C4L0U8PIG8', $response->getHeader('Location')[0], 'It redirects to external site with earlier issued promo');
        $this->assertTableRowCount(1, 'promos_issues', 'Promo is reused');
    }

    public function testOutOfPromos()
    {
        $this->truncateTables();
        $this->insertFixtures([PromoFixture::class, SequenceMaxFixture::class]);

        $request = $this->createRequest('GET', '/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContains($response, 'Bad luck. We have ran out of promo codes.');
        $this->assertTableRowCount(0, 'promos_issues', 'No new promos has been issued');

    }
}
