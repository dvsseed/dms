<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>追蹤管理</h4>
      <h5><p>
        您可以在此管理::[<b>使用者操作各項功能的追蹤記錄</b>]--(<b>本共照平台的使用者</b>)，功能有：<b>查看</b>...等
      </p></h5>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有追蹤記錄</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped">
              <tr>
                <!-- columns -->
                <th>登入Email</th>
                <th>Tables</th>
                <th>動作</th>
                <th>IP</th>
                <th>User-Agent</th>
                <th>異動日期</th>
              </tr>
              <tr v-for="log in logs">
                <td>{{ log.email }}</td>
                <td>{{ log.table }}</td>
                <td>{{ log.action }}</td>
                <td>{{ log.ip }}</td>
                <td>{{ log.useragent }}</td>
                <td>{{ log.updated_at }}</td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="logs" :resource_url="resource_url" :options="options"></v-paginator>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'

  export default {
    components: {
      VPaginator: VuePaginator
    },
    ready () {
      this.fetchLogs()
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
        logs: [],
      }
    },
    methods: {
      fetchLogs () {
        this.$http({url: '/api/logs', method: 'GET'})
          .then(function (response) {
            this.$set('logs', response.data.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
                window.location.assign('/auth/logout')
            }
          })
      },
    },
    computed: {
      resource_url: function () {
        return '/api/logs'
      },
    }
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
</style>
