<template>
<!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">編輯個人資料</h3>
      </div>
      <!-- /.box-header -->
      <!-- vue validator start -->
      <validator name="validation">
      <!-- form start -->
      <form role="form" novalidate>
        <div class="box-body">
          <img class="profile-user-img img-responsive img-circle"
          :src="user.avatar" alt="User profile picture">
          <div class="form-group">
            <label for="name">帳號:</label>
            <input v-model="user.name" class="form-control" name="name" placeholder="Name">
          </div>
          <div class="form-group">
            <label for="hosp_id">醫事機構代碼:</label>
            <input v-model="user.hosp_id" class="form-control" name="hosp_id" placeholder="Hospital ID">
          </div>
          <div class="form-group">
            <label for="Username">全名:</label>
            <input v-model="user.username" class="form-control" name="Username" placeholder="Username">
          </div>
          <div class="form-group">
            <label for="Email">Email地址:</label>
            <input v-model="user.email" type="email" class="form-control" name="Email" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="gravatar">大頭照:</label>
            <input v-model="user.avatar" class="form-control disabled" name="gravatar" placeholder="Avatar" disabled="true">
          </div>
          <div class="form-group">
            <label for="biography">個人簡歷:</label>
            <textarea v-model="user.bio" class="form-control" name="bio" rows="5" id="biography"></textarea>
          </div>
          <div class="form-group">
            <label for="password">目前密碼:</label>
            <input v-model="user.password" type="password" class="form-control" name="password" placeholder="Password" v-validate:password="{ required: true, minlength: 6, maxlength: 12 }">
          </div>
          <div class="form-group">
            <label for="new_password">新密碼:</label>
            <input v-model="user.new_password" type="password" class="form-control" name="new_password" placeholder="New Password">
          </div>
          <div class="form-group">
            <label for="new_password_confirmation">確認密碼:</label>
            <input v-model="user.new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm Password">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button @click="updateUser(user)" class="btn btn-primary btn-lg btn-flat" v-if="$validation.valid">存</button>
        </div>
        <div class="errors">
          <p v-if="$validation.password.required" class="bg-danger text-danger">請輸入...舊密碼, 以確認您的身份!!</p>
          <p v-if="$validation.password.minlength" class="bg-warning text-warning">請注意...密碼長度至少6位!!</p>
          <p v-if="$validation.password.maxlength" class="bg-warning text-warning">請注意...密碼的長度最多12位啦!!</p>
        </div>
      </form>
      </validator>
    </div>
    <!-- /.box -->
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

  export default {
    created() {
      this.fetchUser()
    },
    data () {
      return {
        token: csrf_token,
        user: {
          name: '',
          hosp_id: '',
          username: '',
          email: '',
          password: '',
          new_password: '',
          new_password_confirmation: '',
          avatar: '',
          bio: ''
        }
      }
    },
    methods: {
      fetchUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('user', response.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      updateUser (user) {
  //      event.preventDefault();
        this.$http.patch('/api/me', user).then(function (response) {
          show_stack_success('使用者資料已更新!', response)
        }, function (response){
          show_stack_error('使用者資料更新失敗!', response)
        })
        this.$router.go('/')
      },
      onBlur (e) {
        var pass = e.target.value;
        if (pass == null || pass == ''){
          alert('請輸入...舊的密碼, 以確認!!')
        } else if (pass.length <= 6){
          alert('請注意...密碼長度至少6位!!')
        }
      },
    },
  }
</script>
