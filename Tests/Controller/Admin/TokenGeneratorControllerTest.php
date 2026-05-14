<?php

/*
 * This file is part of ApiToken
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ApiToken\Tests\Controller\Admin;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
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
