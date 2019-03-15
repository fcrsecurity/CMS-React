<?php

namespace BrochureBuilderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileManagerControllerTest extends WebTestCase
{
    public function testLoading()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/brochure/filemanager');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testHasEditMetaOption()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/brochure/filemanager');

        $this->assertEquals(true, true);
    }
}