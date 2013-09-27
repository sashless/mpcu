<?php

namespace Less\MpcuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    public function testSave()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/save');
    }

    public function testGet_next()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/get_next');
    }

}
