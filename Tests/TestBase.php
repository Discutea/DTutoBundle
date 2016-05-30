<?php

namespace Discutea\DTutoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Discutea\DTutoBundle\Entity\Category;
use \Discutea\DTutoBundle\Entity\Tutorial;
use \Discutea\DTutoBundle\Entity\Contribution;

class TestBase extends WebTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $client;
    
    protected $container;
    
    protected $executor;
    
    protected static $application;

    protected $clientCrawler;
    protected $member1Crawler;
    protected $member2Crawler;
    protected $moderatorCrawler;
    protected $adminCrawler;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        
        self::bootKernel();
        
        $this->client = self::createClient();
        $this->container = $this->client->getKernel()->getContainer();
        
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $this->executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $this->executor->purge();
        
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $fixtures = new \Discutea\DTutoBundle\Tests\tests\Fixtures\FosFixtures();
        $fixtures->setContainer($this->container);
        $loader->addFixture($fixtures);
        $this->executor->execute($loader->getFixtures());
        
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em = null; // avoid memory leaks
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();
            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }
        return self::$application;
    }

    protected function doLogin($username, $password) {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => $username,
            '_password'  => $password,
        ));     
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
    
        return $this->client;
    }

    private function dieError() {
        $this->assertTrue( 1 == 2 );
        die();
    }

    protected function tryUrlModerator($url) {
        return $this->tryUrl(302, 403, 403, 200, 200, $url);
    }
    
    protected function tryUrlAdmin($url) {
        $this->tryUrl(302, 403, 403, 403, 200, $url);
    }

    protected function tryUrlFull($url) {
        $this->tryUrl(200, 200, 200, 200, 200, $url);
    }
    
    protected function tryUrl($anonCode, $member1Code, $member2Code, $moderatorCode, $adminCode, $url) {
        $this->client = self::createClient();
        $this->clientCrawler = $this->client->request('GET', $url);
        $this->assertEquals($anonCode, $this->client->getResponse()->getStatusCode());
        
        $this->client = $this->doLogin('member1', 'password');
        $this->member1Crawler = $this->member1Crawler = $this->client->request('GET', $url);
        $this->assertEquals($member1Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('member2', 'password');
        $this->member2Crawler = $this->client->request('GET', $url);
        $this->assertEquals($member2Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('moderator', 'password');
        $this->moderatorCrawler = $this->client->request('GET', $url);
        $this->assertEquals($moderatorCode, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('admin', 'password');
        $this->adminCrawler = $this->client->request('GET', $url);
        $this->assertEquals($adminCode, $this->client->getResponse()->getStatusCode());
    }

    protected function addFixtruresCategory() {
        $query = $this->em->createQuery('DELETE FROM DTutoBundle:Contribution');
        $query->execute(); 
        $query1 = $this->em->createQuery('DELETE FROM DTutoBundle:Tutorial');
        $query1->execute(); 
        $query2 = $this->em->createQuery('DELETE FROM DTutoBundle:Category');
        $query2->execute(); 

        $entity = new Category();
        $entity->setTitle('category'); 
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function addFixtruresTutorial() {
        $query = $this->em->createQuery('DELETE FROM DTutoBundle:Contribution');
        $query->execute(); 
        $query1 = $this->em->createQuery('DELETE FROM DTutoBundle:Tutorial');
        $query1->execute(); 
        
        $names = array('admin', 'moderator', 'member1', 'member2');
        
        $category = $this->em->getRepository('DTutoBundle:Category')->findOneByTitle('category');
        
        foreach ($names as $name) {
            $user = $this->em->getRepository('DTutoBundleUsersEntity:Users')->findOneByUsername($name);
            if ( ($category === NULL) || ($user === NULL) ) { $this->dieError(); }
            
            $entity = 'entity'.$name;
            $entity = new Tutorial();
            $entity->setTitle($name.'TutorialTest');
            $entity->setDescription($name.'DescriptionTutorialTest');
            $entity->setCategory($category);
            
            $tmpContrib = new Contribution($user);
            $entity->setTmpContrib($tmpContrib);
            $contrib = $entity->getTmpContrib();
            
            $contrib->setContent($name . ' first content');
            $contrib->setCurrent(true);

            
            $this->em->persist($entity);
            $this->em->persist( $contrib );
            $this->em->flush();
        }
    }

    
    protected function addFixtruresContribution() {
        $query = $this->em->createQuery('DELETE FROM DTutoBundle:Contribution');
        $query->execute(); 
        $query1 = $this->em->createQuery('DELETE FROM DTutoBundle:Tutorial');
        $query1->execute(); 
        $this->addFixtruresTutorial();
        
        $names = array('admin', 'moderator', 'member1', 'member2');
        
        $tutorials = $this->em->getRepository('DTutoBundle:Tutorial')->findAll();
        
        foreach ($tutorials as $tuto) {
            if ($tuto === NULL) { $this->dieError(); }
            $i = 1;
            $slug = $tuto->getSlug();
            foreach ($names as $name) {
                $user = $this->em->getRepository('DTutoBundleUsersEntity:Users')->findOneByUsername($name);
                if ($user === NULL) { $this->dieError(); }
                $content = $name . $slug . 'content' . $i;
                $entity = 'entity'.$name;
                $entity = new Contribution();
                $entity->setTutorial($tuto);
                $entity->setContributor($user);
                $entity->setContent($content);
                $i++;

                $this->em->persist($entity);
                $this->em->flush();
            }
        }
    }
    
    
    protected function setAllStatusContribs($status) {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findAll();
        
        foreach ($contribs as $contrib) {
            $contrib->setStatus($status);
            $this->em->persist( $contrib );
            $this->em->flush();
        }
    }
}
