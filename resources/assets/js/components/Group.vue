<template id="group-template">

    <div class="row">
        <div class="group-panel col-xs-12 col-sm-4" v-bind:class="{ 'group-enabled': group.enabled }"> 

            <h5 class="group-action-icon"  v-on:click="editGroup">
                {{group.name.toUpperCase().substring(0,30)}}<span v-if="group.name.length >30">...</span>
            </h5> 
            
            <div class="panel-right-corner">
                <div class="group-action-icon" v-on:click="deleteGroup">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>
          
        </div>
    </div>

</template>

<script>
    export default {
        template:'#group-template',

        props: ['group', 'index'],

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
        margin-bottom: .5em;
        background-color: #fff;
        border: 1px solid #777777;
        border-radius: 25px;
        -webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        padding: .7em;
    }

    .panel-right-corner {
        position: absolute;
        top: 0.5em;
        //top:1.2em;
        right: 2em;
    }

    .group-action-icon {
        font-weight: bold;
        cursor: pointer;
        display: block;
        margin: auto ;
    }

    .group-enabled{
        background-color: #b0f2b8;
    }

    .group-disabled{
        background-color: #f491a5;
    }

</style>