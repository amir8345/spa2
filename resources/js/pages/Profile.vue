<template>
  <div>
      {{ as }}
      <button @click="logout">logout</button>
  </div>
</template>

<script>
export default {

    data() {
        return {
            as: ''
        }
    },

    mounted(){
        axios({
            url: '/profile',
            method: 'get',
        })
        .then(res => {
            this.as = res.data;
        })
    },

    methods: {
        logout() {
            axios({
                url: '/login/logout',
                mehtod: 'get'
            })
            .then(res => {
                if (res.data == 'logout successfull') {
                    this.$router.push('/');
                }
            })
        }
    }


}
</script>

<style>

</style>