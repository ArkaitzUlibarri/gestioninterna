<template id="teleworking-template">

    <div class= col-xs-12>
        <div class="item-panel col-sm-12" v-bind:class="{ 'selected-item':prop }"> 

            <h5 class="action"  v-on:click="edititem">
                <b>From {{item.start_date}} <span v-if="item.end_date">to {{item.end_date}}</span> | </b>
                <label>
                     <span v-if ="item.monday">Monday |</span>
                     <span v-if ="item.tuesday">Tuesday |</span>
                     <span v-if ="item.wednesday">Wednesday |</span>
                     <span v-if ="item.thursday">Thursday |</span>
                     <span v-if ="item.friday">Friday |</span>
                     <span v-if ="item.saturday">Saturday |</span>
                     <span v-if ="item.sunday">Sunday |</span>
                </label>
            </h5> 
            
            <div class="panel-right-corner">
                <div class="action" v-on:click="deleteitem">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>
          
        </div>
    </div>

</template>

<script>
    export default {
        template:'#teleworking-template',

        props: ['item', 'index','prop'],

        methods: {     

            edititem() {
                Event.$emit('Edit', this.index, this.item);
            },

            deleteitem() {
                Event.$emit('Delete', this.index, this.item);
            }
        }
    }
</script>

<style>

    .item-panel {
        position:relative;
        border-bottom: 1px solid #ccc;
        padding: .4em;
        //margin-bottom: .5em;
    }

    .panel-right-corner {
        position: absolute;
        right: 2em;
        top:1em;
    }

    .action {
        cursor: pointer;
        //display: block;
        //margin: auto ;
    }

    .selected-item {
        //background-color: lightblue;
        border-bottom: 1px dashed red;
        border-top: 1px dashed red;
    }

</style>