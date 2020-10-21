<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card v-if="pageCase == 1" title="ลงทะเบียน">
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
          <div class="text-center">
            <img :src="user.image_url" width="200" height="200" class="avatar" />
          </div>

          <!-- Name -->
          <div class="form-group row mt-4">
            <div class="col-md-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user" />
                  </span>
                </div>
                <input
                  v-model="form.first_name"
                  :class="{ 'is-invalid': form.errors.has('first_name') }"
                  class="form-control"
                  type="text"
                  placeholder="ชื่อจริง"
                  name="first_name"
                />
              </div>
              <has-error :form="form" field="first_name" />
            </div>
          </div>

          <!-- LastName -->
          <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user" />
                  </span>
                </div>
                <input
                  v-model="form.last_name"
                  :class="{ 'is-invalid': form.errors.has('last_name') }"
                  class="form-control"
                  type="text"
                  placeholder="นามสกุล"
                  name="last_name"
                />
              </div>

              <has-error :form="form" field="last_name" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-envelope" />
                  </span>
                </div>
                <input
                  v-model="form.email"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                  class="form-control"
                  type="email"
                  placeholder="อีเมล"
                  name="email"
                />
              </div>

              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Phone -->
          <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-phone" />
                  </span>
                </div>
                <input
                  v-model="form.tel"
                  :class="{ 'is-invalid': form.errors.has('tel') }"
                  class="form-control"
                  type="tel"
                  placeholder="เบอร์โทรศัพท์"
                  name="tel"
                />
              </div>

              <has-error :form="form" field="tel" />
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12 d-flex">
              <!-- Submit Button -->
              <v-button :loading="form.busy" class="w-100">ลงทะเบียน</v-button>
            </div>
          </div>
        </form>
      </card>
      <card v-else-if="pageCase == 2">
        ลงทะเบียนเสร็จสิ้น
        <hr />ขอบคุณสำหรับการลงทะเบียน
        <v-button class="w-100" @click="closeWindow()">ปิดหน้านี้</v-button>
      </card>
      <card v-else-if="pageCase == 3">
        ขออภัย
        <hr />คุณได้ทำการลงทะเบียนแล้ว
        <v-button class="w-100" @click="closeWindow()">ปิดหน้านี้</v-button>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
export default {
  middleware: 'guest',

  metaInfo() {
    return { title: 'ลงทะเบียน' }
  },

  data: () => ({
    pageCase: 0,
    form: new Form({
      first_name: '',
      last_name: '',
      line_user_id: 1,
      email: '',
      tel: '',

      // password: '',
      // password_confirmation: ''
    }),
  }),
  watch: {
    async user() {
      this.loadingStop()

      const { data } = await axios.get(`/api/line/user/check/register?line_user_id=${this.user.id}`)

      if (data.result.isRegistered) {
        this.pageCase = 3
      } else {
        this.pageCase = 1
      }

      this.form.email = this.user.email
      this.form.line_user_id = this.user.id
    },
  },
  async created() {
    await this.initializeLiff('1654579616-o707RL0n')
  },

  methods: {
    async register() {
      // Register the user.

      const { data, status } = await this.form.post('/api/register')

      if (status == 200) {
        this.pageCase = 2
      }
    },
  },
}
</script>
