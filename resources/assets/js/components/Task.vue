<template id="task-template">
    <div class="task-panel" v-bind:class="{ 'validated-task': task.pm_validation }"> 
        <div class="panel-right-corner">
            <div class="task-action-icon validated-pm-text" v-if="task.pm_validation">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            </div>
            <div class="task-action-icon validated-admin-text" v-if="task.admin_validation">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            </div>
            <div class="task-action-icon task-panel-close" v-if="! task.pm_validation" v-on:click="deleteTask">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </div>
            <div class="task-action-icon task-panel-close" v-if="! task.pm_validation"  v-on:click="editTask">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </div>
        </div>

        <span v-if="task.activity == 'absence'">
            <h4> <b> {{  time  }} </b> {{ task.absence }}  </h4> 
            <p>{{ task.comments }}</p>
        </span>

        <span v-if="task.activity == 'project'">
            <h4> <b> {{  time  }} </b> {{ task.project + ' \\ ' + task.group }}  </h4>      
            <p>{{ task.comments }}</p>
        </span>

        <span v-if="task.activity == 'training'">
            <h4> <b> {{ time }} </b>  {{ 'TRAINING \\ ' + task.training_type }}  </h4> 
            <p>{{ task.comments }}</p>
        </span>
      
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

    .task-panel {
        position:relative;
        margin-bottom: .5em;
        background-color: #fff;
        border: 1px solid #777777;
        border-radius: 0px;
        -webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        padding: .7em;
    }

    .panel-right-corner {
        position: absolute;
        top: .4em;
        right: 1em;
    }

    .task-action-icon {
        font-weight: bold;
        cursor: pointer;
        display: block;
        margin: 0.4em;
    }

    .validated-task {
        border-style: double;
        border-color: red;
    }
    .validated-pm-text {
        color: pink;
    }
    .validated-admin-text {
        color: red;
    }
</style>