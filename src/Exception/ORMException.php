<?php

namespace Aesislabs\Component\Odoo\ORM\Exception;

use Aesislabs\Component\Odoo\ORM\Internal\ReflectorAwareTrait;

class ORMException extends RuntimeException
{
    use ReflectorAwareTrait;
}
