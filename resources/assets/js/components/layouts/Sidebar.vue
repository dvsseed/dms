<template>
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      {{ creatingPost }}
      <!-- Sidebar user panel (optional) -->
      <div v-link="{ path: '/profile' }" class="user-panel">
        <div class="pull-left image">
          <img class="profile-user-img img-responsive img-circle" :src="user.avatar" alt="使用者圖像">
        </div>
        <div class="pull-left info">
          <p>{{ user.name }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-user-circle-o text-success"></i> 線上</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" style="font-size: 16px;">
        <li v-if="!isPatient"><a v-link="{ path: '/' }"><i class="fa fa-home" style="color:orange"></i> <span style="color:orange">數據儀表</span></a></li>

        <!-- Optionally, you can add icons to the links -->

        <!-- 權限管理 -->
        <li v-if="menu.menu1" class="treeview">
          <a v-link="{ path: '/basis' }"><i class="fa fa-file-text-o"></i> <span>基本資料</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/t2dm' }"><i class="fa fa-file-text-o"></i> T2DM</a></li>
            <li><a v-link="{ path: '/t1dm' }"><i class="fa fa-file-text-o"></i> T1DM</a></li>
            <li><a v-link="{ path: '/gdm' }"><i class="fa fa-file-text-o"></i> GDM</a></li>
            <li><a v-link="{ path: '/igtifg' }"><i class="fa fa-file-text-o"></i> IGT/IFG</a></li>
          </ul>
        </li>
        <li v-if="menu.menu2"><a v-link="{ path: '/listbasis' }"><i class="fa fa-tint"></i> <span>血糖資料</span></a></li>
        <li v-if="menu.menu3"><a v-link="{ path: '/' }"><i class="fa fa-list-alt"></i> <span>個案管理</span></a></li>

        <li v-if="menu.menu4" class="treeview">
          <a href="#"><i class="fa fa-hospital-o"></i> <span>健保方案</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/cases' }"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增方案</a></li>
            <li><a v-link="{ path: '/listcases' }"><i class="fa fa-table" aria-hidden="true"></i> 方案總覽</a></li>
            <li><a v-link="{ path: '/checkcases' }"><i class="fa fa-check-square-o" aria-hidden="true"></i> 資料核對</a></li>
            <li><a v-link="{ path: '/historychecks' }"><i class="fa fa-history" aria-hidden="true"></i> 歷史記錄(檢驗)</a></li>
            <li><a v-link="{ path: '/historyvpn' }"><i class="fa fa-history" aria-hidden="true"></i> 歷史記錄(上傳)</a></li>
          </ul>
        </li>

        <li v-if="menu.menu5"><a v-link="{ path: '/' }"><i class="fa fa-list-alt"></i> <span>衛教記錄</span></a></li>

        <li v-if="menu.menu6" class="treeview">
          <a href="#"><i class="fa fa-hospital-o"></i> <span>調查</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/' }"><i class="fa fa-plus-square" aria-hidden="true"></i> 注射檢核</a></li>
          </ul>
        </li>

        <li v-if="menu.menu7"><a v-link="{ path: '/' }"><i class="fa fa-list-alt"></i> <span>計畫管理</span></a></li>

        <li v-if="menu.menu8"><a v-link="{ path: '/report' }"><i class="fa fa-line-chart"></i> <span>品質報表</span></a></li>

        <!-- div v-for="menu in menus">
          {{-- menu.content --}}
        </div -->

        <li v-if="isAdmin" class="treeview">
          <a href="#"><i class="fa fa-list" style="color:violet"></i> <span style="color:violet">公告管理</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/posts' }"><i class="fa fa-tasks"></i> 所有文章</a></li>
            <li><a @click="createPost" href="#"><i class="fa fa-file-text" aria-hidden="true"></i> 新增文章</a></li>
          </ul>
        </li>
        <!-- li><a v-link="{ path: '/categories' }"><i class="fa fa-th-large"></i> <span>Categories</span></a></li -->
        <li v-if="isAdmin" class="treeview">
          <a href="#"><i class="fa fa-phone-square" style="color:yellowgreen"></i> <span style="color:yellowgreen">通知</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/telegram' }"><i class="fa fa-telegram"></i> Telegram傳訊</a></li>
            <li><a v-link="{ path: '/emailto' }"><i class="fa fa-envelope" aria-hidden="true"></i> Email寄信</a></li>
          </ul>
        </li>

        <li v-if="isManager"><a v-link="{ path: '/doctors' }"><i class="fa fa-user-md" style="color:red"></i> <span style="color:red">VPN管理</span></a></li>
        <li v-if="isManager"><a v-link="{ path: '/users' }"><i class="fa fa-users" style="color:red"></i> <span style="color:red">使用者管理</span></a></li>
        <li v-if="isManager"><a v-link="{ path: '/menus' }"><i class="fa fa-user-secret" style="color:red"></i> <span style="color:red">權限管理</span></a></li>
        <li v-if="isManager"><a v-link="{ path: '/logs' }"><i class="fa fa-object-group" style="color:red"></i> <span style="color:red">追蹤管理</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="control-sidebar-bg"></div>
</template>

<script>
import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../../Pnotice.js'

export default {
  created() {
    this.fetchUser()
    this.fetchMenu()
  },
  data () {
    return {
      user: {},
      menu: {}
    }
  },
  computed: {
    creatingPost: function () {
      return this.$route.name === 'editpost'
    }
  },
  methods: {
    fetchUser () {
      this.$http({url: '/api/me', method: 'GET'})
      .then(function (response) {
        if (response.data.length == 0) {
          alert('Auto Logout after idle for 20 mins!!')
//          this.$http({url: 'logout', method: 'GET'}).then(function (response) {
          window.location.assign('/auth/logout')
//          })
        } else {
          this.$set('user', response.data)
        }
      })
    },
    fetchMenu () {
      this.$http({url: '/api/menus/fetch', method: 'GET'})
      .then(function (response) {
        if (response.data.length == 0) {
          alert('Auto Logout after idle for 20 mins!!')
//          this.$http({url: 'logout', method: 'GET'}).then(function (response) {
          window.location.assign('/auth/logout')
//          })
        } else {
          this.$set('menu', response.data)
        }
      })
    },
    createPost () {
      if( !this.creatingPost ){
        this.$http({url: '/api/posts', method: 'POST'}).then(function (response) {
          show_stack_info('新增文章...', response)
          this.$router.go('/posts/'  + response.data.hashid + '/edit')
        }, function (response){
          show_stack_error('新增文章失敗!!', response)
        })
      } else {
        swal('抱歉', '請在新增新文章之前，瀏覽其他地方!!', 'info')
      }
    }
  },
  computed: {
    isAdmin: function () {
      return (this.user.role_level == 9)
    },
    isManager: function () {
      return (this.user.role_level >= 7)
    },
    isPatient: function () {
      return (this.user.role_level == 0)
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
  color: #42b983;
}
aside {
  height: 1000px;
}
.user-panel {
  cursor: pointer;
}
</style>
