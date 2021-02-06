<template>
  <div>
    <div class="clearfix">
      <div class="wrapper-abc">
        <div class="float-left" style="width: 300px; abackground-color: #2a2a2e; height: 100vh">
          <div class="wrapper-sidebar">
            <sidebar-menu :menu="menu" width="300" class="bg-admin">
              <div slot="header" class="text-center pt-3 text-white">
                <img src="/images/taamsung_w.png" width="20%" /><br />
                <div class="mt-2">จัดการร้านค้า</div>
                <hr />

                <button class="btn btn-outline-light" @click="logout">Log Out</button>
                <hr />
              </div>
            </sidebar-menu>
          </div>
        </div>
        <div class="float-left" style="width: calc(100% - 300px); height: 100vh; overflow: scroll">
          <child v-if="auth.restaurant_id" />
          <div v-else>
            <div class="m-4">
              <card class="p-4">
                ขออภัยคุณไม่สามารถใช้งานระบบได้ เนื่องจากข้อมูลร้านค้าใน account
                นี้ยังไม่ถูกลงทะเบียน
              </card>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { SidebarMenu } from 'vue-sidebar-menu'

export default {
  components: {
    SidebarMenu
  },
  data: () => ({
    menu: [
      {
        header: true,
        title: 'Loading...',
        hiddenOnCollapse: true
      },
      {
        href: '/admin',
        title: 'Dashboard',
        icon: 'fas fa-home'
      },
      {
        href: '/admin/orders',
        title: 'Orders',
        icon: 'fas fa-clipboard-check'
      },
      {
        href: '/admin/promotions',
        title: 'Promotion',
        icon: 'fas fa-ad'
      },
      {
        href: '/admin/tables',
        title: 'Table',
        icon: 'fas fa-table'
      },

      {
        title: 'Food',
        icon: 'fas fa-utensils',
        child: [
          {
            href: '/admin/categories',
            title: 'Category'
          },
          {
            href: '/admin/foods',
            title: 'Food List'
          }
        ]
      }
    ]
  }),
  methods: {
    async logout() {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  },
  created() {
    if (this.auth.restaurant) {
      this.menu[0].title = `ร้าน ${this.auth.restaurant.name}`
    }
  }
}
</script>

<style lang="scss">
body {
  overflow-x: hidden;
}
.v-sidebar-menu .vsm--link_level-1.vsm--link_exact-active,
.v-sidebar-menu .vsm--link_level-1.vsm--link_active {
  -webkit-box-shadow: 3px 0px 0px 0px #fc6011 inset !important;
  box-shadow: 5px 0px 0px 0px #fc6011 inset !important;
}

.v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 {
  color: #fff;
  background-color: #fc6011 !important;
}

.v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 .vsm--icon {
  background-color: #141517 !important;
}
.mh-800 {
  min-height: 800px;
}
.v-sidebar-menu .vsm--toggle-btn {
  display: none !important;
}
.v-sidebar-menu .vsm--toggle-btn:after {
  content: '';
}
.wrapper-sidebar {
  top: 0;
  position: fixed;
  max-width: 300px;
  .bg-admin {
    position: fixed;
    width: 300px;
  }
}
</style>
