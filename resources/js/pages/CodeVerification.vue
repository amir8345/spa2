<template>
  <div>
      <h3>verification code sent to {{ $store.state.mobile_email }}</h3>
      <form @submit.prevent="submit">
          <input type="text" v-model="code">
          <input type="submit" value="submit">
      </form>
  </div>
</template>

<script>
export default {

    data() {
        return {
            code: ''
        }
    },

    mounted() {
        axios({
            url: '/login/send_code',
            method: 'get'
        })
    },

    methods: {
        submit() {
            axios({
                method: 'post',
                url: '/login/code_verification',
                data: {
                    code: this.code
                }
            })
            .then(res => {
                if (res.data == 'right code') {

                    if (this.$store.state.demanding_page == 'login') {
                        this.$router.push('/login/username_password')
                    }
                    if (this.$store.state.demanding_page == 'password_check') {
                        this.$router.push('/login/forgot_password')
                    }
                     if (this.$store.state.demanding_page == 'disposable_code') {
                         axios({
                             url: '/login/disposable_code',
                             method: 'get'
                         })
                         .then(res =>{
                             if (res.data == 'authentication successfull') {
                                 this.$router.push('/profile');
                             }
                         })
                    }

                    return;

                }
                console.log('wrong code');
            })
        }
    }


}
</script>

<style>

</style>