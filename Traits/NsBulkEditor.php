<?php
namespace Modules\BulkEditor\Traits;

use App\Classes\Hook;

Trait NsBulkEditor {
    protected $bulkbulkEditConfigurationEdit     =   [];

    public function bulkEdit( string $model, array $fields )
    {
        $this->bulkEditConfiguration     =   [
            'model'     =>  $model,
            'fields'    =>  $fields
        ];
    }

    /**
     * We're overriding the getCrudConfig method from the CrudService class
     * @return array
     */
    public function getCrudConfig(): array
    {
        $editor     =   [];

        /**
         * We're injecting a custom Vue component
         * if the bulkEditConfiguration is not empty
         */
        if( !empty( $this->bulkEditConfiguration ) ) {
            $editor[]   =   [ 'nsBulkEditor' ];
        }

        return Hook::filter( get_class( $this ) . '@getCrudConfig', [
            'columns' => Hook::filter(
                get_class( $this ) . '@getColumns',
                $this->getColumns()
            ),
            'queryFilters' => Hook::filter( get_class( $this ) . '@getQueryFilters', $this->getQueryFilters() ),
            'labels' => Hook::filter( get_class( $this ) . '@getLabels', $this->getLabels() ),
            'links' => Hook::filter( get_class( $this ) . '@getFilteredLinks', $this->getFilteredLinks() ?? [] ),
            'bulkActions' => Hook::filter( get_class( $this ) . '@getBulkActions', $this->getBulkActions() ),
            'prependOptions' => Hook::filter( get_class( $this ) . '@getPrependOptions', $this->getPrependOptions() ),
            'showOptions' => Hook::filter( get_class( $this ) . '@getShowOptions', $this->getShowOptions() ),
            'showCheckboxes' => Hook::filter( get_class( $this ) . '@getShowCheckboxes', $this->getShowCheckboxes() ),
            'headerButtons' => Hook::filter( get_class( $this ) . '@getHeaderButtons', array_merge( $editor, $this->getHeaderButtons() ) ),
            'identifier' => $this->getIdentifier(),
        ], $this );
    }
}