<template>
  <!-- info -->
  <div class="callout callout-info lead">
    <h4>數據儀表</h4>
    <h5><p>
      您可以在此瀏覽::[<b>各數據資料...等</b>]--(<b>本共照平台</b>)
    </p></h5>
  </div>
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">文章</span>
          <span class="info-box-number">{{ posts.length }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-orange"><i class="fa fa-th-large"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">類別</span>
          <span class="info-box-number">{{ categories.length }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">使用者</span>
          <span class="info-box-number">{{ users.length }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-table"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">基本資料</span>
          <span class="info-box-number">{{ basis.length }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-olive"><i class="fa fa-database"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">log記錄</span>
          <span class="info-box-number">{{ logs.length }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
      </div>
    </div>
  </div>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  export default {
    components: {
    },
    ready () {
      this.fetchPosts()
      this.fetchCategories()
      this.fetchUsers()
      this.fetchBasis()
      this.fetchLogs()
    },
    data () {
      return {
        token: csrf_token,
        posts: [],
        categories: [],
        users: [],
        basis: [],
        logs: []
      }
    },
    methods: {
      fetchPosts () {
        this.$http({url: '/api/posts', method: 'GET'})
        .then(function (response) {
          this.$set('posts', response.data.data)
          if (typeof this.posts === 'undefined') {
            alert('Auto Logout after idle for 20 mins!!')
            window.location.assign('/auth/logout')
          }
        })
        .catch(function(response) {
          // console.log(response)
          if (response.data == "Unauthorized." && response.status == 401) {
            alert('Auto Logout after idle for 20 mins!!')
            window.location.assign('/auth/logout')
          }
        })
      },
      fetchCategories () {
        this.$http({url: '/api/categories', method: 'GET'})
        .then(function (response) {
          this.$set('categories', response.data.data)
        })
      },
      fetchUsers () {
        this.$http({url: '/api/users/all', method: 'GET'})
        .then(function (response) {
          this.$set('users', response.data.data)
        })
      },
      fetchBasis () {
        this.$http({url: '/api/basis', method: 'GET'})
        .then(function (response) {
          this.$set('basis', response.data.data)
        })
      },
      fetchLogs () {
        this.$http({url: '/api/logs/all', method: 'GET'})
        .then(function (response) {
          this.$set('logs', response.data.data)
        })
      }
    }
  }

</script>
