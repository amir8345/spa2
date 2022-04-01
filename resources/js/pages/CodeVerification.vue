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

    methods: {
        submit() {
            axios({
                method: 'post',
                url: '/login/check_verificaion_code',
                data: {
                    code: this.code
                }
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