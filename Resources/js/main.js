document.addEventListener( 'DOMContentLoaded', function() {
    console.log( 'zfoo' );
    const nsBulkEditorPopup = defineComponent({
        props: [ 'config', 'selected', 'popup', 'state' ],
        template: `
        <div class="ns-box shadow-lg w-3/5-screen">
            <div class="ns-box-title border-b border-box-edge flex items-center p-2 justify-between">
                <h3>{{ __m( 'Bulk Editing: {items} selected', 'BulkEditor' ).replace( '{items}', entries.length ) }}</h3>
                <div>
                    <ns-close-button @click="closePopup()"/>
                </div>
            </div>
            <div class="ns-box-body flex h-[80vh]">
                <div class="aside hidden md:w-2/5 lg:w-2/5 border-r border-box-edge md:flex flex-col flex-auto overflow-hidden">
                    <div class="flex-auto overflow-y-auto p-2">
                        <div v-for="item of entries" class="mb-2 rounded-full p-1 flex items-center justify-between bg-box-elevation-background">
                            <div>
                                <span class="pl-2 md:hidden lg:hidden xl:hidden">{{ truncateText( item[ config.mapping.label ], 15 ) }}</span>
                                <span class="pl-2 hidden md:inline">{{ truncateText( item[ config.mapping.label ], 20 ) }}</span>
                                <span class="pl-2 hidden lg:inline">{{ truncateText( item[ config.mapping.label ], 35 ) }}</span>
                                <span class="pl-2 hidden xl:inline">{{ truncateText( item[ config.mapping.label ], 50 ) }}</span>
                            </div>
                            <ns-close-button @click="removeItem( item )"/>
                        </div>
                    </div>
                    <div class="border-t border-box-edge p-2">
                        <button @click="clearAllSelection()" class="rounded-full w-full p-2 text-white bg-error-secondary">{{ __m( 'Clear All' ) }}</button>
                    </div>
                </div>
                <div class="flex flex-col h-full flex-auto overflow-hidden">
                    <div class="overflow-y-auto flex-auto">
                        <div class="p-2">
                            <ns-notice>
                                <template v-slot:title>{{ __m( 'Notice', 'BulkEditor' ) }}</template>
                                <template v-slot:description>{{ __m( "The fields that aren't set are ignored.", 'BulkEditor' ) }}</template>
                            </ns-notice>
                        </div>
                        <div style="align-content:flex-start" class="md:w-full -mx-1 flex-auto flex flex-wrap p-2">
                            <div class="w-full md:w-1/2 px-1 mb-2" v-for="field of fields">
                                <ns-field :field="field" :key="field.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-box-edge p-2 flex justify-end">
                        <ns-button @click="bulkUpdate()" type="info">{{ __m( 'Update', 'NsRawMaterial' ) }}</ns-button>
                    </div>
                </div>
            </div>
        </div>
        `,
        data() {
            return {
                entries: [],
                isUpdating: false,
                validation: new FormValidation, // available on the dashboard scope.
            }
        },
        methods: {
            truncateText( text, limit ) {
                return text.length > limit ? text.substr( 0, limit ) + '...' : text;
            },
            removeItem( item ) {
                this.entries = this.entries.filter( i => i !== item );

                // We'll update the state of the ns-crud component
                // by emitting the new state for the "selectedEntries" key.
                this.state.next({
                    selectedEntries: this.entries
                });

                if ( this.entries.length === 0 ) {
                    this.popup.close();
                }
            },
            closePopup() {
                this.popup.close();
            },
            clearAllSelection() {
                this.entries = [];
                this.state.next({
                    selectedEntries: this.entries
                });
                this.popup.close();
            },
            bulkUpdate() {
                const areValid = this.validation.validateFields( this.fields );

                if ( ! areValid ) {
                    return nsNotice.error(
                        __m( 'Validation Error', 'BulkEditor' ),
                        __m( 'Please fill all the required fields', 'BulkEditor' )
                    );
                }

                /**
                 * Before bulk updating, we need to check if any field has at least a value.
                 * If it's not the case, we shouldn't proceed as no changes will be made.
                 */
                const hasChanges = Object.values( this.fields ).some( field => field.value !== '' );

                if ( ! hasChanges ) {
                    return nsNotice.error(
                        __m( 'No Changes', 'BulkEditor' ),
                        __m( 'Please make some changes before updating', 'BulkEditor' )
                    );
                }

                Popup.show( nsConfirmPopup, {
                    title: __m( 'Bulk Update Confirmation', 'BulkEditor' ),
                    message: __m( 'Are you sure you want to update {entries} selected entries?', 'BulkEditor' ).replace( '{entries}', this.entries.length ),
                    onAction: ( action ) => {
                        if ( action ) {
                            this.isUpdating = true;
                            nsHttpClient.post( '/api/bulk-update', {
                                class: this.config.class,
                                fields: this.validation.extractFields( this.fields ),
                                selected: this.entries.map( entry => entry[ this.config.mapping.value ] )
                            }).subscribe({
                                next: response => {
                                    nsNotice.success( 
                                        __m( 'Opertion Successful', 'BulkEditor' ),
                                        __m( 'The bulk update operation was successful', 'BulkEditor' )
                                    );
                                },
                                error: error => {
                                    nsNotice.error( 
                                        __m( 'Opertion Failed', 'BulkEditor' ),
                                        __m( 'The bulk update operation failed', 'BulkEditor' )
                                    );
                                }
                            })
                        }
                    }
                });
            }
        },
        mounted() {
            console.log( this.config )
            this.entries = this.selected;
            this.fields = this.validation.createFields( this.config.fields );
        }
    })

    nsExtraComponents.nsBulkEditor  =   defineComponent({
        name: 'ns-bulk-editor',
        props: [ 'selectedSubject', 'config' ],
        template: `
        <div>
            <button v-if="selected.length > 0" @click="launchEditor()" class="flex justify-center items-center rounded-full text-sm h-10 px-3 ns-crud-button border outline-none">
            <i class="las la-edit mr-1"/>
            {{ __m( 'Quick Edit {entries} selected', 'NsRawMaterial' ).replace( '{entries}', selected.length ) }}
            </button>
        </div>
        `,
        mounted() {
            this.selectedSubject.subscribe({
                next: selected => {
                    this.selected = selected;
                }
            });

            // The state is a BehaviorSubject that is passed to the popup. As we're using Popup,
            // We don't have much way of listening to event on that popup. By passing the BehaviorSubject
            // we can listen to changes and emit it to the ns.crud component.
            this.state.subscribe({
                next: state => {
                    this.$emit( 'update', state );
                }
            });

            console.log( this.config );
        },
        data() {
            return {
                selected: [],
                state: new RxJS.BehaviorSubject({}),
            }
        },
        methods: {
            __m,
            launchEditor: function() {
                const popup = Popup.show( nsBulkEditorPopup, {
                    selected: this.selected,
                    config: this.config.bulkEditConfig,
                    state: this.state,
                });
            }
        }
    })
});