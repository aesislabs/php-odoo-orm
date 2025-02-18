<?php

namespace Aesislabs\Component\Odoo\ORM\Mapping;

use Aesislabs\Component\Odoo\ORM\Exception\RuntimeException;
use Aesislabs\Component\Odoo\ORM\Internal\ReflectorAwareTrait;

class MetadataLoader
{
    use ReflectorAwareTrait;

    private $classMetadataFactory;

    public function __construct(ClassMetadataFactory $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * Load metadata file classes and returns a new class metadata registry.
     *
     * @param string[]|string $paths
     *
     * @return ClassMetadata[]
     */
    public function load($paths): array
    {
        $paths = array_filter((array) $paths);
        $classes = get_declared_classes();
        [$loadedFiles, $loadedClasses] = [[], []];
        $reflector = self::getReflector();

        foreach ($paths as $path) {
            $filename = realpath($path);

            if (!$filename) {
                throw new RuntimeException(sprintf('The path "%s" is not valid', $path));
            }

            foreach ($classes as $key => $className) {
                $reflectionClass = $reflector->getClass($className);

                if (array_key_exists($reflectionClass->getName(), $loadedClasses)) {
                    continue;
                }

                if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                    continue;
                }

                $classFilename = $reflectionClass->getFileName();

                if (!$classFilename || in_array($classFilename, $loadedFiles, true)) {
                    continue;
                }

                if (false === (0 === strpos($classFilename, $filename))) {
                    continue;
                }

                $classMetadata = $this->classMetadataFactory->getClassMetadata($reflectionClass);

                $loadedFiles[] = $classFilename;
                $loadedClasses[$reflectionClass->getName()] = $classMetadata;
            }
        }

        return $loadedClasses;
    }
}
