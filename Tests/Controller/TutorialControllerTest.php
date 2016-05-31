<?php
namespace Discutea\DTutoBundle\Tests\Controller;

use Discutea\DTutoBundle\Tests\TestBase;


/**
 * TutorialControllerTest
 * 
 * @package  DTutorialBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TutorialControllerTest extends TestBase
{

    public function testIndexAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('discutea_tuto_homepage');
        $this->tryUrlFull($url); //start test content if empty
        
        $this->addFixtruresCategory();
        $this->addFixtruresTutorial();
        
        $this->tryUrlFull($url);

        $this->setAllStatusContribs(1);
        $this->tryUrlFull($url);

        $this->setAllStatusContribs(2);
        $this->tryUrlFull($url);

        $this->setAllStatusContribs(3);
        $this->tryUrlFull($url);
        
        $this->addFixtruresContribution(); // test fixtures
    }
    
}
