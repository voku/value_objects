<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use __PHP_Incomplete_Class;
use JsonSerializable;
use Stringable;
use Stringy\Stringy;
use Throwable;
use voku\ValueObjects\exceptions\InvalidValueObjectException;

/**
 * @template TValue
 *
 * @implements InterfaceValueObject<TValue>
 */
abstract class AbstractValueObject implements Stringable, JsonSerializable, InterfaceValueObject
{
    /**
     * @var TValue
     *
     * @readonly
     */
    private $value;

    final private function __construct()
    {
    }

    /**
     * @template TCreateValue of TValue
     *
     * @param TCreateValue $value
     *
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    public static function create($value): InterfaceValueObject
    {
        $static = new static();

        $static->set($value);

        return $static;
    }

    /**
     * @param TValue $value
     *
     * @throws InvalidValueObjectException
     */
    private function set($value, bool $validate = true): void
    {
        $value = $this->parseBeforeValidation($value);

        if (
            $validate
            &&
            $this->validate($value) === false
        ) {
            throw new InvalidValueObjectException('The value "' . print_r($value ?? 'NULL', true) . '" is not correct for: ' . static::class);
        }

        $this->value = $this->parseAfterValidation($value);
    }

    /**
     * @param mixed $value
     *
     * @return TValue
     */
    protected function parseBeforeValidation($value)
    {
        return $value;
    }

    /**
     * @param TValue $value
     *
     * @return mixed
     */
    protected function parseAfterValidation($value)
    {
        return $value;
    }

    /**
     * @param TValue $value
     */
    abstract protected function validate($value): bool;

    /**
     * @return InterfaceValueObject<TValue>
     *
     * @throws InvalidValueObjectException
     */
    public static function createByDecrypt(string $password, string $data): InterfaceValueObject
    {
        $data = str_replace(static::class . '_', '', $data);

        $decodedData = rawurldecode($data);

        try {
            $decrypted = Stringy::create($decodedData)->decrypt($password);
        } catch (Throwable $e) {
            throw new InvalidValueObjectException($e->getMessage(), $e->getCode(), $e);
        }

        $object = unserialize($decrypted->toString(), ['allowed_classes' => [static::class]]);
        if ($object instanceof __PHP_Incomplete_Class) {
            throw new InvalidValueObjectException('Unserialized object "' . $decrypted->toString() . '" is not of type "' . static::class . '"');
        }

        return $object;
    }

    final public function encrypt(string $password): string
    {
        $data = serialize($this);

        $encrypted = Stringy::create($data)->encrypt($password);

        return static::class . '_' . rawurlencode($encrypted->toString());
    }

    /**
     * @return TValue
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return TValue
     */
    public function valueForDatabase()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     *
     * @param InterfaceValueObject<mixed> $other
     */
    final public function equals(InterfaceValueObject $other): bool
    {
        return $other->value() == $this->value();
    }

    /**
     * @inheritDoc
     *
     * @param InterfaceValueObject<TValue> $other
     */
    final public function same(InterfaceValueObject $other): bool
    {
        return $other instanceof static && $other->value() === $this->value();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * @return string
     */
    final public function jsonSerialize(): mixed
    {
        return (string) $this;
    }
}
