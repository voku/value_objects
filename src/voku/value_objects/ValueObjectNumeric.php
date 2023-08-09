<?php

declare(strict_types=1);

namespace voku\value_objects;

use function ob_get_flush;
use function ob_start;
use const PHP_ROUND_HALF_UP;
use Throwable;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\exceptions\ValueObjectNumericException;
use voku\value_objects\utils\MathUtils;

/**
 * @extends AbstractValueObject<numeric-string>
 *
 * @immutable
 */
final class ValueObjectNumeric extends AbstractValueObject
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
     * @var array<string, string>
     */
    private const OPERATIONS_MAP = [
        self::OPERATION_ADD     => 'bcadd',
        self::OPERATION_SUB     => 'bcsub',
        self::OPERATION_MUL     => 'bcmul',
        self::OPERATION_DIV     => 'bcdiv',
        self::OPERATION_POW     => 'bcpow',
        self::OPERATION_MOD     => 'bcmod',
        self::OPERATION_POWMOD  => 'bcpowmod',
        self::OPERATION_SQRT    => 'bcsqrt',
        self::OPERATION_COMPARE => 'bccomp',
    ];

    /**
     * @phpstan-var 0|positive-int
     */
    private int $scale;

    /**
     * @param string $value
     * @param int<0,max> $scale
     *
     * @return static
     */
    public static function i18n_number_parse(
        $value,
        string $activeLocale = 'en-US',
        int $scale = 10
    ): self {
        $valueParsed = MathUtils::i18n_number_parse($value, $activeLocale, null);
        if ($valueParsed === null) {
            throw new InvalidValueObjectException('could not parse value: ' . $value . ' | locale: ' . $activeLocale);
        }

        return self::create($valueParsed, $scale);
    }

    /**
     * @param numeric-string $value
     * @param int<0,max> $scale
     *
     * {@inheritdoc}
     */
    public static function create($value, int $scale = 10): self
    {
        /* @phpstan-ignore-next-line | we do not trust the code here, so we check the type again */
        if ($scale < 0) {
            throw new InvalidValueObjectException('$scale can\'t be less than 0');
        }

        $return = parent::create(self::filterNumeric($value));

        $return->scale = $scale;

        return $return;
    }

    /**
     * @param numeric|null $num
     *
     * @return numeric-string
     */
    public static function filterNumeric($num): string
    {
        if ($num === null) {
            $numBackup = 0;
        } else {
            $numBackup = $num;
        }

        // fallback
        if (!$num) {
            $num = 0;
        }

        try {
            $return = MathUtils::numericToNumericString($num);
        } catch (Throwable $e) {
            return (string) $numBackup;
        }

        if (!MathUtils::is_numeric($return)) {
            return (string) $numBackup;
        }

        return $return;
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function add($num, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_ADD, $num, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self|null                       $num
     * @param numeric|self|null                       $mod
     *
     * @return numeric-string
     *
     * @phpstan-param ValueObjectNumeric::OPERATION_* $operation
     */
    private function performOperation(
        string $operation,
               $num = null,
               $mod = null,
        ?int $scale = null
    ) {
        $left = $this->getValue();
        $right = $num instanceof self ? $num->getValue() : self::filterNumeric($num);
        $mod = $mod instanceof self ? $mod->getValue() : self::filterNumeric($mod);
        $func = self::OPERATIONS_MAP[$operation];

        // ------------------------------------------------------

        $scaleCheck = 99;
        switch ($operation) {
            case self::OPERATION_POWMOD:
                $args = [$left, $right, $mod, $scaleCheck];

                break;
            case self::OPERATION_SQRT:
                $args = [$left, $scaleCheck];

                break;
            case self::OPERATION_MOD:
                $args = [$left, $right];

                break;
            default:
                $args = [$left, $right, $scaleCheck];

                break;
        }
        ob_start();
        $resultScaleCheck = \call_user_func_array($func, $args);
        $error = ob_get_flush();
        if ($error) {
            throw new ValueObjectNumericException($error);
        }

        // ------------------------------------------------------

        $scale = (int) ($scale ?? $this->scale);
        switch ($operation) {
            case self::OPERATION_POWMOD:
                $args = [$left, $right, $mod, $scale];

                break;
            case self::OPERATION_SQRT:
                $args = [$left, $scale];

                break;
            case self::OPERATION_MOD:
                $args = [$left, $right];

                break;
            default:
                $args = [$left, $right, $scale];

                break;
        }
        ob_start();
        $result = \call_user_func_array($func, $args);
        $error = ob_get_flush();
        if ($error) {
            throw new ValueObjectNumericException($error);
        }

        // ------------------------------------------------------

        if (
            $scale !== 0 // we allow "scale" zero, so that we can cut off everything
            &&
            (float) $result === 0.0
            &&
            (float) $resultScaleCheck > 0
        ) {
            trigger_error('scale error: ' . $func . '|' . print_r($args, true), \E_USER_WARNING);
        }

        return $result;
    }

    /**
     * @return numeric-string
     */
    public function getValue(): string
    {
        /* @phpstan-ignore-next-line | we know that this is numeric-string by definition */
        return (string) $this;
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function sub($num, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_SUB, $num, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function mul($num, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_MUL, $num, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function div($num, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_DIV, $num, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function pow($num, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_POW, $num, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self $num
     * @param numeric|self $mod
     *
     * @return static
     */
    public function powmod($num, $mod, ?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_POWMOD, $num, $mod, $scale), $this->scale);
    }

    /**
     * @return static
     */
    public function sqrt(?int $scale = null): self
    {
        return self::create($this->performOperation(self::OPERATION_SQRT, null, null, $scale), $this->scale);
    }

    /**
     * @param numeric|self $num
     *
     * @return static
     */
    public function mod($num): self
    {
        return self::create($this->performOperation(self::OPERATION_MOD, $num, null), $this->scale);
    }

    /**
     * @phpstan-param 1|2|3|4 $mode
     *                              <p>PHP_ROUND_*</p>
     *
     * @return static
     */
    public function round(int $precision = 3, int $mode = PHP_ROUND_HALF_UP): self
    {
        // @codingStandardsIgnoreStart
        return self::create((string) round((float) $this->value(), $precision, $mode), $this->scale);
        // @codingStandardsIgnoreEnd
    }

    /**
     * @param numeric|self $num
     */
    public function isEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) === 0;
    }

    /**
     * @param numeric|self|null $num
     *
     * @phpstan-return 0|1|-1
     */
    protected function compare($num, ?int $scale = null): int
    {
        $result = (int) $this->performOperation(self::OPERATION_COMPARE, $num, null, $scale);

        \assert($result === 0 || $result === 1 || $result === -1);

        return $result;
    }

    /**
     * @param numeric|self $num
     */
    public function isGreaterThan($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) === 1;
    }

    /**
     * @param numeric|self $num
     */
    public function isLessThan($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) === -1;
    }

    /**
     * @param numeric|self $num
     */
    public function isGreaterOrEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) >= 0;
    }

    /**
     * @param numeric|self $num
     */
    public function isLessOrEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) <= 0;
    }

    public function toFloat(): float
    {
        return (float) $this->getValue();
    }

    /**
     * @return false|string
     */
    public function i18n_number_format(
        string $activeLocale = 'en-US',
        int $precision = 2,
        bool $showMore = false,
        bool $sendWarningOnError = true
    ) {
        return MathUtils::i18n_number_format(
            (string) $this->value(),
            $activeLocale,
            $precision,
            $showMore,
            $sendWarningOnError
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!MathUtils::is_numeric((string) $value)) {
            return false;
        }

        return parent::validate($value);
    }
}
