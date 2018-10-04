import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueValidator from 'vue-validator'

require('./components/vue-strap/docs/assets/docs.css')
require('./components/vue-strap/docs/assets/style.css')
require('prismjs')
require('./components/vue-strap/docs/js/showLanguage')

// import VueStrap from 'vue-strap'
// window.VueStrap = require('vue-strap/dist/vue-strap.js')

// 开启debug模式
/* 關閉vue-devtools -- false*/
Vue.config.devtools = true
/* 關閉錯誤警告 */
Vue.config.debug = true

// 替 Vue 掛上路由的功能
Vue.use(VueRouter)
// 替 Vue 掛上 HTTP Request 的功能
Vue.use(VueResource)
Vue.use(VueValidator)
// Vue.use(VueStrap)

// export 是為了讓其他分離的程式碼也能取得路由的物件實例
export var router = new VueRouter()

// Laravel CSRF-TOKEN protection, get csrf token
try {
  Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content')
  // Vue.http.headers.common['X-CSRF-TOKEN'] = $('#token').attr('content')
  // Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content')
}
catch (err) {
  console.log('No Laravel X-CSRF-TOKEN')
}

// Defauts for AJAX requests
// Vue.http.interceptors.push({
//   request: function (request) {
//     request.headers['Authorization'] = 'Bearer: ' + auth.state.token
//     request.headers['Accept'] = 'application/vnd.mob.v1+json'
//     request.emulateJSON = true
//     return request
//   },
//   response: function (response) {
//     if( response.headers('Auth-Token') ) {
//       auth.actions.setToken( response.headers('Auth-Token') )
//     }
//     if( response.status == 500 ) {
//       if( response.data.message == 'Token has expired, but is still valid.' ) {
//         console.log('RETRY', response)
//       }
//       else {
//         alert('Whoops, an unknown error occured.')
//       }
//     }
//     return response
//   }
// })

/* eslint-disable no-new */

// 创建一个路由器实例
// 并且配置路由规则
var router = new VueRouter({
  history: true,
  root: 'dashboard'
})

// router.beforeEach(function (transition) {
//   if ( !auth.state.token ) {
//     transition.redirect('/login')
//   }
//   if (transition.to.path === '/logout') {
//     auth.actions.logout()
//     transition.redirect('/login')
//   }
//   if ( transition.to.path === '/' || transition.to.path === '/login' ) {
//     transition.redirect('/dashboard')
//   }
//   transition.next()
// })

// Set up routing and match routes to components
// 定義路由
router.map({
  '/': {
    component: require('./components/Home.vue')
  },
  '/users/': {
    name: 'users',
    component: require('./components/Users.vue')
  },
  '/users/:hashid': {
    name: 'showuser',
    component: require('./components/Showuser.vue')
  },
  '/users/:hashid/edit': {
    name: 'edituser',
    component: require('./components/Edituser.vue')
  },
  '/posts/': {
    name: 'posts',
    component: require('./components/Posts.vue')
  },
  '/posts/categories/:hashid': {
    name: 'postincats',
    component: require('./components/Posts.vue')
  },
  '/posts/:hashid/edit': {
    name: 'editpost',
    component: require('./components/Editpost.vue')
  },
  '/categories': {
    component: require('./components/Categories.vue')
  },
  '/categories/:hashid/edit': {
    name: 'categories',
    component: require('./components/Editcategory.vue')
  },
  '/profile': {
    component: require('./components/Profile.vue')
  },
  '/menus': {
    component: require('./components/Menus.vue')
  },
  '/logs': {
    component: require('./components/Logs.vue')
  },
  '/basis': {
    component: require('./components/Basis.vue')
  },
  '/importbasis': {
    name: 'importbasis',
    component: require('./components/ImportBasis.vue')
  },
  '/t2dm': {
    component: require('./components/T2dm.vue')
  },
  '/t1dm': {
    component: require('./components/T1dm.vue')
  },
  '/gdm': {
    component: require('./components/Gdm.vue')
  },
  '/igtifg': {
    component: require('./components/Igtifg.vue')
  },
  '/listmresults/:basePid': {
    name: 'showmresult',
    component: require('./components/Listmresults.vue')
  },
  '/batchmresults/:basePid': {
    name: 'batchmresult',
    component: require('./components/Batchmresults.vue')
  },
  '/batchdelbg/:basePid': {
    name: 'batchdelbg',
    component: require('./components/Batchdelbg.vue')
  },
  '/listbasis': {
    component: require('./components/Listbasis.vue')
  },
  '/tracks': {
    component: require('./components/Tracks.vue')
  },
  '/listtracks': {
    component: require('./components/Listtracks.vue')
  },
  '/cases': {
    component: require('./components/Cases.vue')
  },
  '/listcases': {
    component: require('./components/Listcases.vue')
  },
  '/edittrack/:trackId/edit': {
    name: 'edittrack',
    component: require('./components/Edittrack.vue')
  },
  '/editcase/:caseStage/edit/:caseId': {
    name: 'editcase',
    component: require('./components/Editcase.vue')
  },
  '/checkcases': {
    component: require('./components/Checkcases.vue')
  },
  '/historychecks': {
    component: require('./components/Historychecks.vue')
  },
  '/historyvpn': {
    component: require('./components/Historyvpn.vue')
  },
  '/doctors/': {
    name: 'doctors',
    component: require('./components/Doctors.vue')
  },
  '/doctors/:doctorid/edit': {
    name: 'editdoctor',
    component: require('./components/Editdoctor.vue')
  },
  '/report': {
    component: require('./components/Report.vue')
  },
  '/telegram': {
    component: require('./components/Telegram.vue')
  },
  '/emailto': {
    component: require('./components/Emailto.vue')
  }
})

router.alias({
  // alias can contain dynamic segments
  // the dynamic segment names must match
  // '/users/:hashid': '/users/:hashid/edit'
  '/posts/:hashid': '/posts/:hashid/edit',
  '/categories/:hashid': '/categories/:hashid/edit'
})

// Redirect to the home route if any routes are unmatched
router.redirect({
  '*': '/'
})

// auth.check()
// 啟動路由並將 root component 掛載到 HTML 中 id="app" 的 DOM 上
// router.start(App, '#app')
// Start the app on the #app div
router.start(App, 'body')
