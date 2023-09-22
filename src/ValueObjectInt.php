<?php

declare(strict_types=1);

namespace voku\ValueObjects;

/**
 * @template TValue as int
 * @extends AbstractValueObject<TValue>
 *
 * @immutable
 */
class ValueObjectInt extends AbstractValueObject
{
    private const OPERATION_ADD = 'add';

    private const OPERATION_SUB = 'sub';

    private const OPERATION_MUL = 'mul';

    private const OPERATION_DIV = 'div';

    private const OPERATION_POW = 'pow';

    private const OPERATION_MOD = 'mod';

    private const OPERATION_POWMOD = 'powmod';

    private const OPERATION_SQRT = 'sqrt';

    private const OPERATION_COMPARE = 'compare';

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function add($num): self
    {
        return self::create($this->performOperation(self::OPERATION_ADD, $num));
    }

    protected function parseBeforeValidation($value)
    {
        if (!\is_int($value) && (string)(int)$value === (string)$value) {
            $value = (int)$value;
        }

        return $value;
    }

    /**
     * @param int|static<TValue>|null                       $num
     * @param int|static<TValue>|null                       $mod
     *
     * @phpstan-param ValueObjectInt::OPERATION_* $operation
     *
     * @phpstan-return TValue
     */
    private function performOperation(string $operation, $num = null, $mod = null): int
    {
        $left = $this->value();
        $right = $num instanceof self ? $num->value() : $num;
        $mod = $mod instanceof self ? $mod->value() : $mod;

        switch ($operation) {
            case self::OPERATION_ADD:
                $result = $left + $right;

                break;
            case self::OPERATION_SUB:
                $result = $left - $right;

                break;
            case self::OPERATION_MUL:
                $result = $left * $right;

                break;
            case self::OPERATION_DIV:
                $result = $left / $right;

                break;
            case self::OPERATION_POW:
                $result = $left ** $right;

                break;
            case self::OPERATION_MOD:
                $result = $left % $right;

                break;
            case self::OPERATION_POWMOD:
                $result = ($left ** $right) % $mod;

                break;
            case self::OPERATION_SQRT:
                $result = sqrt($left);

                break;
            case self::OPERATION_COMPARE:
                $result = strnatcmp((string) $left, (string) $right);

                break;
            default:
                throw new \Exception('Invalid operation: ' . $operation);
        }

        /* @phpstan-ignore-next-line | is ok here */
        return (int) $result;
    }

    public function value(): int
    {
        return parent::value();
    }

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function sub($num): self
    {
        return self::create($this->performOperation(self::OPERATION_SUB, $num));
    }

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function mul($num): self
    {
        return self::create($this->performOperation(self::OPERATION_MUL, $num));
    }

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function div($num): self
    {
        return self::create($this->performOperation(self::OPERATION_DIV, $num));
    }

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function pow($num): self
    {
        return self::create($this->performOperation(self::OPERATION_POW, $num));
    }

    /**
     * @param int|static<TValue> $num
     * @param int|static<TValue> $mod
     *
     * @return static<TValue>
     */
    public function powmod($num, $mod): self
    {
        return self::create($this->performOperation(self::OPERATION_POWMOD, $num, $mod));
    }

    /**
     * @return static<TValue>
     */
    public function sqrt(): self
    {
        return self::create($this->performOperation(self::OPERATION_SQRT, null));
    }

    /**
     * @param int|static<TValue> $num
     *
     * @return static<TValue>
     */
    public function mod($num): self
    {
        return self::create($this->performOperation(self::OPERATION_MOD, $num));
    }

    /**
     * @param int|static<TValue> $num
     */
    public function isEquals($num): bool
    {
        return $this->compare($num) === 0;
    }

    /**
     * @param int|static<TValue>|null $num
     */
    public function compare($num): int
    {
        $result = $this->performOperation(self::OPERATION_COMPARE, $num);

        return (int) $result;
    }

    /**
     * @param int|static<TValue> $num
     */
    public function isGreaterThan($num): bool
    {
        return $this->compare($num) === 1;
    }

    /**
     * @param int|static<TValue> $num
     */
    public function isLessThan($num): bool
    {
        return $this->compare($num) === -1;
    }

    /**
     * @param int|static<TValue> $num
     */
    public function isGreaterOrEquals($num): bool
    {
        return $this->compare($num) >= 0;
    }

    /**
     * @param int|static<TValue> $num
     */
    public function isLessOrEquals($num): bool
    {
        return $this->compare($num) <= 0;
    }

    public function toFloat(): float
    {
        return (float) $this->value();
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!\is_int($value)) {
            return false;
        }

        return true;
    }
}
