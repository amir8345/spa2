<template>
  <div>
      <form @submit.prevent="submit">
          <input type="text" v-model="mobile_email">
          <input type="submit" value="submit">
      </form>
  </div>
</template>

<script>
export default {

data() {
    return {
        mobile_email: ''
    }
},

methods: {
    submit() {
        axios({
            method: 'post',
            url: '/login',
            data: {
                mobile_email: this.mobile_email
            }
        })
        .then(res => {
            if(res.data == 'new user') {
                this.$store.state.mobile_email = this.mobile_email;
                this.$store.state.demanding_page = 'login';

                this.$router.push('/login/code_verification')
            }

            if (res.data == 'already a user') {
                this.$router.push('/login/password_check')
            }
        })
    }
}


}
</script>

<style>

</style>