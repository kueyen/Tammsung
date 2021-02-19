<template>
  <div class="p-4">
    <div class="container-fluid">
      <div class="card p-4">
        <div class="card p-4">
          <div class="row">
            <div class="col-md-6">
              <div class="card p-2">
                <div class="my-2 text-center">
                  <i class="fas fa-quran fa-4x"></i>
                </div>

                บิลทั้งหมด
                <hr />

                <div class="text-center">
                  <span class="h1"> {{ db.totalBill }} </span>
                </div>

                <div class="text-right">
                  รายการ
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card p-2">
                <div class="my-2 text-center">
                  <i class="fas fa-quran fa-4x"></i>
                </div>

                รายได้ของวันนี้
                <hr />

                <div class="text-center">
                  <span class="h1"> {{ db.totalBillPriceToday }} </span>
                </div>

                <div class="text-right">
                  รายการ
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card p-2">
                <div class="my-2 text-center">
                  <i class="fas fa-quran fa-4x"></i>
                </div>

                รายได้ของเดือนนี้
                <hr />

                <div class="text-center">
                  <span class="h1"> {{ db.totalBillPriceMonth }} </span>
                </div>

                <div class="text-right">
                  รายการ
                </div>
              </div>
            </div>

            <div class="col-md-6 mt-2">
              <div class="card p-2">
                <div class="my-2 text-center">
                  <i class="fas fa-quran fa-4x"></i>
                </div>

                รายได้รวมทั้งหมด
                <hr />

                <div class="text-center">
                  <span class="h1"> {{ db.totalBillPriceAllTime }} </span>
                </div>

                <div class="text-right">
                  รายการ
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card p-4 mt-3">
          <h1>welihfewiuhefw9i</h1>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Amount(in Bill Detail)</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(food, index) in filterFoodMax(db.foodMax)"
                :key="food.id"
                :class="{ 'bg-primary bg-org py-5 h1 text-white': index == 0 }"
              >
                <td scope="row">{{ index + 1 }}</td>
                <td>
                  <img
                    :src="food.image_url"
                    width="60"
                    height="60"
                    style="object-fit:cover; border-radius:50%;"
                    class="mr-3"
                  />{{ food.name }}
                </td>
                <td>{{ food.bill_details_count }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
export default {
  data: () => ({
    db: null
  }),
  methods: {
    async fetch() {
      const { data } = await axios.get("/api/dashboard");
      this.db = data;
    },
    filterFoodMax(foodmax) {
      let items = _.orderBy(foodmax, "bill_details_count", "desc");
      let foods = [];
      items.map((el, index) => {
        if (index < 10) {
          foods.push(el);
        }
      });

      return foods;
    }
  },
  created() {
    this.fetch();
  }
};
</script>

<style>
.bg-org {
  background-color: #f26323 !important;
}
</style>
