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
            console.log(res.data);
            
            if(res.data == ' your verification code is sent') {
                this.$store.state.mobile_email = this.mobile_email;
                this.$router.push('/code_verification')

            }
        })
    }
}


}
</script>

<style>

</style>