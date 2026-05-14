<?php

/*
 * This file is part of ApiToken42
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ApiToken42\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
    public function __construct(private string $identifier)
    {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
