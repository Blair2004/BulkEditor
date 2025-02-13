document.addEventListener("DOMContentLoaded",function(){nsExtraComponents.nsBulkEditor=defineComponent({name:"ns-bulk-editor",props:["selectedSubject"],template:`
        <div>
            <button v-if="selected.length > 0" @click="loadSelected()" class="flex justify-center items-center rounded-full text-sm h-10 px-3 ns-crud-button border outline-none">
            {{ __m( 'Edit {entries} selected', 'NsRawMaterial' ).replace( '{entries}', selected.length ) }}
            </button>
        </div>
        `,mounted(){this.selectedSubject.subscribe({next:e=>{this.selected=e}})},data(){return{selected:[]}},methods:{__m,loadSelected:function(){console.log(this.selected)}}})});
