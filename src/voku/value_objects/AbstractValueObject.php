<?php

declare(strict_types=1);

namespace voku\value_objects;

use __PHP_Incomplete_Class;
use JsonSerializable;
use Stringable;
use Stringy\Stringy;
use Throwable;
use voku\value_objects\exceptions\InvalidValueObjectException;

/**
 * @template TValue as scalar
 */
abstract class AbstractValueObject implements Stringable, JsonSerializable
{
    /**
     * @var TValue|null
     */
    private $value;

    final private function __construct()
    {
    }

    /**
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    final public static function createEmpty(): self
    {
        $static = new static();

        $static->set(null, false);

        return $static;
    }

    /**
     * @param TValue|null $value
     *
     * @throws InvalidValueObjectException
     */
    private function set($value, bool $validate = true): void
    {
        if (
            $validate
            &&
            $this->validate($value) === false
        ) {
            throw new InvalidValueObjectException('The value "' . print_r($value ?? 'NULL', true) . '" is not correct for: ' . static::class);
        }

        $this->value = $this->parse($value);
    }

    /**
     * @param TValue|null $value
     *
     * @return TValue|null
     */
    protected function parse($value)
    {
        return $value;
    }

    /**
     * @param TValue|null $value
     */
    protected function validate($value): bool
    {
        return true;
    }

    /**
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    final public static function decryptFromString(string $password, string $data): self
    {
        return self::decrypt($password, $data);
    }

    /**
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    private static function decrypt(string $password, string $encodedData): self
    {
        $encodedData = str_replace(static::class . '_', '', $encodedData);

        $decodedData = rawurldecode($encodedData);

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

    /**
     * Get the value that are used for the database.
     *
     * @return TValue|null
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @template TValueFallback as null|TValue
     *
     * @param TValueFallback $fallback
     *
     * @return TValue|TValueFallback
     */
    final public function valueOrFallback($fallback = null)
    {
        return $this->value ?? $fallback;
    }

    /**
     * @throws InvalidValueObjectException
     *
     * @return TValue
     */
    final public function valueOrThrowException()
    {
        if ($this->value === null) {
            throw new InvalidValueObjectException('The value "NULL" is not correct for: ' . static::class);
        }

        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    final public function encrypt(string $password): string
    {
        $data = serialize($this);

        $encrypted = Stringy::create($data)->encrypt($password);

        return static::class . '_' . rawurlencode($encrypted->toString());
    }

    /**
     * @template TCreateValue of TValue
     *
     * @param TCreateValue|null $value
     *
     * @return static
     *
     * @throws InvalidValueObjectException
     */
    public static function create($value): self
    {
        $static = new static();

        $static->set($value);

        return $static;
    }

    /**
     * @return string
     */
    final public function jsonSerialize(): mixed
    {
        return (string) $this;
    }
}
