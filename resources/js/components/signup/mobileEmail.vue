<template>
  <div>
      <form @submit.prevent="submit">
        <input type="text" v-model="mobile">
        <input type="submit" value="submit">
      </form>
  </div>
</template>

<script>
export default {

    data() {
        return {
            mobile: ''
        }
    },

    methods: {
        submit() {
            axios({
                url: '/signup/mobile',
                method: 'post',
                data: {
                    mobile: this.mobile
                }
            })
            .then(res => {
                console.log(res.data);
               this.$router.push('/code-verification');
            })
        }
    }



}
</script>

<style>

</style>