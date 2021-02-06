function page(path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}
function cruGen(path, name,start = 'admin') {
  return [
    {
      path: `${path}`,
      name: path,
      component: page(`${start}/${name}/${name}.vue`)
    },
    {
      path: `${path}/create`,
      name: `${path}.create`,
      component: page(`${start}/${name}/form.vue`)
    },
    {
      path: `${path}/:id/edit`,
      name: `${path}.edit`,
      component: page(`${start}/${name}/form.vue`)
    }
  ]
}
export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },
  { path: '/scan/callback', name: 'scan', component: page('scanCallback.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  {
    path: '/password/reset/:token',
    name: 'password.reset',
    component: page('auth/password/reset.vue')
  },
  {
    path: '/email/verify/:id',
    name: 'verification.verify',
    component: page('auth/verification/verify.vue')
  },
  {
    path: '/email/resend',
    name: 'verification.resend',
    component: page('auth/verification/resend.vue')
  },

  { path: '/home', name: 'home', component: page('home.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  {
    path: '/menus',
    name: 'menu.index',
    component: page('menu/menu.vue'),
    savedPosition: true
  },
  {
    path: '/menus/show',
    name: 'menu.show',
    component: page('menu/menuShow.vue')
  },
  {
    path: '/payments',
    name: 'payment',
    component: page('payments/payment.vue')
  },
  {
    path: '/admin',
    component: page('admin/index.vue'),
    children: [
      {
        path: '',
        name: 'adminHome',
        component: page('admin/adminDashboard.vue')
      },
      ...cruGen('categories', 'categories'),
      ...cruGen('foods', 'foods'),
      ...cruGen('tables', 'tables'),
      ...cruGen('orders', 'orders'),
      ...cruGen('promotions', 'promotions')


    ]
  },

  {
    path: '/superAdmin',
    component: page('superAdmin/index.vue'),
    children: [
      {
        path: '',
        name: 'superAdminHome',
        component: page('superAdmin/adminDashboard.vue')
      },
      ...cruGen('sa_promotions', 'sa_promotions','superAdmin'),
      


    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
