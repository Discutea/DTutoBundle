<?php

namespace Discutea\DTutoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Discutea\DTutoBundle\DependencyInjection\TutorialExtension;

class DTutoBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TutorialExtension();
    }
}
