<template id="task-template">
    <div style="border: 1px solid red;">
    
        <span class="label label-primary label-position">{{index +1}}</span>

        <div class="task-panel" v-bind:class="{ 'validated-task': task.admin_validation }"> 

            <div class="panel-right-corner" v-if="! task.admin_validation">

                <div class="task-action-icon validated-pm-text" v-if="task.pm_validation">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                </div>

                <div class="task-action-icon task-delete" v-if="! task.pm_validation" v-on:click="deleteTask">            
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>

            <div class="task-action-icon" v-on:click="editTask">
                <span v-if="task.activity == 'absence'">
                <h5><b> {{ time }} {{ 'ABSENCE \\ ' + task.absence.toUpperCase() }}</b><p>{{ task.comments }}</p> </h5>           
                </span>
                
                <span v-if="task.activity == 'project'">
                    <h5>
                        <p><b>{{ time }} {{ task.project.toUpperCase() + ' \\ ' + task.group.toUpperCase()}}</b></p>
                        <p>{{ task.comments }}</p>
                    </h5>             
                </span>
                
                <span v-if="task.activity == 'training'">
                <h5><b> {{ time }} {{ 'TRAINING \\ ' + task.training_type.toUpperCase() }}</b><p>{{ task.comments }}</p></h5> 
                </span>
            </div>
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
        margin-bottom: .3em;
        background-color: #fff;
        border: 1px solid #777777;
        border-radius: 0px;
        -webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 3px 1px rgba(0, 0, 0, .05);
        padding: .3em .7em .3em .7em;
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