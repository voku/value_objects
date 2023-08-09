<?php

declare(strict_types=1);

namespace voku\value_objects;

use function is_int;

/**
 * @extends AbstractValueObject<int>
 *
 * @immutable
 */
final class ValueObjectInt extends AbstractValueObject
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
     * @param int|self $num
     */
    public function add($num): static
    {
        return self::create($this->performOperation(self::OPERATION_ADD, $num));
    }

    /**
     * {@inheritdoc}
     *
     * @param int $value
     */
    public static function create($value): static
    {
        /* @phpstan-ignore-next-line | allow "numeric" here, if we can convert it into "int" */
        if (!is_int($value) && (string)(int)$value === (string)$value) {
            $value = (int)$value;
        }

        return parent::create($value);
    }

    /**
     * @param null|int|self                       $num
     * @param null|int|self                       $mod
     *
     * @phpstan-param ValueObjectInt::OPERATION_* $operation
     */
    private function performOperation(string $operation, $num = null, $mod = null): int
    {
        $left = $this->getValue();
        $right = $num instanceof self ? $num->getValue() : $num;
        $mod = $mod instanceof self ? $mod->getValue() : $mod;

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
                $result = strnatcmp((string)$left, (string)$right);

                break;
            default:
                /* @phpstan-ignore-next-line | we do not want to catch this exception, so it's ok to use Exception here */
                throw new Exception('Invalid operation: ' . $operation);
        }

        return (int)$result;
    }

    public function getValue(): int
    {
        return (int)(string)$this;
    }

    /**
     * @param int|self $num
     */
    public function sub($num): static
    {
        return self::create($this->performOperation(self::OPERATION_SUB, $num));
    }

    /**
     * @param int|self $num
     */
    public function mul($num): static
    {
        return self::create($this->performOperation(self::OPERATION_MUL, $num));
    }

    /**
     * @param int|self $num
     */
    public function div($num): static
    {
        return self::create($this->performOperation(self::OPERATION_DIV, $num));
    }

    /**
     * @param int|self $num
     */
    public function pow($num): static
    {
        return self::create($this->performOperation(self::OPERATION_POW, $num));
    }

    /**
     * @param int|self $num
     * @param int|self $mod
     */
    public function powmod($num, $mod): static
    {
        return self::create($this->performOperation(self::OPERATION_POWMOD, $num, $mod));
    }

    public function sqrt(): static
    {
        return self::create($this->performOperation(self::OPERATION_SQRT, null));
    }

    /**
     * @param int|self $num
     */
    public function mod($num): static
    {
        return self::create($this->performOperation(self::OPERATION_MOD, $num));
    }

    /**
     * @param int|self $num
     */
    public function isEquals($num): bool
    {
        return $this->compare($num) == 0;
    }

    /**
     * @param null|int|self $num
     */
    public function compare($num): int
    {
        $result = $this->performOperation(self::OPERATION_COMPARE, $num);

        return (int)$result;
    }

    /**
     * @param int|self $num
     */
    public function isGreaterThan($num): bool
    {
        return $this->compare($num) == 1;
    }

    /**
     * @param int|self $num
     */
    public function isLessThan($num): bool
    {
        return $this->compare($num) == -1;
    }

    /**
     * @param int|self $num
     */
    public function isGreaterOrEquals($num): bool
    {
        return $this->compare($num) >= 0;
    }

    /**
     * @param int|self $num
     */
    public function isLessOrEquals($num): bool
    {
        return $this->compare($num) <= 0;
    }

    public function toFloat(): float
    {
        return (float)$this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!is_int($value)) {
            return false;
        }

        return parent::validate($value);
    }

}
