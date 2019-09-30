<template>

    <div class="columns">
      <div class="column is-2">
            <b-field label="Категории"> 
            </b-field>
                <div class="field" v-for="(name, tag) in tags" 
                v-bind:key="tag">
                    <button class="button is-fullwidth" 
                    v-bind:class="{ 'is-primary': tagSelect==tag }"
                    @click="tagSelect=tag">
                        <span>{{name}}</span>
                    </button>
                 </div>
      </div>
      <div class="column">
        
        <div class="container">

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
                            <b-button v-if="task.tag=='meets'" class="is-pulled-right is-small"
                                type="is-primary"
                                icon-right="eye-outline" 
                                @click="viewMapMeet(task)"/>
                    </div>
                </div>
        </div>
        
      </div>
      
        <b-modal :active.sync="isComponentModalActive" has-modal-card>
            <modal-form v-model="formProps"></modal-form>
        </b-modal>
  </div>
</template>
<script>
    let ModalForm = {
        data: function(){
            return {
                value: undefined,
                coords: [54.79402948133831, 56.05672011904906],
                reRenderCount: 0,
            }
        },
        props: [
            //'task',
            //'coords',
        ],
        methods: {
            onClick(e)
            {
                if (this.task === undefined) {
                    this.coords = e.get('coords');
                    this.$parent.$parent.formProps.coords = this.coords
                    
                    this.forceReRender()
                }
            },
            forceReRender() {
                this.reRenderCount += 1;  
            },
            onClose()
            {
                this.$parent.$parent.formProps.task = undefined;
                this.coords = [54.79402948133831, 56.05672011904906];
                //console.log(this.props.coords)
                this.$parent.close()
            },
            onSend()
            {
                var self = this
                var param = {}
                param.text = this.value

                if (self.$parent.$parent.dateEnd !== undefined && self.$parent.$parent.dateEnd != null)
                    param.created_at = Date.parse(this.$parent.$parent.dateEnd)/1000
                    
                param.coords = JSON.stringify(this.coords)
                param.tag = self.$parent.$parent.tagSelect

                axios.post('/task/send', param)
                .then(response => {
                    if (response.data[0].id)
                    {
                        self.$parent.$parent.updateTasks(response.data)
                        self.$parent.close()
                    }
                })
                this.task = undefined;
            }
        },
        computed:
        {
            ModalTitle: function() {
                return (this.task === undefined) ? 'Создание задачи' : 'Просмотр задачи'; //this.$parent.$parent.tagSelect
            },
            zzz: function() {
                return this.$parent.$parent.formProps.coords;
            },
            task: function() {
                return this.$parent.$parent.formProps.task;
            },
            newid: function() {
                return Date.now()
            }
        },
        template: `             
            <div class="modal-card" style="max-width: 500px" @close="console.log('ththt')">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ModalTitle}}</p>
                </header>
                <section class="modal-card-body">
                    <b-field v-if="!task"> 
                        <b-input
                            type="name"
                            v-model="value"
                            placeholder="Введите текст"
                            required>
                        </b-input>
                    </b-field>
                    <b-field
                        style="height:350px"> 
                        <yandex-map
                        style="height:100%"
                        :coords="zzz" 
                        @click="onClick">
                             <ymap-marker 
                            :coords="zzz" 
                            :marker-id="newid" 
                            :key="reRenderCount"
                            />
                            
                        </yandex-map>
                    </b-field>
                </section>
                <footer class="modal-card-foot">
                    <button v-if="!task" class="button" type="button" @click="onSend()">Send</button>
                    <button class="button" type="button" @click="onClose()">Close</button>
                </footer>
            </div>
        `
    }
    module.exports = {
        components: {
            ModalForm
        },
        data: function(){
            return {
                tasksOriginal: null,
                tasks: null,
                typeView: null,
                lastWatchTypeView: null,
                dateStart: null,
                dateEnd: null,
                events: [],
                tags: {
                    isAll: 'Все',
                    default: 'Стандарт',
                    meets: 'Встречи',
                },
                tagSelect: 'isAll',
                lastWatchtagSelect: null,
                isComponentModalActive: false,
                formProps: {
                    task: undefined,
                    coords: [54.79402948133831, 56.05672011904906],
                }
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
                    this.updateRecordsFromTags()
                    this.updateRecordsFromDate()
                })
            },
            updateTasks(data, typeView)
            {
                if (typeView === undefined)
                    typeView = 'isAll'
                    
                var self = this
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
                this.updateRecordsFromTags()
                this.updateRecordsFromDate()
            },
            viewMapMeet(task)
            {
                this.isComponentModalActive = true
                this.formProps.task = task
                this.formProps.coords = JSON.parse(task.coords)
                console.log(task)
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
            updateRecordsFromTags()
            {
                var self = this   
                var temp = this.tasks;
                if (this.tagSelect != 'isAll')
                {
                    this.tasks = [];
                    temp.map(function(value, key) {
                        if (self.tagSelect == value.tag)
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
                if(this.tagSelect != "meets") {
                    this.$buefy.dialog.prompt({
                        confirmText: 'Сохранить',
                        inputAttrs: {
                            placeholder: 'Введите вашу задачу',
                            maxlength: 255,
                        },
                        onConfirm: (value) => {
                            var param = {}
                            param.text = value

                            if (this.dateEnd !== undefined && this.dateEnd != null)
                                param.created_at = Date.parse(this.dateEnd)/1000
                                
                            param.tag = this.tagSelect
                            axios.post('/task/send', param)
                            .then(response => {
                                if (response.data[0].id)
                                {
                                    this.updateTasks(response.data)
                                }
                            })
                        },
                    })
                }
                else
                {
                  this.isComponentModalActive = true
                  this.formProps.task = undefined
                }
            },
            
        },
        mounted: function() {
            this.load()
            /*
            axios.get('/task/tags')
            .then(response => {
                this.tags = response.data
            })
            */
        },
        watch: {
            typeView: function() {
                if (this.typeView != this.lastWatchTypeView)
                {           
                    this.lastWatchTypeView = this.typeView
                    this.updateRecordsSync()
                    this.updateRecordsFromType()         
                    this.updateRecordsFromTags()
                    this.updateRecordsFromDate()
                }
            },
            tagSelect: function() {
                if (this.tagSelect != this.lastWatchtagSelect)
                {      
                    this.lastWatchtagSelect = this.tagSelect
                    this.updateRecordsSync()
                    this.updateRecordsFromType()              
                    this.updateRecordsFromTags()
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