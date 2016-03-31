<?php
namespace HomeBundle\Twig;

use Ramsey\Uuid\Uuid;

class UuidExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('uuid4', function() { return Uuid::uuid4()->toString(); })
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'uuid_extension';
    }
}
