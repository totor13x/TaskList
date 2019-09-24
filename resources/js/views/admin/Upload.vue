<template>
    <section>
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
            </div>
            <div class="column">
                <div class="columns">
                    <div class="column">
                    </div>
                    <div class="column">
                        <div class="box">
                            <div v-if="file">
                                12313
                            </div>
                            <div v-else>
                            {{DataInfo}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        
        data() {
            return {
                file: undefined,
                DataInfo: "ZZZ",
            }
        },
        methods: {
            sendDropFile()
            {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                formData.append('file', this.file);
   
                axios.post('/admin/upload', formData, config)
                .then(function (response) {
                    console.log(response)
                })
                .catch(function (error) {
                    console.log(error)
                });
            },
            getUploadedFiles()
            {
                axios.get('/admin/file/show')
                .then(response => {
                    console.log(response)
                })
            }
        }
    }
</script>