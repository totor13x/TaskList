<template>
    <div class="container">

        <div class="notification is-primary">
            <div class="container">
            <h1 class="title">
                Task list | Admin Panel
            </h1>

            <h1 class="subtitle">
                {{admin.name}} | {{admin.email}}
            </h1>
            </div>
        </div>
        <div class="columns">
            <div class="column is-3">
                <b-menu>
                    <b-menu-list label="Menu">
                        <b-menu-item 
                            icon="information-outline" 
                            tag="router-link"
                            label="Home"
                            to="/admin"></b-menu-item>
                        <b-menu-item 
                            icon="information-outline" 
                            tag="router-link"
                            label="Home11"
                            to="/admin/file/upload"></b-menu-item>
                    </b-menu-list>
                </b-menu>
            </div>
            <div class="column">
                <router-view></router-view>
            </div>
        </div>
  </div>
</template>
<script>
    export default {
        data() {
            return {
                admin: [],
                isActive: true
            }
        },
        mounted: function() {
            this.admin = this.$attrs.admin
            console.log(this.admin)
        },
    }
</script>