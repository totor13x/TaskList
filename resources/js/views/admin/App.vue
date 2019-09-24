<template>
    <div class="container">

        <div class="notification is-primary">
            <div class="container">
            <h1 class="title">
                Task list | Admin Panel
            </h1>
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
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
                            to="/admin/upload"></b-menu-item>
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
                isActive: true
            }
        }
    }
</script>