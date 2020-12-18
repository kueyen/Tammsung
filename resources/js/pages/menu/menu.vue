<template>
  <div class="">
    <CartList />
    <div v-if="!showId">
      <scrollactive class="my-nav" active-class="active">
        <ul class="nav-center clearfix">
          <li v-for="category in menu.categories" :key="category.id">
            <a :href="`#${category.id}`" class="scrollactive-item">{{ category.name }}</a>
          </li>
        </ul>
      </scrollactive>

      <div v-if="menu" class="menuPage">
        <img :src="menu.profile_url" class="w-100 img-fit" height="200" />

        <div class="pb-4">
          <div class="bg-white p-4 mb-3">
            <div class="container">
              <h3 class="text-o">{{ menu.name }}</h3>
              <span class="text-secondary">{{ menu.description }}</span>
            </div>
          </div>

          <div class="bg-white py-3">
            <div class="container">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-search"></i></div>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="inlineFormInputGroup"
                  placeholder="ค้นหา"
                />
              </div>
              <hr />
              <label for="">จัดเรียงโดย</label>

              <div class="input-group mb-2">
                <select v-model="sortBy" class="form-control">
                  <option :value="sort.name" v-for="sort in sorts" :key="sort.name">
                    {{ sort.label }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="bg-white py-3 mt-3">
            <div class="container">
              <div class="clearfix">
                <div class="float-left">ยอดนิยม</div>
              </div>
              <div>
                <carousel :perPage="1" class="mt-4">
                  <slide v-for="food in menu.food_recomments" :key="food.id">
                    <img :src="food.image_url" class="w-100 img-fit br20" height="200" />
                  </slide>
                </carousel>
              </div>
            </div>
          </div>

          <section
            class="bg-white py-3 mt-3 mb-3"
            v-for="cat in menu.categories"
            :key="cat.id"
            :id="cat.id"
          >
            <div class="container">
              <div class="clearfix mb-3">
                <div class="float-left">{{ cat.name }}</div>
              </div>
              <!-- // row1 -->

              <div class="row bt-dashed text-dark" v-for="food in cat.foods" :key="food.id">
                <div class="card w-100 ">
                  <img :src="food.image_url" class="card-img-top" alt="..." />
                  <div class="card-body">
                    <h5 class="card-title">{{ food.name }}</h5>
                    <div class="card-text">
                      <span class="text-secondary" style="font-size: 12px">
                        {{ food.description }}
                      </span>
                      <hr />
                      <h5>{{ food.real_price }} บาท</h5>
                      <del v-if="food.discount">{{ food.price }}</del>
                      <br />
                    </div>
                    <button class="btn btn-primary py-2 w-100 mt-2" @click="showId = food.id">
                      เพิ่มรายการอาหาร
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <div v-else>
      <menuShow :id="showId" @back="onBack" />
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import CartList from '~/components/CartList'
import menuShow from './menuShow'

export default {
  components: {
    CartList,
    menuShow
  },
  methods: {
    ...mapActions({
      fetch: 'restaurant/show',
      addToCart: 'cart/addToCart'
    }),
    onBack(restaurant_id) {
      this.showId = false
      this.fetch(restaurant_id)
    }
  },
  data: () => ({
    sortBy: 'score',
    showId: null,
    sorts: [
      {
        name: 'score',
        label: 'คะแนน'
      },
      {
        name: 'name',
        label: 'ชื่อ'
      },
      {
        name: 'price',
        label: 'ราคา'
      }
    ]
  }),
  computed: {
    ...mapGetters({
      menu: 'restaurant/show',
      sum: 'cart/sum',
      sumAmount: 'cart/sumAmount',
      carts: 'cart/carts'
    }),
    id() {
      let lp = this.$liffParams.get('id')
      let t = lp ? lp : this.$route.query.id
      return t
    },
    show() {
      let lp = this.$liffParams.get('show')
      let t = lp ? lp : this.$route.query.show
      return t
    }
  },
  async created() {
    await this.initializeLiff('1654579616-vejGe5jz')
    if (this.show) {
      this.showId = this.show
    } else {
      this.fetch(this.id)
    }
  }
}
</script>

<style scoped lang="scss">
.menuPage {
  padding-top: 50px;
}
.my-nav {
  overflow-x: scroll;
  height: 50px;
  width: 100%;
  background: white;
  padding: 10px 0;
  position: fixed;
  z-index: 9;
  -webkit-box-shadow: -1px 3px 5px 5px rgba(255, 132, 0, 0.27);
  -moz-box-shadow: -1px 3px 5px 5px rgba(255, 132, 0, 0.27);
  box-shadow: -1px 3px 5px 5px rgba(255, 132, 0, 0.27);
  a {
    margin-right: 10px;
    text-decoration: none;
    color: #777;
  }
  a.active {
    color: orange;
    border-bottom: 3px solid orange;
  }

  .nav-center {
    overflow-x: auto;
    width: 1000px;
    li {
      display: inline-block;

      list-style: none;
    }
  }
}
</style>
