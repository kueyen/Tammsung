<template>
  <div>
    <div class="position-absolute">
      <button class="btn btn-primary m-2 w-100 " @click="$emit('back', food.category.restaurant_id)"><i class="fas fa-chevron-left"></i></button>
      <!-- <button @click="$emit('back', food.category.restaurant_id)"><i class="fas fa-chevron-left"></i></button> -->
    </div>
    
    <img :src="food.image_url" class="w-100 img-fit" height="200" />

    <div class="pb-4">
      <div class="bg-white py-4 mb-3">
        <div class="container">
          <h3 class="text-o">{{ food.name }}</h3>
          <div class="row bt-dashed text-dark">
            <div class="container">
              <br />
              <div class="col-12">
                <span class="text-secondary" style="font-size: 12px">
                  {{ food.description }}
                </span>
                <hr />
                <h6>พิเศษ</h6>
                <hr />
                <h5>{{ food.real_price }} บาท</h5>
                <del v-if="food.discount">{{ food.price }}</del>
                <hr />

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span
                      class="input-group-text"
                      id="basic-addon1"
                      @click="amount--"
                      v-if="amount > 1"
                      >-</span
                    >
                    <span class="input-group-text" id="basic-addon1" v-else>-</span>
                  </div>
                  <input type="tel" class="form-control" v-model="amount" />
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1" @click="amount++">+</span>
                  </div>
                </div>

                <button class="btn btn-primary py-2 w-100 mt-2" @click="add(food)">
                  เพิ่มรายการอาหาร
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  props: {
    id: {
      type: Number,
      default: 1,
    },
  },
  methods: {
    ...mapActions({
      fetch: 'food/show',
      addToCart: 'cart/addToCart',
    }),
    add(food) {
      food.amount = this.amount
      console.log('before add', food)
      this.addToCart(food)
      this.$emit('back', this.food.category.restaurant_id)
    },
  },
  data: () => ({
    sortBy: 'score',
    showId: null,
    amount: 1,
    sorts: [
      {
        name: 'score',
        label: 'คะแนน',
      },
      {
        name: 'name',
        label: 'ชื่อ',
      },
      {
        name: 'price',
        label: 'ราคา',
      },
    ],
  }),
  computed: {
    ...mapGetters({
      food: 'food/show',
      sum: 'cart/sum',
      sumAmount: 'cart/sumAmount',
      carts: 'cart/carts',
    }),
  },
  async created() {
    this.fetch(this.id)
    let find = this.carts.find((el) => el.id == this.id)
    if (find) {
      this.amount = find.amount
    }
  },
}
</script>

