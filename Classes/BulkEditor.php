<?php
namespace Modules\BulkEditor\Classes;

use Attribute;

#[Attribute( Attribute::TARGET_CLASS )]
class BulkEditor
{
    public function __construct( public array $config )
    {
        // ...
    }

    public static function configuration( array $fields, array $mapping )
    {
        return compact( 'fields', 'mapping' );
    }

    public static function mapping( string $value, string $label )
    {
        return compact( 'value', 'label' );
    }
}