<template>
  <div>
    <form @submit.prevent="submit">
      <input type="text" v-model="username">
      <input type="text" v-model="password">
      <input type="text" v-model="repassword">
      <input type="submit" value="submit">
    </form>
  </div>
</template>

<script>
export default {

  data() {
    return {
      username: '',
      password: '',
      repassword: ''
    }
  },

  methods: {
    submit() {
      axios({
        method: 'post',
        url: '/login/set_username_password',
        data: {
          username: this.username,
          password: this.password,
          repassword: this.repassword
        }
      })
      .then(res => {
        this.$router.push('/profile');
      })
    }
  }

}
</script>

<style>

</style>