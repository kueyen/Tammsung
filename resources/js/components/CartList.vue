<template>
  <div>
    <div class="bg-white py-3 add_to_basket" v-if="carts.length">
      <div class="container">
        <button class="btn btn-primary w-100" @click="showModal">
          <div class="clearfix">
            <div class="float-left">รายการอาหาร {{ carts.length }} อย่าง {{ sumAmount }} จาน</div>
            <div class="float-right">{{ sum }} บาท</div>
          </div>
        </button>
      </div>
    </div>

    <b-modal ref="my-modal" hide-footer title="รายการอาหาร" scrollable style="z-index: 9999999">
      <div class="row bt-dashed text-dark" v-for="(food, index) in carts" :key="index">
        <div class="col-5">
          <img :src="food.image_url" class="w-100 img-fit br20" height="100%" />
        </div>
        <div class="col-7">
          <h4>{{ food.name }}</h4>
          <span class="text-secondary" style="font-size: 12px">
            {{ food.description }}
          </span>
          <hr />
          <h5>{{ food.real_price }}</h5>
          <del v-if="food.discount">{{ food.price }}</del>
          <h6>X {{ food.amount }}</h6>
        </div>
      </div>

      <div class="clearfix">
        <div class="float-left">รายการอาหาร {{ carts.length }} อย่าง {{ sumAmount }} จาน</div>
        <div class="float-right">{{ sum }} บาท</div>
      </div>

      <button class="btn btn-primary py-2 w-100 mt-2" @click="confirmOrder()">
        ยืนยันการสั่งอาหาร
      </button>
    </b-modal>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      carts: 'cart/carts',
      sum: 'cart/sum',
      sumAmount: 'cart/sumAmount',
    }),
  },
  methods: {
    ...mapActions({
      addToBill: 'cart/addToBill',
    }),
    showModal() {
      this.$refs['my-modal'].show()
    },
    confirmOrder() {
      let _this = this
      this.$swal
        .fire({
          title: 'ต้องการยืนยัน?',
          text: 'ต้องการยืนยันรายการอาหารใช่หรือไม่',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#aaa',
          confirmButtonText: 'ยืนยัน',
          cancelButtonText: 'ยกเลิก',
        })
        .then(async (result) => {
          if (result.isConfirmed) {
            await _this.addToBill()
            _this.$swal
              .fire({
                type: 'success',
                title: 'สำเร็จ',
              })
              .then((res) => {
                if (res.isConfirmed) {
                  _this.closeWindow()
                }
              })
          }
        })
    },
  },
}
</script>

<style scoped>
.add_to_basket {
  position: fixed;
  width: 100%;
  bottom: 0;
  z-index: 2;
}
</style>
