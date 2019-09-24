<template>
  <div class="columns is-centered">
  <div class="column is-half">
      <div class="notification is-primary">
        <div class="container">
          <h1 class="title">
            Task list
          </h1>
        </div>
      </div>
    <!--
    <div class="columns">
      <div class="column">
        -->
        <div class="container">
          <router-view></router-view>
        </div>
        <!--
      </div>
      <div class="column is-4">
      </div>
      -->
  </div>
  </div>
</template>
<script>
  module.exports = { 
    beforeCreate: function() {
      var token = localStorage.getItem('token')
      if (token != undefined && token != null) 
      {
        window.axios.defaults.headers.common['Authorization'] = token;
        this.$root.token = token;
      }
      else
      {
        let self = this
        axios.get('/auth')
        .then(function(response){
          localStorage.setItem('token', response.data.token)
          window.axios.defaults.headers.common['Authorization'] = response.data.token;
          self.$root.token = response.data.token
        })
      }
    },   
  }
</script>