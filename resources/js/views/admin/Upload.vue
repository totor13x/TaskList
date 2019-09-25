<template>
    <section>
        <b-modal :active.sync="isComponentModalActive" has-modal-card>
            <modal-form v-bind="formProps" ></modal-form>
        </b-modal>
        <div class="columns">
            <div class="column is-4">
                <b-field>
                    <b-upload v-model="file"
                        drag-drop
                        >
                        <section class="section">
                            <div class="content has-text-centered">
                                <p>
                                    <b-icon
                                        icon="upload"
                                        size="is-large">
                                    </b-icon>
                                </p>
                                <p>Drop your files here or click to upload</p>
                            </div>
                        </section>
                    </b-upload>
                </b-field>
                <b-field  v-if="file">
                    <b-button @click="sendDropFile" class="is-fullwidth">Загрузить {{ file.name }}</b-button>
                </b-field>
                <b-field>
                    <b-button @click="getUploadedFiles" class="is-fullwidth">Загрузить</b-button>
                </b-field>
            </div>
            <div class="column">
                <div v-if="files">
                    <b-field v-for="file in files" v-bind:key="file.id">
                        <b-button @click="modalFile(file)" class="is-fullwidth">{{file.name}}</b-button>
                    </b-field>
                    <div v-if="files.length==0">
                        <div class="box">
                            Нет загруженных файлов
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
const ModalForm = {
        props: ['file'],
        methods: {
            updateUploadedFile()
            {
                var this_ = this
                let formData = new FormData();
                formData.append('name',this.file.name)
                formData.append('id',this.file.id)
                axios.post('/admin/file/update', formData)
                .then(function (response) {
                    console.log(response)
                    this_.$parent.close()
                })
            },
            deleteUploadedFile()
            {
                var self = this.$parent.$parent
                var this_ = this
                axios.get('/admin/file/delete/'+this.file.id)
                .then(function (response) {

                    self.files.splice(self.files.indexOf(this_.file), 1);
                    //self.delete(self.files, self.file)
                    console.log(response)
                    this_.$parent.close()
                })
            }
        },
        template: `            
                <div class="modal-card" style="max-width: 500px">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Редактирование: {{file.name}}</p>
                    </header>
                    <section class="modal-card-body">
                        <b-field label="Название">
                            <b-input
                                type="name"
                                v-model="file.name"
                                placeholder="Название файла"
                                required>
                            </b-input>
                        </b-field>

                    </section>
                    <footer class="modal-card-foot">
                        <button class="button is-danger"
                        @click="deleteUploadedFile()">Удалить</button>
                        <button class="button is-primary" 
                        @click="updateUploadedFile()">Сохранить</button>
                        <button class="button" type="button" @click="$parent.close()">Close</button>
                    </footer>
                </div>
            </form>
        `
    }
    export default {
        components: {
            ModalForm
        },
        data() {
            return {
                file: undefined,
                DataInfo: "ZZZ",
                files: undefined,
                isComponentModalActive: false,
                formProps: {
                    file: undefined,
                }
            }
        },
        methods: {
            sendDropFile()
            {
                var self = this
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                formData.append('file', this.file);
   
                axios.post('/admin/file/upload', formData, config)
                .then(function (response) {
                    console.log(response)
                    self.getUploadedFiles()
                })
                .catch(function (error) {
                    console.log(error)
                });
            },
            getUploadedFiles()
            {
                axios.get('/admin/file/show')
                .then(response => {
                    this.files = response.data
                    console.log(response)
                })
            },
            modalFile(file)
            {
                this.formProps.file = file
                this.isComponentModalActive = true
            }
        },
        mounted: function() {
            this.getUploadedFiles()
        },
    }
</script>