<?php

namespace Plugin\ApiToken42\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Plugin\ApiToken42\Entity\UserEntity;

class UserEntityTest extends TestCase
{
    public function testGetIdentifier()
    {
        $userEntity = new UserEntity('test_user');
        $this->assertEquals('test_user', $userEntity->getIdentifier());
    }
}
