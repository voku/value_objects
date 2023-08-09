<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectHtmlEscape;

final class ValueObjectHtmlEscapeTest extends TestCase
{

    public function testSimple(): void
    {
        $str = ValueObjectHtmlEscape::create('<p><b></b><a></a>broken-html');

        self::assertSame('&lt;p&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;broken-html', $str . '');
        self::assertSame('&lt;p&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;broken-html', $str->__toString());
        self::assertSame($str->value(), $str->valueOrThrowException());
    }

}
