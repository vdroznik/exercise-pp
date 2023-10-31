<?php

namespace Tests\Integration;

use ExercisePromo\App;
use Odan\Session\SessionInterface;
use Tests\Fixtures\IpFixture;
use Tests\Fixtures\PromoFixture;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Tests\Fixtures\PromoIssueFixture;

class IpLimitTest extends TestCase
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

    public function testItDoesntIssuePromoWhenIpLimitHit()
    {
        $this->truncateTables();
        $this->insertFixtures([IpFixture::class]);

        $this->assertTableRowCount(0, 'promos_issues', 'No promos has been issued yet');
        $request = $this->createRequest('GET', '/getpromo', ['REMOTE_ADDR' => '192.168.0.1']);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContains($response, 'The number of promo codes issued for this ip is too high.');
        $this->assertTableRowCount(0, 'promos_issues', 'No new promos has been issued');
    }

    public function testItGivesOutStoredPromoWithIpLimitHit()
    {
        $this->truncateTables();
        $this->insertFixtures([PromoFixture::class, PromoIssueFixture::class, IpFixture::class]);
        $session = $this->container->get(SessionInterface::class);
        $session->set('promoId', 1);

        $this->assertTableRowCount(1, 'promos_issues', 'Promo has been issued earlier');

        $request = $this->createRequest('GET', '/getpromo', ['REMOTE_ADDR' => '192.168.0.1']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=C4L0U8PIG8', $response->getHeader('Location')[0], 'It redirects to external site with earlier generated promo code even with ip limit hit');
        $this->assertTableRowCount(1, 'promos_issues', 'Promo has been reused');
    }
}
