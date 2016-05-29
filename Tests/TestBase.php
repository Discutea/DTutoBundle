<?php

namespace Discutea\DTutoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Discutea\DTutoBundle\Tests\tests\src\Entity\Users as User;

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
    
}
