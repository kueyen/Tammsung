<template>
  <div class="p-5">
    <card>
      <div class="create-btn-group clearfix">
        <div class="float-left">
          <h3>{{ pageTitle }}</h3>
        </div>
        <div class="float-left ml-2">
          <router-link :to="{ name: `${$route.name}.create` }">
            <button class="btn btn-success">Add <i class="fas fa-plus"></i></button>
          </router-link>
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
          <div class="form-group">
            <label>Sort By:</label>
            <select v-model="sortBy" @change="fetch()" class="form-control ml-2">
              <option value="desc">Sort Newest to Oldest</option>
              <option value="asc">Sort Oldest to Newest</option>
            </select>
          </div>
          <!-- ////////// SEARCH AND SORT //////////-->
        </div>
      </div>
      <div class="clear-fix paginate-group">
        <div class="float-left form-inline">
          <div class="form-group mb-4">
            <label>Search:</label>
            <input
              type="text"
              v-model="q"
              class="form-control ml-2"
              @input="fetch()"
              placeholder="Keyword ..."
            />
          </div>
        </div>
        <div class="float-right">
          <pagination :data="items" @pagination-change-page="fetch"></pagination>
        </div>
      </div>
      <!-- ////////// Paginate TOP //////////-->

      <b-table striped hover :items="items.data" :fields="fields">
        <template v-slot:cell(thumnails)="data">
          <img :src="data.item.image_url" height="100" />
        </template>
        <template v-slot:cell(actions)="data">
          <button class="btn btn-primary btn-sm" @click="showQr(data.item.qr_url)">
            <i class="fas fa-qrcode"></i> QR Code
          </button>

          <button
            class="btn btn-success btn-sm"
            @click="showReceipt(data.item.latest_bills)"
            :disabled="!data.item.latest_bills"
          >
            <i class="far fa-money-bill-alt"></i> Receipt
          </button>
          <button
            class="btn btn btn-link btn-sm"
            @click="kick(data.item.id)"
            :disabled="data.item.latest_bills"
          >
            <i class="far fa-window-close"></i> Kick
          </button>
          <router-link :to="{ name: `${$route.name}.edit`, params: { id: data.item.id } }">
            <button class="btn btn-warning btn-sm"><i class="far fa-edit"></i> Edit</button>
          </router-link>
          <button class="btn btn-danger btn-sm" @click="del(data.item.id)">
            <i class="far fa-trash-alt"></i> Delete
          </button>
        </template>
      </b-table>

      <!-- ////////// TABLES //////////-->

      <div class="clear-fix paginate-group">
        <div class="float-right">
          <pagination :data="items" @pagination-change-page="fetch"></pagination>
        </div>
      </div>
      <!-- ////////// Paginate BOTTOM //////////-->

      <div class="showing">
        Showing {{ items.from }} to {{ items.to }} of {{ items.total }} entries
        <hr class="mt-5" />
      </div>

      <b-modal ref="qrTable" hide-footer title="">
        <div class="text-center">
          <h5>กรุณาสแกน Qr Code ก่อนสั่งอาหาร</h5>
          <img :src="qr" />
        </div>
      </b-modal>

      <b-modal ref="receipt" hide-footer title="">
        <div class="text-center">
          <div class="receptshow mb-3">
            <h3>Bill Details</h3>
            <div class="clearfix" v-for="d in details.details" :key="d.id">
              <div class="float-left">{{ d.food.name }} x {{ d.amount }}</div>
              <div class="float-right">
                <div class="pl-3">
                  <div v-if="d.status == 1">
                    <i class="far fa-circle text-warning"></i>
                  </div>
                  <div v-if="d.status == 2">
                    <i class="fas fa-check-circle text-success"></i>
                  </div>
                  <div v-if="d.status == 3">
                    <i class="far fa-times-circle text-danger"></i>
                  </div>
                </div>
              </div>
              <div class="float-right">{{ d.price_sum }} THB</div>
            </div>
            <hr />
            <div class="clearfix">
              <div class="float-left">
                รวม
              </div>
              <div class="float-right">{{ sum }} THB</div>

              <!-- <pre>{{ details }}</pre> -->
            </div>
          </div>

          <hr />
          <i class="far fa-circle text-warning"></i> รออาหาร
          <i class="fas fa-check-circle text-success"></i> เสริฟแล้ว
          <i class="far fa-times-circle text-danger"></i> ยกเลิกแล้ว

          <hr />
          <button class="btn btn-dark" @click="closeBill()">ชำระด้วยเงินสด</button>
          <button class="btn btn-outline-primary" disabled>ชำระด้วยพร้อมเพย์</button>
        </div>
      </b-modal>
      <!-- ////////// SWOWING //////////-->
    </card>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    qr: '',
    bill_id: '',
    pageTitle: 'Table',
    details: false,
    items: {},
    showItem: 10,
    showItemOptions: [
      { v: 10, t: '10' },
      { v: 25, t: '25' },
      { v: 50, t: '50' },
      { v: 100, t: '100' }
    ],
    q: '',
    sortBy: 'desc',
    fields: [
      { key: 'id', sortable: true, sortDirection: 'desc' },
      'name',
      { key: 'created_at_text', label: 'Created At' },
      { key: 'updated_at_text', label: 'Updated At' },

      'actions'
    ]
  }),
  computed: {
    pageName() {
      return this.$route.name.split('.')[0]
    },
    sum() {
      var sum = 0
      console.log('load sum')
      if (this.details) {
        if (this.details.details.length) {
          console.log('String')
          this.details.details.forEach(detail => {
            sum += detail.price_sum
          })
        }
      }
      return sum
    }
  },
  methods: {
    showQr(qr) {
      this.qr = qr
      this.$refs['qrTable'].show()
    },
    showReceipt(bill) {
      this.bill_id = bill.id
      this.details = bill
      this.$refs['receipt'].show()
    },
    async closeBill(bill_id) {
      const { data } = await axios.post(this.$api(`closebill`), {
        bill_id: this.bill_id
      })
      await this.fetch()
      this.$refs['receipt'].hide()

      console.log(data)
    },
    async fetch(page = 1) {
      const { data } = await axios.get(this.$api(this.pageName), {
        params: {
          page,
          item: this.showItem,
          q: this.q,
          sortBy: this.sortBy
        }
      })
      this.items = data.items
    },
    async del(id) {
      var result = confirm('Are you sure to delete this item?')
      if (result) {
        const { data } = await axios.post(this.$api(this.pageName + `/${id}/delete`), {
          id
        })

        this.fetch()
      }
    },
    async kick(id) {
      var result = confirm('Are you sure to Kick this table?')
      if (result) {
        const { data } = await axios.post(this.$api(this.pageName + `/${id}/kick`), {
          id
        })

        this.fetch()
      }
    }
  },
  created() {
    this.fetch()
  }
}
</script>
