<template>
  <!-- Display Validation Errors -->

  <!-- general form elements -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">編輯使用者資料</h3>
    </div>
    <!-- /.box-header -->
    <!-- vue validator start -->
    <validator name="validation">
      <!-- form start -->
      <form role="form" novalidate>
        <div class="box-body">
          <div class="form-group">
            <label for="name"><span class="text-danger">*帳號</span>:</label>
            <input v-model="user.name" class="form-control" name="name" placeholder="Name">
          </div>
          <div class="form-group">
            <label for="password"><span class="text-danger">*密碼</span>:</label>
            <input v-model="user.password" type="password" class="form-control" name="password" placeholder="Password" v-validate:password="{ required: true, minlength: 6, maxlength: 20 }">
          </div>
          <div class="form-group">
            <label for="hosp_id"><span class="text-danger">*醫事機構代碼</span>:</label>
            <input v-model="user.hosp_id" class="form-control" name="hosp_id" placeholder="Medical Code" v-validate:hosp_id="{ required: true }">
          </div>
          <div class="form-group">
            <label for="username"><span class="text-danger">*全名</span>:</label>
            <input v-model="user.username" class="form-control" name="username" placeholder="Username">
          </div>
          <div class="form-group">
            <label for="email"><span class="text-danger">*Email地址(登入使用)</span>:</label>
            <input v-model="user.email" type="email" class="form-control" name="email" placeholder="Email" v-validate:email="{ required: true }">
          </div>
          <div class="form-group">
            <label for="pid"><span class="text-danger">*身份證字號</span>:</label>
            <input v-model="user.pid" class="form-control" name="pid" placeholder="PID" style="text-transform: uppercase;" v-validate:pid="{ required: true, minlength: 10, maxlength: 10 }">
          </div>
          <div class="form-group">
            <label for="biography"><span class="text-danger">*個人職務</span>:</label>
            <textarea v-model="user.bio" class="form-control" name="bio" rows="5" id="biography"></textarea>
          </div>
          <div class="form-group">
            <label for="patient"><span class="text-danger">*使用權限</span>:</label>
            <label for="patient" class="text-danger bg-danger">病患:</label>
            <input v-model="user.role_level" type="radio" name="patient" value="0" class="bg-danger">、
            <label for="patient" class="text-success bg-success">一般使用者:</label>
            <input v-model="user.role_level" type="radio" name="patient" value="1" class="bg-success">、
            <label v-if="isManager" for="patient" class="text-info bg-info">機構管理者:</label>
            <input v-if="isManager" v-model="user.role_level" type="radio" name="patient" value="7" class="bg-info">
            <!-- input v-if="isAdmin" v-model="user.role_level" type="radio" name="patient" value="9" class="bg-info" -->
            <!-- label v-if="isAdmin" for="patient" class="text-info bg-info">系統管理者:</label -->
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="button" @click="updateUser(user)" class="btn btn-lg btn-primary btn-flat pull-right" v-if="$validation.valid"><i class="fa fa-cloud" aria-hidden="true"></i> 存</button>
          <button type="button" @click="deleteUser(user)" class="btn btn-lg btn-danger btn-flat"><i class="fa fa-trash-o" aria-hidden="true"></i> 刪</button>
        </div>
        <div class="errors">
          <div class="callout callout-danger lead">
            <h4><p v-if="$validation.password.required">請輸入...密碼!!</p></h4>
            <h4><p v-if="$validation.password.minlength">請注意...密碼長度至少6位!!</p></h4>
            <h4><p v-if="$validation.password.maxlength">請注意...密碼的長度最多20位啦!!</p></h4>
            <h4><p v-if="$validation.pid.required">請輸入...身份證字號!!</p></h4>
            <h4><p v-if="$validation.pid.minlength">請注意...身份證字號長度至少10位!!</p></h4>
            <h4><p v-if="$validation.pid.maxlength">請注意...身份證字號長度最多10位啦!!</p></h4>
            <h4><p v-if="$validation.hosp_id.required">請輸入...醫事機構代碼!!</p></h4>
            <h4><p v-if="$validation.email.required">請輸入...Email地址!!</p></h4>
          </div>
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
    created () {
      this.userId = this.$route.params.hashid
      this.fetchUser()
    },
    ready () {
      this.fetchOwnUser()
    },
    data () {
      return {
        token: csrf_token,
        userowner: {},
        userId: '',
        user: [],
        patient: ''
      }
    },
    methods: {
      fetchOwnUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
//              this.$http({url: 'logout', method: 'GET'}).then(function (response) {
                window.location.assign('/auth/logout')
//              })
            }
          })
      },
      fetchUser () {
        this.$http({url: '/api/users/' + this.userId + '/edit', method: 'GET'})
        .then(function (response) {
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
        })
      },
      updateUser (user) {
        //event.preventDefault();
        this.$http.patch('/api/users/' + this.userId, user).then(function (response) {
          show_stack_success('使用者資料已更新!', response)
        }, function (response) {
          show_stack_error('使用者資料更新失敗!', response)
        })
        this.$router.go('/')
      },
      deleteUser (user) {
        let self = this
        swal({
          title: '確定嗎?!',
          text: '您將無法救回此使用者!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除!!',
          cancelButtonText: '不, 取消!!',
        }).then(function () {
          self.$http.delete('/api/users/' + user.hashid, user).then(function (response) {
            self.$router.go('/users')
            swal(
              '已刪除!!',
              '使用者已經刪除!!',
              'success'
            );
          }, function (response) {
            show_stack_error('刪除使用者發生錯誤!!', response)
          })
        }, function (dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '使用者維持現狀 :)',
              'error'
            );
          }
        });
      },
    },
    computed: {
      isAdmin: function () {
        return (this.userowner.role_level >= 9)
      },
      isManager: function () {
        return (this.userowner.role_level >= 7)
      },
    }
  }
</script>
