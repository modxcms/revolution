<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Model\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Transport\modTransportProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Tests related to the modTransportProvider class.
 *
 * The requests to the provider are answered by a Guzzle MockHandler, which replaces the shared HTTP client service
 * during the tests, so no network access is needed.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group modTransportProvider
 */
class modTransportProviderTest extends MODxTestCase
{
    private const API_KEY = 'secret-api-key';
    private const SIGNATURE = 'testpackage-1.0.0-pl';

    /** @var modTransportProvider */
    private $provider;
    /** @var callable|null The original HTTP client service */
    private $originalClientService;
    /** @var array The requests sent to the mock handler */
    private $history = [];
    /** @var bool */
    private $sessionCreated = false;

    /**
     * @before
     * @throws \xPDO\xPDOException
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        // modTransportProvider::args() reads the manager language from the session
        if (!isset($_SESSION)) {
            $_SESSION = [];
            $this->sessionCreated = true;
        }

        if ($this->modx->services->has(ClientInterface::class)) {
            $this->originalClientService = $this->modx->services->raw(ClientInterface::class);
        }

        $this->provider = $this->modx->newObject(modTransportProvider::class);
        $this->provider->fromArray([
            'name' => 'unit-test-provider-' . uniqid(),
            'service_url' => 'http://provider.test/',
            'username' => 'unit-test',
            'api_key' => self::API_KEY,
            'created' => date('Y-m-d H:i:s'),
        ]);
        $this->provider->save();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if ($this->provider) {
            $this->provider->remove();
            $this->provider = null;
        }

        // Restore the original HTTP client service, which is registered as a factory
        if ($this->modx) {
            $this->modx->services->offsetUnset(ClientInterface::class);
            if ($this->originalClientService !== null) {
                $this->modx->services->add(
                    ClientInterface::class,
                    $this->modx->services->factory($this->originalClientService)
                );
            }
        }
        $this->originalClientService = null;

        if ($this->sessionCreated) {
            unset($_SESSION);
            $this->sessionCreated = false;
        }
        $this->history = [];
    }

    /**
     * Replace the shared HTTP client with a client that answers from the queue
     *
     * @param array $queue Responses, exceptions or callables returning a promise
     */
    private function mockClient(array $queue)
    {
        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push(Middleware::history($this->history));

        $this->modx->services->offsetUnset(ClientInterface::class);
        $this->modx->services->add(ClientInterface::class, function () use ($stack) {
            return new Client(['handler' => $stack]);
        });
    }

    /**
     * A connection failure like a cURL timeout, containing the full request URI like the real Guzzle message
     *
     * @return callable
     */
    private function connectTimeout(): callable
    {
        return function (RequestInterface $request) {
            return Create::rejectionFor(new ConnectException(
                'cURL error 28: Connection timed out after 5001 milliseconds for ' . $request->getUri(),
                $request
            ));
        };
    }

    /**
     * Load the provider again from the database
     *
     * @return modTransportProvider
     */
    private function reloadProvider(): modTransportProvider
    {
        return $this->modx->getObject(modTransportProvider::class, $this->provider->get('id'), false);
    }

    public function testUnreachableProviderIsRememberedDuringTheRequest()
    {
        $this->mockClient([$this->connectTimeout()]);

        $result = $this->provider->latest(self::SIGNATURE);
        $this->assertIsString($result, 'A failed connection should return an error message.');
        $this->assertTrue($this->provider->isUnreachable(0));

        // The second check must not send a request; the empty mock queue would fail it
        $result = $this->provider->latest('otherpackage-1.0.0-pl');
        $this->assertIsString($result);
        $this->assertCount(1, $this->history, 'An unreachable provider should only be requested once.');
    }

    public function testUnreachableProviderIsStoredWithoutCredentials()
    {
        $this->mockClient([$this->connectTimeout()]);

        $this->provider->latest(self::SIGNATURE);

        $unreachable = $this->reloadProvider()->getUnreachable();
        $this->assertIsArray($unreachable, 'The failed connection should be stored in the provider properties.');
        $this->assertGreaterThan(0, $unreachable['since']);
        $this->assertGreaterThan(0, $unreachable['last_try']);
        $this->assertStringContainsString('Connection timed out', $unreachable['error']);
        $this->assertStringContainsString('http://provider.test/package/update', $unreachable['error']);
        $this->assertStringNotContainsString(self::API_KEY, $unreachable['error']);
        $this->assertStringNotContainsString('?', $unreachable['error']);
    }

    public function testRepeatedFailureKeepsTheFirstFailureTime()
    {
        $since = time() - 3600;
        $this->provider->set('properties', [
            'unreachable' => ['since' => $since, 'last_try' => $since, 'error' => 'old error'],
        ]);
        $this->provider->save();
        $this->mockClient([$this->connectTimeout()]);

        $this->provider->latest(self::SIGNATURE);

        $unreachable = $this->reloadProvider()->getUnreachable();
        $this->assertEquals($since, $unreachable['since']);
        $this->assertGreaterThan($since, $unreachable['last_try']);
        $this->assertStringContainsString('Connection timed out', $unreachable['error']);
    }

    public function testReachableProviderClearsTheStoredFailure()
    {
        $this->provider->set('properties', [
            'other' => 'value',
            'unreachable' => ['since' => time() - 600, 'last_try' => time() - 600, 'error' => 'old error'],
        ]);
        $this->provider->save();
        // An update is returned, since SimpleXML treats an element without children as false
        $this->mockClient([new Response(200, [], '<?xml version="1.0" encoding="UTF-8"?><packages>'
            . '<package><signature>testpackage-1.0.1-pl</signature><version>1.0.1</version></package>'
            . '</packages>')]);

        $result = $this->provider->latest(self::SIGNATURE);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($this->provider->getUnreachable());
        $reloaded = $this->reloadProvider();
        $this->assertNull($reloaded->getUnreachable(), 'The stored failure should be removed after a response.');
        $this->assertEquals('value', $reloaded->get('properties')['other'], 'Other properties should be kept.');
    }

    public function testHttpErrorIsNotAConnectionFailure()
    {
        // The provider answers with an XML error message, like a provider error response
        $error = '<?xml version="1.0" encoding="UTF-8"?><error><message>Internal Server Error</message></error>';
        $this->mockClient([
            new Response(500, [], $error),
            new Response(500, [], $error),
        ]);

        $this->assertIsString($this->provider->latest(self::SIGNATURE));
        $this->assertIsString($this->provider->latest('otherpackage-1.0.0-pl'));

        $this->assertFalse($this->provider->isUnreachable(300));
        $this->assertNull($this->reloadProvider()->getUnreachable());
        $this->assertCount(2, $this->history, 'A provider that responds should be requested again.');
    }

    /**
     * @dataProvider providerRetryInterval
     * @param int $lastTryAgo
     * @param int $interval
     * @param bool $expected
     */
    public function testRetryInterval($lastTryAgo, $interval, $expected)
    {
        $this->provider->set('properties', [
            'unreachable' => ['since' => time() - 3600, 'last_try' => time() - $lastTryAgo, 'error' => 'error'],
        ]);

        $this->assertSame($expected, $this->provider->isUnreachable($interval));
    }

    /**
     * @return array
     */
    public function providerRetryInterval()
    {
        return [
            'tried recently' => [60, 300, true],
            'interval passed' => [600, 300, false],
            'interval disabled' => [60, 0, false],
        ];
    }

    public function testProviderWithoutStoredFailureIsReachable()
    {
        $this->assertNull($this->provider->getUnreachable());
        $this->assertFalse($this->provider->isUnreachable(300));
    }
}
