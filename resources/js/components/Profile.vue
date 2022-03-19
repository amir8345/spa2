<template>
  <div>

      {{ resp }}
      
  </div>
</template>

<script>
export default {

    data() {
        return {
            resp: ''
        }
    },

    mounted() {
        axios({
            url: '/profile',
            method: 'get',
        })
        .then(res => {
            this.resp = res.data;
        })
    }

}
</script>

<style>

</style>