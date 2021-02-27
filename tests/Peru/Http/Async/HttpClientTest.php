<?php

declare(strict_types=1);

namespace Tests\Peru\Http\Async;

use function Clue\React\Block\await;
use Peru\Http\Async\ClientInterface;
use Peru\Http\Async\HttpClient;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class HttpClientTest extends TestCase
{
    /**
     * @var LoopInterface
     */
    private $loop;
    /**
     * @var ClientInterface
     */
    private $client;

    protected function setUp()
    {
        $this->loop = Factory::create();
        $this->client = new HttpClient($this->loop);
    }

    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $result = await($this->client->getAsync('http://httpbin.org/get?value=1'), $this->loop);

        $obj = json_decode($result);

        $this->assertTrue(isset($obj->args->value));
    }

    /**
     * @expectedException \RuntimeException
     * @throws \Exception
     */
    public function testGetWithError()
    {
        await($this->client->getAsync('http://http323bin.org'), $this->loop);
    }

    /**
     * @throws \Exception
     */
    public function testPost()
    {
        $data = json_encode(['result' => 42]);
        $result = await($this->client->postAsync('https://httpbin.org/post', $data, [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data)
        ]), $this->loop);

        $obj = json_decode($result);

        $this->assertEquals(42, $obj->json->result);
    }

    /**
     * @expectedException \RuntimeException
     * @throws \Exception
     */
    public function testPostWithError()
    {
        await($this->client->postAsync('https://httpbin323.org/post', ''), $this->loop);
    }

    protected function tearDown()
    {
        $this->loop->run();
    }
}
