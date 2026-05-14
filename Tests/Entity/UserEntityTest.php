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

namespace Plugin\ApiToken\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Plugin\ApiToken\Entity\UserEntity;

class UserEntityTest extends TestCase
{
    public function testGetIdentifier()
    {
        $userEntity = new UserEntity('test_user');
        $this->assertEquals('test_user', $userEntity->getIdentifier());
    }
}
