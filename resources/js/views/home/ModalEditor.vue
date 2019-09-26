<template>
    <form>
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
</template>
<script>
    module.exports = {
        mounted: function(){
            console.log('import')
            this.$root.$on('insertMeet', function (dt) {
                console.log(dt)
            })
        },
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
        }
    }
</script>