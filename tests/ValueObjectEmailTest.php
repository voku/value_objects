<?php

use Defuse\Crypto\Crypto;
use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectEmail;

/**
 * @internal
 */
final class ValueObjectEmailTest extends TestCase
{
    public function testSimpleSuccess(): void
    {
        $email = ValueObjectEmail::create('lars@moelleken.org');

        ValueObjectEmailTest::assertSame('lars@moelleken.org', $email . '');
        ValueObjectEmailTest::assertSame('lars@moelleken.org', $email->__toString());
        ValueObjectEmailTest::assertSame('lars@moelleken.org', $email->value());
    }

    public function testSimpleFail(): void
    {
        try {
            @ValueObjectEmail::create('lars@moelleken');
        } catch (Exception $exception) {
            $error = $exception->getMessage();
        }

        /* @phpstan-ignore-next-line | failing test */
        ValueObjectEmailTest::assertSame('The value "lars@moelleken" is not correct for: voku\ValueObjects\ValueObjectEmail', $error);
    }

    public function testDecrypt(): void
    {
        $email = ValueObjectEmail::create('lars@moelleken.org');
        $encrypted = $email->encrypt('mySimplePassword1234');
        $emailDecrypted = ValueObjectEmail::createByDecrypt('mySimplePassword1234', $encrypted);

        ValueObjectEmailTest::assertSame('lars@moelleken.org', $emailDecrypted->value());
    }

    public function testDecryptError(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');

        ValueObjectEmail::createByDecrypt('mySimplePassword1234', 'foobar');
    }

    public function testDecryptError2(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');

        $encrypted = Crypto::encryptWithPassword('lars@moelleken.org', 'test1234');
        ValueObjectEmail::createByDecrypt('mySimplePassword1234', $encrypted);
    }

    public function testNullError(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        /* @phpstan-ignore-next-line | failing test */
        @ValueObjectEmail::create(null);
    }
}
