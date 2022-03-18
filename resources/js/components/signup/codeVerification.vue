<template>
  <div>
      <form @submit.prevent="submit">
          <input type="text" v-model="code" >
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
                url: '/signup/code_verification',
                data: {
                    code: this.code
                }
            })
            .then(res => {
               if(res.data == 'yes') {
                   this.$router.push('/set-username-password');
               }
            })
        
        }

    }

}
</script>

<style>

</style>