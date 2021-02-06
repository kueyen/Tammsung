<template>
  <div class="p-5">
    <card>
      <div class="create-btn-group clearfix">
        <div class="float-left">
          <h3>{{ pageTitle }}</h3>
        </div>
        <div class="float-left ml-2">
          <button class="btn btn-primary" @click="fetch()">
            Refresh <i class="fas fa-sync-alt"></i>
          </button>
        </div>
      </div>
      <!-- ////////// Page Title //////////-->
      <div class="clearfix mb-4">
        <div class="float-left form-inline">
          <div class="form-group">
            <label>Show</label>
            <select v-model="showItem" class="form-control mx-2" @change="fetch()">
              <option :value="item.v" v-for="item in showItemOptions" :key="item.v">
                {{ item.t }}
              </option>
            </select>
            <label>entries</label>
          </div>
          <!-- ////////// ITEM ENTRIES //////////-->
        </div>
        <div class="float-right form-inline">
          <!-- <div class="form-group">
            <label>Sort By:</label>
            <select v-model="sortBy" @change="fetch()" class="form-control ml-2">
              <option value="desc">Sort Newest to Oldest</option>
              <option value="asc">Sort Oldest to Newest</option>
            </select>
          </div> -->
          <!-- ////////// SEARCH AND SORT //////////-->
        </div>
      </div>
      <div class="clear-fix paginate-group clearfix">
        <div class="float-left form-inline">
          <!-- <div class="form-group mb-4">
            <label>Search:</label>
            <input
              type="text"
              v-model="q"
              class="form-control ml-2"
              @input="fetch()"
              placeholder="Keyword ..."
            />
          </div> -->
        </div>
        <div class="float-right">
          <pagination :data="items" @pagination-change-page="fetch"></pagination>
        </div>
      </div>
      <!-- ////////// Paginate TOP //////////-->

      <!-- <b-table striped hover :items="items.data" :fields="fields" :striped="true">
        <template v-slot:cell(thumnails)="data">
          <img :src="data.item.image_url" height="100" width="100" style="object-fit: cover" />
        </template>
        <template v-slot:cell(name)="data">
          {{ data.item.name }} {{ data.item.is_recommend ? ` (รายการแนะนำ)` : '' }}
        </template>
        <template v-slot:cell(price)="data">
          {{ data.item.real_price }} บาท
          {{ data.item.discount ? ` (ลด ${data.item.discount}%)` : '' }}
        </template>
        <template v-slot:cell(actions)="data">
          <router-link :to="{ name: `${$route.name}.edit`, params: { id: data.item.id } }">
            <button class="btn btn-warning"><i class="far fa-edit"></i> Edit</button>
          </router-link>
          <button class="btn btn-danger" @click="del(data.item.id)">
            <i class="far fa-trash-alt"></i> Delete
          </button>
        </template>
      </b-table> -->

      <!-- ////////// TABLES //////////-->

      <hr />
      <div class="row">
        <div class="col-md-3 d-flex mt-3" v-for="(item, index) in items.data" :key="item.id">
          <div class="card h-100 w-100">
            <div class="card-header">
              <div v-if="item.bill.table">
                {{ item.bill.table.name }}
              </div>
            </div>

            <!-- <img
                class="card-img-top"
                :src="item.food.image_url"
                alt="Card image cap"
                height="150"
                style="object-fit: cover"
              /> -->
            <div class="card-body p-3">
              <h4 class="card-title" v-if="item.food">{{ item.food.name }}</h4>

              <p class="card-text">{{ item.amount_v }} จาน</p>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span
                    class="input-group-text"
                    id="basic-addon1"
                    @click="min(index)"
                    v-if="item.amount > 1"
                    >-</span
                  >
                  <span class="input-group-text" id="basic-addon1" v-else>-</span>
                </div>
                <input type="tel" class="form-control" v-model="item.amount" />
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon1" @click="plus(index)">+</span>
                </div>
              </div>
              <span>{{ item.created_at_text }}</span>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary btn-lg" @click="update(item.id, 2, item.amount)">
                เสิร์ฟ
              </button>
              <button class="btn btn-link btn-sm" @click="update(item.id, 3, item.amount_v)">
                ยกเลิก
              </button>
            </div>
          </div>
        </div>
      </div>

      <hr />

      <div class="clear-fix paginate-group clearfix">
        <div class="float-right">
          <pagination :data="items" @pagination-change-page="fetch"></pagination>
        </div>
      </div>
      <!-- ////////// Paginate BOTTOM //////////-->

      <div class="showing">
        Showing {{ items.from }} to {{ items.to }} of {{ items.total }} entries
        <hr class="mt-5" />
      </div>
      <!-- ////////// SWOWING //////////-->
    </card>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    pageTitle: 'Orders',
    items: {},
    showItem: 10,
    showItemOptions: [
      { v: 10, t: '10' },
      { v: 25, t: '25' },
      { v: 50, t: '50' },
      { v: 100, t: '100' },
    ],
    q: '',
    sortBy: 'desc',
    fields: [
      { key: 'id', sortable: true, sortDirection: 'desc' },
      'thumnails',
      'name',
      'description',
      { key: 'category.name', label: 'Category' },
      'price',
      { key: 'created_at_text', label: 'Created At' },
      'actions',
    ],
  }),
  computed: {
    pageName() {
      return this.$route.name.split('.')[0]
    },
  },
  methods: {
    async fetch(page = 1) {
      const { data } = await axios.get(this.$api(this.pageName), {
        params: {
          page,
          item: this.showItem,
          q: this.q,
          sortBy: this.sortBy,
        },
      })
      this.items = data.items
    },
    async plus(index) {
      this.items.data[index].amount++
      let item = this.items.data[index]
      await this.update(item.id, 1, item.amount, false)
    },
    async min(index) {
      this.items.data[index].amount--
      let item = this.items.data[index]
      await this.update(item.id, 1, item.amount, false)
    },
    async update(id, status, amount, showBox = true) {
      const { data } = await axios.post(this.$api(this.pageName) + `/${id}/update`, {
        status,
        amount,
      })

      let vm = this
      if (showBox) {
        this.$swal
          .fire({
            type: 'success',
            title: `${status == 2 ? 'เสิร์ฟ' : 'ยกเลิก'} สำเร็จ`,
          })
          .then(() => {
            vm.fetch()
          })
      }
    },
    async del(id) {
      var result = confirm('Are you sure to delete this item?')
      if (result) {
        const { data } = await axios.post(this.$api(this.pageName + `/${id}/delete`), {
          id,
        })

        this.fetch()
      }
    },
  },
  created() {
    this.fetch()
  },
}
</script>
