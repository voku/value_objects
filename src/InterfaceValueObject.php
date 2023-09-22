<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\ValueObjects\exceptions\InvalidValueObjectException;

/**
 * @template TValue
 */
interface InterfaceValueObject
{
    /**
     * @template TCreateValue of TValue
     *
     * @param TCreateValue $value
     *
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    public static function create(mixed $value): InterfaceValueObject;

    /**
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    public static function createByDecrypt(string $password, string $data): InterfaceValueObject;

    public function encrypt(string $password): string;

    /**
     * @return TValue
     */
    public function value();

    /**
     * @return mixed
     */
    public function valueForDatabase();

    /**
     * Checks whether a value of a value object is equals to another.
     *
     * @param InterfaceValueObject<mixed> $other The value object to compare.
     *
     * @return boolean TRUE if the two value objects are equals, FALSE otherwise.
     */
    public function equals(InterfaceValueObject $other): bool;

    /**
     * Checks whether a value object is the same class and same value.
     *
     * @param InterfaceValueObject<TValue> $other The value object to compare.
     *
     * @return boolean TRUE if the two value objects are equals, FALSE otherwise.
     */
    public function same(InterfaceValueObject $other): bool;
}