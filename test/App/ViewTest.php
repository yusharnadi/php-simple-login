<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('Home/index', [
            'title' => "Php Login Management"
        ]);

        self::expectOutputRegex('[Php Login Management]');
    }
}
