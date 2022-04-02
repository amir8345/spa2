<template>
  <div>

      <h4 v-if="updated">password updated successfully</h4>

      <form @submit.prevent="submit">
          <input type="text" v-model="password" >
          <input type="text" v-model="repassword" >
          <input type="submit" value="submit">
      </form>
  </div>
</template>

<script>
export default {

  data() {
    return {
      password: '',
      repassword: '',
      updated: false
    }
  },

  methods: {
    submit() {
      axios({
        url: '/login/update_password',
        method: 'post',
        data: {
          password: this.password,
          repassword: this.repassword
        }
      })
      .then(res => {
        if (res.data == 'password updated successfully') {
          this.updated = true;
        }
      })
    }
  }

}
</script>

<style>

</style>