<template>
  <div>
      <form @submit.prevent="submit">
          <input type="text" v-model="password">
          <input type="submit" value="submit" >
      </form>
      <button @click="forgot_password" >forgot password</button>
      <button @click="disposable_code" >disposable code</button>
  </div>
</template>

<script>
export default {

    data() {
        return {
            password: ''
        }
    },

    methods: {
        submit() {
            axios({
                url: '/login/password_check',
                method: 'post',
                data: {
                    password: this.password
                }
            })
            .then(res => {
                console.log(res.data);
                if (res.data == 'authentication successfull') {
                    this.$router.push('/profile');
                }
            })
        },

        forgot_password() {
            this.$store.state.demanding_page = 'password_check';
            this.$router.push('/login/code_verification');
        },
        disposable_code() {
            this.$store.state.demanding_page = 'disposable_code';
            this.$router.push('/login/code_verification');
        }
    }




}
</script>

<style>

</style>