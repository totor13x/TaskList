<template>
  <div class="columns is-centered">
  <div class="column is-8">
      <div class="notification is-primary">
        <div class="container">
          <h1 class="title">
            Task list
            
            <b-dropdown position="is-bottom-left" class="is-pulled-right" hoverable aria-role="list" v-if="fileList" >
                <b-button 
                  type="is-primary" 
                  inverted 
                  slot="trigger" 
                  icon-right="menu-down">
                    Документация
                </b-button>

                <b-dropdown-item has-link aria-role="listitem" 
                v-for="file in fileList" v-bind:key="file.id">
                <a :href="`/files/download/${file.link}`" target="_blank">
                {{file.name}}
                </a>
                </b-dropdown-item>
                
            </b-dropdown>
          </h1>
        </div>
      </div>
    
    <div class="columns">
      <div class="column is-2">
      </div>
      <div class="column">
        
        <div class="container">
          <router-view></router-view>
        </div>
        
      </div>
      
  </div>
  </div>
</div>
</template>
<script>
  module.exports = { 
    data: function(){
      return {
        fileList: [],
      }
    },
    mounted: function()
    {
      axios.get('files/show')
      .then(response => {
        console.log(response.data)
        this.fileList = response.data
      })
    },
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