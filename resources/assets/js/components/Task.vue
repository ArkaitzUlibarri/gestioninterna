<template id="task-template">
    
    <div class="task-panel">

        <div class="panel-right-corner" v-if="! task.admin_validation">
            <div class="validated-pm-text" v-if="task.pm_validation">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </div>
            <div class="task-delete" v-if="! task.pm_validation" v-on:click="deleteTask">            
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </div>
        </div>

        <div v-on:click="editTask">
            <h5 v-if="task.activity == 'project'">
                <span><b>{{ time }} {{ task.project.toUpperCase() + ' | ' + task.group.toUpperCase() }}</b> | </span> {{ task.comments.substring(0, 75) }}...
            </h5>             
            
            <h5 v-if="task.activity == 'absence'">
                <span><b>{{ time }} {{ 'ABSENCE | ' + task.absence.toUpperCase() }}</b> | </span> {{ task.comments.substring(0, 75) }}...
            </h5>

            <h5 v-if="task.activity == 'training'">
                <span><b>{{ time }} {{ 'TRAINING | ' + task.training_type.toUpperCase() }}</b> | </span> {{ task.comments.substring(0, 75) }}...
            </h5> 
        </div>
    </div>

</template>

<script>
    export default {
        template:'#task-template',

        props: ['task', 'index'],

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

    .label-position {
        position: relative;
        right:3em;
        top:3.5em;
    }

    .panel-right-corner {
        position: absolute;
        right: 2em;
        top:1em;
    }

    .task-action-icon {
        cursor: pointer;
        display: block;
        margin: auto ;
    }

    .task-panel {
        position:relative;
        border-bottom: 1px solid #ccc;
        padding:.4em
    }

    .validated-task {
        border-style: double;
        border-color: #21d421;
    }

    .validated-pm-text {
        //color: #98FB98;
        color: #21d421;
    }

    .validated-admin-text {
        color: #21d421;
    }

</style>