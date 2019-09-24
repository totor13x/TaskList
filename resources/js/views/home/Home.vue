<template>
    <div>
        <div class="field container">
            <nav class="level">
            <!-- Left side -->
            <div class="level-left">
                <div class="level-item">
                    <p class="subtitle is-5">
                        Выведено задач: <strong>{{countTasks}}</strong>
                    </p>
                </div>
            </div>

            <!-- Right side -->
            <div class="level-right">
                <p class="level-item">
                        <b-datepicker 
                            v-model="dateStart"
                            :indicators="'dots'"
                            :events="events"
                            @input="changeDate"
                            placeholder="Введите дату..."
                            icon="calendar-today"
                            editable>
                            <button class="button is-primary"
                                @click="dateStart = new Date()">
                                <b-icon icon="calendar-today"></b-icon>
                                <span>Сегодня</span>
                            </button>

                            <button class="button is-warning"
                                @click="dateStart = null; changeDate(null)">
                                <b-icon icon="close"></b-icon>
                                <span>Очистить</span>
                            </button>
                        </b-datepicker>
                </p>
                <p class="level-item">
                    <b-dropdown v-model="typeTasksView" aria-role="list">
                        <button class="button is-primary" type="button" slot="trigger">
                            <template v-if="typeView=='isAll'">
                                <span>Все</span>
                            </template>
                            <template v-else-if="typeView=='isChecked'">
                                <span>Только выполненные</span>
                            </template>
                            <template v-else-if="typeView=='isNonChecked'">
                                <span>Только не выполненные</span>
                            </template>
                            <b-icon icon="menu-down"></b-icon>
                        </button>

                        <b-dropdown-item value="isAll" aria-role="listitem">
                            <div class="media">
                                <div class="media-content">
                                    <h3>Все</h3>
                                </div>
                            </div>
                        </b-dropdown-item>

                        <b-dropdown-item value="isChecked" aria-role="listitem">
                            <div class="media">
                                <div class="media-content">
                                    <h3>Только выполненные</h3>
                                </div>
                            </div>
                        </b-dropdown-item>

                        <b-dropdown-item value="isNonChecked" aria-role="listitem">
                            <div class="media">
                                <div class="media-content">
                                    <h3>Только не выполненные</h3>
                                </div>
                            </div>
                        </b-dropdown-item>
                    </b-dropdown>
                </p>
                <p class="level-item"><a class="button is-success" @click="addNewTask">New</a></p>
            </div>
            </nav>
        </div>
        <div v-if='tasks'>
            <div class='field box'
                v-for="task in tasks" v-bind:key="task.id" >
                <b-checkbox 
                    v-bind:true-value=1
                    v-bind:false-value=0
                    v-bind:value="task.status"
                    v-model="task.status"
                    @input="updateTask(task)"
                    
                    ></b-checkbox>
                    {{ task.text }}
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data: function(){
            return {
                tasksOriginal: null,
                tasks: null,
                typeView: null,
                lastWatchTypeView: null,
                dateStart: null,
                dateEnd: null,
                events: [],
            }
        },
        methods: {
            load: function()
            {
                if (this.$root.token !== undefined && this.$root.token != null)
                {
                    axios.get('/task/show')
                    .then(response => {
                        this.updateTasks(response.data)
                    })
                }
                else
                {
                    this.updateTask([])
                }
            },
            updateTask(task)
            {
                axios.post('/task/update', {id: task.id,status:task.status,text:task.text})
                .then(response => {
                    this.updateRecordsSync()
                    this.updateRecordsFromType()
                    this.updateRecordsFromDate()
                })
            },
            updateTasks(data, typeView)
            {
                if (typeView === undefined)
                    typeView = 'isAll'
                    
                var self = this
                console.log(data)
                if (data.token !== undefined)
                {
                    localStorage.setItem('token', data.token)
                    window.axios.defaults.headers.common['Authorization'] = data.token;
                    self.$root.token = data.token
                    this.load()
                    return 
                }
                self.tasksOriginal = data
                self.typeView = typeView
                this.updateRecordsSync()
                self.events = []
                var MaxDots = []
                if (this.tasksOriginal.map !== undefined) 
                {
                    this.tasksOriginal.map(function(value, key) {
                        var formattedDate = new Date(value.created_at)
                        var formattedDateForMaxDots = formattedDate.toISOString().split('T')[0]
                        if (MaxDots[formattedDateForMaxDots] === undefined)
                            MaxDots[formattedDateForMaxDots] = 0

                        if (MaxDots[formattedDateForMaxDots] < 3)
                        {
                            self.events.push(formattedDate);
                            MaxDots[formattedDateForMaxDots]++;
                        }
                    })
                }
            },
            changeDate(date)
            {
                if (date != null && date !== undefined)
                {
                    this.dateStart = new Date(date)
                    this.dateEnd = new Date(date.setHours(23,59,59,999));
                }
                this.updateRecordsSync()
                this.updateRecordsFromType()
                this.updateRecordsFromDate()
            },
            updateRecordsSync()
            {
                if (this.tasks != this.tasksOriginal)
                    this.tasks = this.tasksOriginal;

            },
            updateRecordsFromDate()
            {
                var self = this
                if (this.dateStart != null && this.dateStart !== undefined)
                    {
                        var temp = this.tasks;
                        this.tasks = [];
                        temp.map(function(value, key) {
                            var formattedTime = new Date(value.created_at)
                            if (formattedTime >= self.dateStart && formattedTime <= self.dateEnd)
                                self.tasks.push(value);
                        })
                    }
            },
            updateRecordsFromType()
            {
                var self = this
                if (this.typeView == 'isAll') 
                {
                    this.tasks = [];
                    this.tasks = this.tasksOriginal;
                }
                else
                    if (this.typeView == 'isChecked')
                    {
                        this.tasks = [];
                        this.tasksOriginal.map(function(value, key) {
                            if (value.status == 1)
                                self.tasks.push(value);
                        });
                    }
                    else
                    {
                        this.tasks = [];

                        this.tasksOriginal.map(function(value, key) {
                            if (value.status == 0)
                                self.tasks.push(value);
                        });
                    }

            },
            addNewTask() {
                this.$buefy.dialog.prompt({
                    confirmText: 'Сохранить',
                    inputAttrs: {
                        placeholder: 'Введите вашу задачу',
                        maxlength: 255,
                    },
                    onConfirm: (value) => {
                        axios.post('/task/send', {'text': value})
                        .then(response => {
                            if (response.data[0].id)
                            {
                                this.updateTasks(response.data)
                            }
                        })
                    },
                })
            },
            
        },
        mounted: function() {
            this.load()
        },
        watch: {
            typeView: function() {
                if (this.typeView != this.lastWatchTypeView)
                {
                    this.updateRecordsSync()
                    this.updateRecordsFromType()                    
                    this.lastWatchTypeView = this.typeView
                    
                    this.updateRecordsFromDate()
                }
            }
        },
        computed: {
            countTasks: function() {
                return this.tasks && this.tasks.length || 0
            },
            typeTasksView: {
                get () {
                    return this.typeView
                },
                set (value) {
                    this.typeView = value
                }
            },
        }
        
    }
</script>