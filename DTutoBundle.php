<?php

namespace Discutea\DTutoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Discutea\DTutoBundle\DependencyInjection\TutorialExtension;

/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class DTutoBundle extends Bundle
{
    public function getContainerExtension()
    {
       return new TutorialExtension();
    }
}
