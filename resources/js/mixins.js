import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      scrolled: false,
      windowWidth: 0,
      windowHeight: 0
    }
  },

  beforeMount() {
    window.addEventListener('scroll', this.handleScroll)
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  computed: {
    ...mapGetters({
      user: 'line_auth/user',
      auth: 'auth/user',
      isLoading: 'loading/isLoading'
    })
  },
  methods: {
    ...mapActions({
      fethUser: 'line_auth/fetchUser',
      loadingStart: 'loading/loadingStart',
      loadingStop: 'loading/loadingStop',
      checkRegister: 'line_auth/checkRegister'
    }),
    openWindow(url) {
      this.$liff.openWindow({
        url
      })
      alert(url)
    },
    closeWindow() {
      this.$liff.closeWindow()
    },
    async initializeLiff(myLiffId) {
      this.loadingStart()
      let _this = this
      await this.$liff.init({ liffId: myLiffId })

      if (this.$liff.isLoggedIn()) {
        await this.getProfile()
        this.loadingStop()
      } else {
        this.$liff.login()
      }

      console.log('liff end')
    },
    async getProfile() {
      let _this = this
      const profile = await this.$liff.getProfile()

      // this.checkRegister(profile.userId)

      // console.log(profile, profile.userID)

      await this.fethUser({
        id: profile.userId,
        name: profile.displayName,
        status: profile.statusMessage,
        image_url: profile.pictureUrl,
        email: _this.$liff.getDecodedIDToken().email,
        token: _this.$liff.getAccessToken()
      })
    }
  }
}
