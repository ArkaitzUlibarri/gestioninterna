<template id="group-template">

    <div class= col-xs-12>
        <div class="group-panel col-sm-4" v-bind:class="{ 'group-enabled': group.enabled ,'selected-item':prop }"> 

            <h5 class="action"  v-on:click="editGroup">
                <b>{{group.name.toUpperCase().substring(0,30)}}<span v-if="group.name.length >30">...</span></b>
            </h5> 
            
            <div class="panel-right-corner">
                <div class="action" v-on:click="deleteGroup">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>
          
        </div>
    </div>

</template>

<script>
    export default {
        template:'#group-template',

        props: ['group', 'index','prop'],

        methods: {     

            editGroup() {
                Event.$emit('Edit', this.index, this.group);
            },

            deleteGroup() {
                Event.$emit('Delete', this.index, this.group);
            }
        }
    }
</script>

<style>

    .group-panel {
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

    .group-enabled{
        background-color: #b0f2b8;
    }

</style>