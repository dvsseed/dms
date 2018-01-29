<template>
  <!-- Display Validation Errors -->

  <!-- general form elements -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">瀏覽使用者資料</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="form-group">
        <label for="name">使用者:</label>
        <span class="label label-default lb-md">{{ user.name }}</span>
      </div>
      <div class="form-group">
        <label for="hosp_id">醫事機構代碼:</label>
        <span class="label label-default lb-md">{{ user.hosp_id }}</span>
      </div>
      <div class="form-group">
        <label for="Username">全名:</label>
        <span class="label label-default lb-md">{{ user.username }}</span>
      </div>
      <div class="form-group">
        <label for="Email">登入Email:</label>
        <span class="label label-default lb-md">{{ user.email }}</span>
      </div>
      <div class="form-group">
        <label for="biography">個人簡歷:</label>
        <span class="label label-default lb-md">{{ user.bio }}</span>
      </div>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  export default {
    created() {
      this.userId = this.$route.params.hashid
      this.fetchUser()
    },
    data () {
      return {
        token: csrf_token,
        userId: '',
        user: {
          hosp_id: '',
          username: '',
          email: '',
          bio: ''
        },
      }
    },
    methods: {
      fetchUser () {
        this.$http({url: '/api/users/' + this.userId, method: 'GET'}).then((response) => {
          // success callback
          this.$set('user', response.data)
        }, function (response) {
          // error callback
          console.log(response)
          if (response.data == "Unauthorized." && response.status == 401) {
            alert('Auto Logout after idle for 20 mins!!')
//            this.$http({url: 'logout', method: 'GET'}).then(function (response) {
              window.location.assign('/auth/logout')
//            })
          }
        });
      }
    }
  }
</script>

<style>
  span.lb-md {
    font-size: 16px;
  }
</style>