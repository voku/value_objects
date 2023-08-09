<?php

declare(strict_types=1);

namespace voku\value_objects;

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

    public function extractIntegers(): static
    {
        return self::create($this->o()->extractIntegers()->toString());
    }

    public function extractSpecialCharacters(): static
    {
        return self::create($this->o()->extractSpecialCharacters()->toString());
    }

    public function length(): int
    {
        return $this->o()->length();
    }

    public function replace(string $search, string $replacement): static
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

    public function toUpperCase(): static
    {
        return self::create($this->o()->toUpperCase()->toString());
    }

    public function toLowerCase(): static
    {
        return self::create($this->o()->toLowerCase()->toString());
    }

    public function base64Decode(): static
    {
        return self::create($this->o()->base64Decode()->toString());
    }

    public function base64Encode(): static
    {
        return self::create($this->o()->base64Encode()->toString());
    }

    public function escape(): static
    {
        return self::create($this->o()->escape()->toString());
    }

    public function append(string ...$suffix): static
    {
        return self::create($this->o()->append(...$suffix)->toString());
    }

    public function prepend(string ...$prefix): static
    {
        return self::create($this->o()->prepend(...$prefix)->toString());
    }

    public function truncate(int $length = 0, string $substring = ''): static
    {
        return self::create($this->o()->truncate($length, $substring)->toString());
    }

    public function removeXss(): static
    {
        return self::create($this->o()->removeXss()->toString());
    }

    public function encryptWithPassword(string $password): static
    {
        return self::create($this->o()->encrypt($password)->toString());
    }

    public function decryptWithPassword(string $password): static
    {
        return self::create($this->o()->decrypt($password)->toString());
    }

    public function appendRandomString(int $length, string $possibleChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'): static
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

        return parent::validate($value);
    }

    //
    // INFO: we can add more methods from Stringy here ...
    //
}
