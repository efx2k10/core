<?php

namespace Efx\Core\Tests;

use Efx\Core\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_session_flash_set_get_has()
    {
        $session = new Session();

        $session->setFlash('ok', 'успешно');
        $session->setFlash('error', 'ошибка');

        $this->assertTrue($session->hasFlash('ok'));
        $this->assertTrue($session->hasFlash('error'));

        $this->assertEquals(['успешно'], $session->getFlash('ok'));
        $this->assertEquals(['ошибка'], $session->getFlash('error'));

        $this->assertEquals([], $session->getFlash('warning'));

    }
}