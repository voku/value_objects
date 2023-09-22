<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use Stringy\Stringy;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
class ValueObjectString extends AbstractValueObject
{
    public function isEmpty(): bool
    {
        return $this->o()->isEmpty();
    }

    /**
     * get a string (o)bject
     */
    private function o(): Stringy
    {
        return Stringy::create($this->value());
    }

    public function isNotEmpty(): bool
    {
        return $this->o()->isNotEmpty();
    }

    /**
     * @param self|string $other
     */
    public function isEquals($other): bool
    {
        if (\is_string($other)) {
            /* @noinspection CallableParameterUseCaseInTypeContextInspection */
            $other = self::create($other);
        }

        return $this->o()->isEquals($other->o());
    }

    /**
     * @return static
     */
    public function extractIntegers(): self
    {
        return self::create($this->o()->extractIntegers()->toString());
    }

    /**
     * @return static
     */
    public function extractSpecialCharacters(): self
    {
        return self::create($this->o()->extractSpecialCharacters()->toString());
    }

    public function length(): int
    {
        return $this->o()->length();
    }

    /**
     * @return static
     */
    public function replace(string $search, string $replacement): self
    {
        return self::create($this->o()->replace($search, $replacement)->toString());
    }

    public function contains(string $needle): bool
    {
        return $this->o()->contains($needle);
    }

    /**
     * @param string[] $needles
     */
    public function containsAny(array $needles): bool
    {
        return $this->o()->containsAny($needles);
    }

    /**
     * @param string[] $needles
     */
    public function containsAll(array $needles): bool
    {
        return $this->o()->containsAll($needles);
    }

    /**
     * @return static
     */
    public function toUpperCase(): self
    {
        return self::create($this->o()->toUpperCase()->toString());
    }

    /**
     * @return static
     */
    public function toLowerCase(): self
    {
        return self::create($this->o()->toLowerCase()->toString());
    }

    /**
     * @return static
     */
    public function base64Decode(): self
    {
        return self::create($this->o()->base64Decode()->toString());
    }

    /**
     * @return static
     */
    public function base64Encode(): self
    {
        return self::create($this->o()->base64Encode()->toString());
    }

    /**
     * @return static
     */
    public function escape(): self
    {
        return self::create($this->o()->escape()->toString());
    }

    /**
     * @return static
     */
    public function append(string ...$suffix): self
    {
        return self::create($this->o()->append(...$suffix)->toString());
    }

    /**
     * @return static
     */
    public function prepend(string ...$prefix): self
    {
        return self::create($this->o()->prepend(...$prefix)->toString());
    }

    /**
     * @return static
     */
    public function truncate(int $length = 0, string $substring = ''): self
    {
        return self::create($this->o()->truncate($length, $substring)->toString());
    }

    /**
     * @return static
     */
    public function removeXss(): self
    {
        return self::create($this->o()->removeXss()->toString());
    }

    /**
     * @return static
     */
    public function encryptWithPassword(string $password): self
    {
        return self::create($this->o()->encrypt($password)->toString());
    }

    /**
     * @return static
     */
    public function decryptWithPassword(string $password): self
    {
        return self::create($this->o()->decrypt($password)->toString());
    }

    /**
     * @return static
     */
    public function appendRandomString(int $length, string $possibleChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'): self
    {
        return self::create($this->o()->appendRandomString($length, $possibleChars)->toString());
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        return true;
    }

    //
    // INFO: we can add more methods from Stringy here ...
    //
}
