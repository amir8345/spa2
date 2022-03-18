<template>
  <div>
      <form @submit.prevent="submit">
          <input type="text" v-model="usernamePassword.username">
          <input type="password" v-model="usernamePassword.password" >
          <input type="password" v-model="usernamePassword.repassword" >
          <input type="submit" value="submit">
      </form>
  </div>
</template>

<script>
export default {

    data() {
        return {
            usernamePassword: {
                username: '',
                password: '',
                repassword: '',
            }
        }
    },

    methods: {
        submit() {
            axios({
                method: 'post',
                url: '/signup/set_username_password',
                data: this.usernamePassword
            })
            .then(res => {
                console.log(res.data);
            })
        }
    }



}
</script>

<style>

</style>