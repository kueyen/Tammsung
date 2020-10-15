<template>
  <div>
    <img
      src="https://www.remotelands.com/travelogues/app/uploads/2018/08/shutterstock_761494024.jpg"
      class="w-100 img-fit"
      height="200"
    />

    <div class="pb-4">
      <div class="bg-white p-4 mb-3">
        <div class="container">
          <h3 class="text-o">Food Detail {{ id }}</h3>
          <div class="row bt-dashed text-dark">
              <div class="col-12">
                <img
                  src="https://images.unsplash.com/photo-1551326844-4df70f78d0e9?ixlib=rb-1.2.1"
                  class="w-100 img-fit br20"
                  height="100%"
                />
              </div>
              
              <div class="container"> 
                <br>
                <div class="col-12">
                <span class="text-secondary" style="font-size: 12px">
                  แดนเซอร์สเก็ตช์ โปรโมชั่นฟลุทออกแบบ แรลลีสเตริโอ ซ้ออินดอร์เห่ยช็อคก๋ากั่น
                  ไทยแลนด์ไฮเปอร์แกสโซฮอล์
                </span>
                <hr />
                <h6>พิเศษ</h6>
                <hr />
                <h5>40.00 บาท</h5>
                <br />
                <button class="btn btn-primary py-2 w-100 mt-2">เพิ่มรายการอาหาร</button>
              </div></div>
              
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    id() {
      return this.$route.params.id
    },
  },
}
</script>

<style></style>
