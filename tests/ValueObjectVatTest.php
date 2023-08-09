<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectVat;

final class ValueObjectVatTest extends TestCase
{

    public function testSimple(): void
    {
        $vat = ValueObjectVat::create('16.0');

        self::assertSame('16.0', $vat . '');
        self::assertSame('11.6', $vat->getGross(10.0));
    }

}
