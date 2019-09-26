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
                    </div>
                </div>
        </div>
        
      </div>
      
        <b-modal :active.sync="isComponentModalActive" has-modal-card>
            <modal-form></modal-form>
        </b-modal>
  </div>
</template>
<script>
    const ModalForm = {
        data: function(){
            return {
                coords: [54.79402948133831, 56.05672011904906],
                 markerIcon: {
                    layout: 'default#imageWithContent',
                    imageHref: 'https://image.flaticon.com/icons/png/512/33/33447.png',
                    imageSize: [43, 43],
                    imageOffset: [0, 0],
                    content: '123 v12',
                    contentOffset: [0, 15],
                    contentLayout: '<div style="background: red; width: 50px; color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                    }
            }
        },
        props: [
            'task',
        ],
        methods: {
            onClick(e) {
                this.coords = e.get('coords');
            }
        },
        template: `            
            <div class="modal-card" style="max-width: 500px">
                <header class="modal-card-head">
                    <p class="modal-card-title">Редактирование:</p>
                </header>
                <section class="modal-card-body">
                    <b-field> 
                        <b-input
                            type="name"
                            placeholder="Введите текст"
                            required>
                        </b-input>
                    </b-field>
                    <b-field
                        style="height:350px"> 
                        <yandex-map
                        style="height:100%"
                        :coords="coords" 
                         :icon="markerIcon"
                        @click="onClick">
                             <ymap-marker 
                            :coords="coords" 
                            marker-id="123" 
                            hint-content="some hint" 
                            />
                            
                        </yandex-map>
                    </b-field>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="$parent.close()">Close</button>
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