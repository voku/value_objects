<?php

use Defuse\Crypto\Crypto;
use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectEmail;

/**
 * @internal
 */
final class ValueObjectEmailTest extends TestCase
{
    public function testSimpleSuccess(): void
    {
        $email = ValueObjectEmail::create('lars@moelleken.org');

        static::assertSame('lars@moelleken.org', $email . '');
        static::assertSame('lars@moelleken.org', $email->__toString());
        static::assertSame('lars@moelleken.org', $email->value());
        static::assertSame($email->value(), $email->valueOrThrowException());
    }

    public function testSimpleFail(): void
    {
        try {
            @ValueObjectEmail::create('lars@moelleken');
        } catch (Exception $exception) {
            $error = $exception->getMessage();
        }

        static::assertSame('The value "lars@moelleken" is not correct for: voku\value_objects\ValueObjectEmail', $error);
    }

    public function testDecrypt(): void
    {
        $email = ValueObjectEmail::create('lars@moelleken.org');
        $encrypted = $email->encrypt('mySimplePassword1234');
        $emailDecrypted = ValueObjectEmail::decryptFromString('mySimplePassword1234', $encrypted);

        static::assertSame('lars@moelleken.org', $emailDecrypted->value());
    }

    public function testDecryptError(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');

        ValueObjectEmail::decryptFromString('mySimplePassword1234', 'foobar');
    }

    public function testDecryptError2(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');

        $encrypted = Crypto::encryptWithPassword('lars@moelleken.org', 'test1234');
        ValueObjectEmail::decryptFromString('mySimplePassword1234', $encrypted);
    }

    public function testNullError(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');

        @ValueObjectEmail::create(null);
    }
}
