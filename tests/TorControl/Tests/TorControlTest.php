<?php

/*
 * This file is part of the TorControl package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TorControl\Tests;

use TorControl\TorControl;
use TorControl\Exception\IOError;

/**
 * @author dunglas
 */
class TorControlTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->torControl = new TorControl(array('foo' => 'bar'));
    }

    public function tearDown()
    {
        $this->torControl->quit();
    }

    /**
     * Quote function.
     */
    public function testQuote()
    {
        // Must return "test"
        $this->assertEquals('"test"', TorControl::quote('test'));
        // Must return "\""
        $this->assertEquals('"\\""', TorControl::quote('"'));
        // Must return "\\"
        $this->assertEquals('"\\\\"', TorControl::quote('\\'));
        // Must return "\\\""
        $this->assertEquals('"\\\\\\""', TorControl::quote('\\"'));
    }

    /**
     * Option systtem.
     */
    public function testOption()
    {
        $this->assertEquals('bar', $this->torControl->getOption('foo'));
    }

    /**
     * Connection.
     */
    public function testConnect()
    {
        $this->assertEquals(false, $this->torControl->isConnected());

        try {
            $this->torControl->executeCommand('PROTOCOLINFO');
            $this->fail('IOError exception has not been raised.');
        } catch (IOError $e) {
        }

        $this->torControl->connect();
        $this->assertEquals(true, $this->torControl->isConnected());

        $this->torControl->quit();
        $this->assertEquals(false, $this->torControl->isConnected());
    }

    /**
     * Authentication.
     */
    public function testAuthenticate()
    {
        $this->torControl->connect();
        $this->torControl->authenticate();

        $this->assertFalse($this->torControl->getOption('authmethod') === TorControl::AUTH_METHOD_NOT_SET);
    }
}
