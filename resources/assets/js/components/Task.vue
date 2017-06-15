<template id="task-template">
    
    <div class="task-panel" v-bind:class="{ 'validated-task': task.admin_validation ,'selected-task':prop }">

        <div class="panel-right-corner" v-if="! task.admin_validation">
            <div class="validated-color" v-if="task.pm_validation">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  
            </div>
            <div class="task-delete action" v-if="! task.pm_validation" v-on:click="deleteTask">            
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </div>
        </div>

        <div class="action" v-on:click="editTask">
            <h5 v-if="task.activity == 'project'">
                <span><b>{{ time }} {{ task.project.toUpperCase() + ' | ' + task.group.toUpperCase() + ' | ' + task.category.toUpperCase() }}</b> | </span> 
                <span v-if="task.comments">{{ task.comments.substring(0, 90) }}<span v-if="task.comments.length >90">...</span></span>
            </h5>             
            
            <h5 v-if="task.activity == 'absence'">
                <span><b>{{ time }} {{ 'ABSENCE | ' + task.absence.toUpperCase() }}</b> | </span> 
                <span v-if="task.comments">{{ task.comments.substring(0, 90) }}<span v-if="task.comments.length >90">...</span></span>
            </h5>

            <h5 v-if="task.activity == 'training'">
                <span><b>{{ time }} {{ 'TRAINING | ' + task.training_type.toUpperCase() }}</b> | </span> 
                <span v-if="task.comments">{{ task.comments.substring(0, 90) }}<span v-if="task.comments.length >90">...</span></span>
            </h5> 
        </div>
    </div>

</template>

<script>
    export default {
        template:'#task-template',

        props: ['task', 'index','prop'],

        computed: {
            time() {
                return (parseInt(this.task.time_slots)*0.25); 
            }
        },

        methods: {
            deleteTask() {
                Event.$emit('DeleteTask', this.index, this.task);
            },
            
            editTask() {
                Event.$emit('EditTask', this.index, this.task);
            }
        }

    }
</script>

<style>

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

    .task-panel {
        position:relative;
        border-bottom: 1px solid #ccc;
        padding:.4em
    }

    .validated-task {
        background-color: #b0f2b8;
    }

    .selected-task {
        //background-color: lightblue;
        border-bottom: 1px dashed red;
        border-top: 1px dashed red;
    }

    .validated-color {
        color: #21d421;
    }

</style>