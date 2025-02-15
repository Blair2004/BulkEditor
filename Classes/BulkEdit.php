<?php
namespace Modules\BulkEditor\Classes;

class BulkEdit
{
    public static function configuration( array $fields, array $mapping )
    {
        return compact( 'fields', 'mapping' );
    }

    public static function mapping( string $value, string $label )
    {
        return compact( 'value', 'label' );
    }
}