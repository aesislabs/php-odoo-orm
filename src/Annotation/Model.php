<?php

namespace Aesislabs\Component\Odoo\ORM\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Model
{
    /**
     * @var string
     *
     * @Required
     */
    public $name;

    /**
     * @var string
     */
    public $repositoryClass;
}
