<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectHtmlEscape;

/**
 * @internal
 */
final class ValueObjectHtmlEscapeTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectHtmlEscape::create('<p><b></b><a></a>broken-html');

        ValueObjectHtmlEscapeTest::assertSame('&lt;p&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;broken-html', $str . '');
        ValueObjectHtmlEscapeTest::assertSame('&lt;p&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;broken-html', $str->__toString());
        ValueObjectHtmlEscapeTest::assertSame('&lt;p&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;broken-html', $str->value());
    }
}
