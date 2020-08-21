<template>
  <div class="main-layout">
    <navbar />
    <loading
      :active.sync="isLoading"
      :can-cancel="false"
      color="#fff"
      :is-full-page="true"
      background-color="#fc6011"
      :opacity="1"
    />
    <div class="container mt-4 pb-4">
      <child />
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
export default {
  name: 'MainLayout',

  components: {
    Navbar,
    Loading
  }
}
</script>

<style>
@import url('/css/bootstrap.min.css');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css');
/* edit bootstrap */
body {
  background-color: #f0f5f9;
}
.btn {
  padding-top: 15px;
  padding-bottom: 15px;
}
.btn-primary {
  color: #fff;
  background-color: #fc6011 !important;
  border-color: #fc6011 !important;
}
.card {
  border-radius: 1rem;
  /* border: 1px solid #fc6011; */
}
.card-header {
  border-radius: 1rem 1rem 0 0 !important;
  border-bottom: 0;
}

.input-group-text {
  background: #fff;
  color: #929090;
}
/* add new */

.bg-o {
  color: #fff;
  background: #fc6011;
}
.avatar {
  border-radius: 50%;
  object-fit: cover;
}
</style>
