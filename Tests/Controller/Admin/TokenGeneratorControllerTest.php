<?php

namespace Plugin\ApiToken42\Tests\Controller\Admin;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class TokenGeneratorControllerTest extends AbstractAdminWebTestCase
{
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->generateUrl('admin_api_token_generator'));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
    }

    public function testGenerateClientNotFound()
    {
        // ここでは単純な 404 ケースを、実データなしで identifier 指定して確認する
        $this->client->request('POST', $this->generateUrl('admin_api_token_generator_generate', ['identifier' => 'nonexistent']));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
