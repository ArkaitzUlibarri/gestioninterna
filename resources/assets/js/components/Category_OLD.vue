<template id="category-template">

    <div class= col-xs-12>
        <div class="item-panel col-sm-6"> 

            <h5>
                <b>{{item.category.replace("-","|")}}</b>
            </h5> 
            
            <div class="panel-right-corner">
                <div class="action" v-on:click="deleteCategory">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>
          
        </div>
    </div>

</template>

<script>
    export default {
        template:'#category-template',

        props: ['item', 'index'],

        methods: {     

            deleteCategory() {
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
        margin-bottom: .5em;
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

    .item-enabled{
        background-color: #b0f2b8;
    }

</style>