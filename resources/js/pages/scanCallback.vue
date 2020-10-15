<template>
  <div>
    Scan Success
    <button @click="closeWindow()">ปิดหน้านี้</button>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  async created() {
    await this.initializeLiff('1654579616-VqmeqPmM')
    await axios.post('/api/table/add', {
      user_id: this.user.id,
      key: this.key,
    })
  },
  computed: {
    key() {
      let lp = this.$liffParams.get('key')
      let t = lp ? lp : this.$route.query.key
      return t
    },
    type() {
      let lp = this.$liffParams.get('type')
      let t = lp ? lp : this.$route.query.type
      return t
    },
  },
}
</script>

<style></style>
