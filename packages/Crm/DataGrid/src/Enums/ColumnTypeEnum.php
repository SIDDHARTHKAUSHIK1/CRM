<?php

namespace Crm\DataGrid\Enums;

use Crm\DataGrid\ColumnTypes\Aggregate;
use Crm\DataGrid\ColumnTypes\Boolean;
use Crm\DataGrid\ColumnTypes\Date;
use Crm\DataGrid\ColumnTypes\Datetime;
use Crm\DataGrid\ColumnTypes\Decimal;
use Crm\DataGrid\ColumnTypes\Integer;
use Crm\DataGrid\ColumnTypes\Text;
use Crm\DataGrid\Exceptions\InvalidColumnTypeException;

enum ColumnTypeEnum: string
{
    /**
     * String.
     */
    case STRING = 'string';

    /**
     * Integer.
     */
    case INTEGER = 'integer';

    /**
     * Float.
     */
    case FLOAT = 'float';

    /**
     * Boolean.
     */
    case BOOLEAN = 'boolean';

    /**
     * Date.
     */
    case DATE = 'date';

    /**
     * Date time.
     */
    case DATETIME = 'datetime';

    /**
     * Aggregate.
     */
    case AGGREGATE = 'aggregate';

    /**
     * Get the corresponding class name for the column type.
     */
    public static function getClassName(string $type): string
    {
        return match ($type) {
            self::STRING->value => Text::class,
            self::INTEGER->value => Integer::class,
            self::FLOAT->value => Decimal::class,
            self::BOOLEAN->value => Boolean::class,
            self::DATE->value => Date::class,
            self::DATETIME->value => Datetime::class,
            self::AGGREGATE->value => Aggregate::class,
            default => throw new InvalidColumnTypeException("Invalid column type: {$type}"),
        };
    }
}
