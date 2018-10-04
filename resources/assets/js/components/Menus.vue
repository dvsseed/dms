<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>權限管理</h4>
      <h5><p>
        您可以在此管理::[<b>使用者操作選單權限</b>]--(<b>本共照平台的使用者</b>)，功能有：<b>存</b>...等
      </p></h5>
    </div>
    <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有權限</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped">
              <tr>
                <!-- columns -->
                <th>登入Email</th>
                <th>全名</th>
                <th v-for="menu in menus">{{ menu.content }}</th>
                <th>功能</th>
              </tr>
              <tr v-for="user in usermenu">
                <td style ="word-break: break-all;">{{ user.email }}<input type="hidden" name="userid" v-model="user.id"></td>
                <td>{{ user.username }}</td>
                <td><input type="checkbox" id="menu1" value="1" name="usermenu1" v-model="user.menu1"></td>
                <td><input type="checkbox" id="menu2" value="1" name="usermenu2" v-model="user.menu2"></td>
                <td><input type="checkbox" id="menu3" value="1" name="usermenu3" v-model="user.menu3"></td>
                <td><input type="checkbox" id="menu4" value="1" name="usermenu4" v-model="user.menu4"></td>
                <td><input type="checkbox" id="menu5" value="1" name="usermenu5" v-model="user.menu5"></td>
                <td><input type="checkbox" id="menu6" value="1" name="usermenu6" v-model="user.menu6"></td>
                <td><input type="checkbox" id="menu7" value="1" name="usermenu7" v-model="user.menu7"></td>
                <td><input type="checkbox" id="menu8" value="1" name="usermenu8" v-model="user.menu8"></td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-danger" @click="updateMenu(user)">存</button>
                  </div>
                </td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="usermenu" :resource_url="resource_url" :options="options"></v-paginator>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    </div>
    </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      VPaginator: VuePaginator
    },
    ready () {
      this.fetchMenu()
      this.fetchMenus()
    },
    data () {
      return {
        token: csrf_token,
        options: {
          remote_data: 'data',
          remote_current_page: 'current_page',
          remote_last_page: 'last_page',
          remote_next_page_url: 'next_page_url',
          remote_prev_page_url: 'prev_page_url'
        },
        menus: {},
        usermenu: [],
      }
    },
    methods: {
      fetchMenu () {
        this.$http({url: '/api/menus', method: 'GET'})
          .then(function (response) {
            this.$set('menus', response.data.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchMenus () {
        this.$http({url: '/api/menus/showmenu', method: 'GET'}).then(function (response) {
          this.$set('usermenu', response.data.data)
        })
        .catch(function(response) {
          console.log(response)
          if (response.data == "Unauthorized." && response.status == 401) {
            alert('Auto Logout after idle for 20 mins!!')
            window.location.assign('/auth/logout')
          }
        })
      },
      updateMenu (user) {
        if (confirm("確定更新嗎?!")) {
          //event.preventDefault();
          this.$http.patch('/api/menus/update', user).then(function (response) {
            show_stack_success('使用者選單權限已更新!', response)
          }, function (response) {
            show_stack_error('使用者選單權限更新失敗!', response)
          })
          this.$router.go('/')
        }
      },
    },
    computed: {
      resource_url: function () {
        return '/api/menus/showmenu'
      },
    }
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
</style>
