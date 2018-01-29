<template>
  <!-- Display Validation Errors -->

  <!-- general form elements -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">編輯VPN上傳資料</h3>
    </div>
    <!-- /.box-header -->
    <!-- vue validator start -->
    <validator name="validation">
      <!-- form start -->
      <form role="form" novalidate>
        <div class="box-body">
          <div class="form-group">
            <label for="branch_code"><span class="text-danger">*業務組別</span>:</label>
            <div class="btn-group btn-group-justified">
              <v-select name="branch_code" :options="branchs" :value.sync="doctor.branch_code" :search="true" :close-on-select="true">
              </v-select>
            </div>
          </div>
          <div class="form-group">
            <label for="hosp_id"><span class="text-danger">*醫事機構代碼</span>:</label>
            <input v-model="doctor.hosp_id" class="form-control" name="hosp_id" placeholder="Medical Code" maxlength="10" v-validate:hosp_id="{ required: true, maxlength: 10 }">
          </div>
          <div class="form-group">
            <label for="name"><span class="text-danger">*姓名</span>:</label>
            <input v-model="doctor.name" class="form-control" name="name" placeholder="Name" v-validate:name="{ required: true }">
          </div>
          <div class="form-group">
            <label for="pid"><span class="text-danger">*身分證號</span>:</label>
            <input v-model="doctor.pid" class="form-control" name="pid" placeholder="PID" maxlength="10" v-validate:pid="{ required: true, maxlength: 10 }">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="button" @click="updateDoctor(doctor)" class="btn btn-lg btn-primary btn-flat pull-right" v-if="$validation.valid"><i class="fa fa-cloud" aria-hidden="true"></i> 存</button>
          <button type="button" @click="deleteDoctor(doctor)" class="btn btn-lg btn-danger btn-flat"><i class="fa fa-trash-o" aria-hidden="true"></i> 刪</button>
        </div>
        <div class="errors">
          <div class="callout callout-danger lead">
            <h4><p v-if="$validation.hosp_id.required">請輸入...醫事機構代碼!!</p></h4>
            <h4><p v-if="$validation.hosp_id.maxlength">請注意...醫事機構代碼的長度最多10位啦!!</p></h4>
            <h4><p v-if="$validation.name.required">請輸入...姓名!!</p></h4>
            <h4><p v-if="$validation.pid.required">請輸入...身分證號!!</p></h4>
            <h4><p v-if="$validation.pid.maxlength">請注意...身分證號的長度最多10位啦!!</p></h4>
          </div>
        </div>
      </form>
    </validator>
  </div>
  <!-- /.box -->
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

//  import buttonGroup from './vue-strap/src/buttonGroup.vue'
  import vSelect from './vue-strap/src/Select.vue'
  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

  export default {
    components: {
//      buttonGroup,
      vSelect
    },
    created () {
      this.doctorId = this.$route.params.doctorid
      this.fetchDoctor()
    },
    ready () {
      this.fetchOwnUser()
    },
    data () {
      return {
        token: csrf_token,
        userowner: {},
        doctorId: '',
        doctor: [],
        branchs: [
          {value: '1', label: '臺北業務組'},
          {value: '2', label: '北區業務組'},
          {value: '3', label: '中區業務組'},
          {value: '4', label: '南區業務組'},
          {value: '5', label: '高屏業務組'},
          {value: '6', label: '東區業務組'}
        ]
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
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchDoctor () {
        this.$http({url: '/api/doctors/' + this.doctorId + '/edit', method: 'GET'})
        .then(function (response) {
          // success callback
          this.$set('doctor', response.data)
        }, function (response) {
          // error callback
          console.log(response)
          if (response.data == "Unauthorized." && response.status == 401) {
            alert('Auto Logout after idle for 20 mins!!')
            window.location.assign('/auth/logout')
          }
        })
      },
      updateDoctor (doctor) {
        // event.preventDefault();
        this.$http.patch('/api/doctors/' + this.doctorId, doctor).then(function (response) {
          // console.log(response.data.message)
          show_stack_success('醫師資料已更新!', response)
        }, function (response) {
          show_stack_error('醫師資料更新失敗!', response)
        })
        this.$router.go('/doctors')
      },
      deleteDoctor (doctor) {
        let self = this
        swal({
          title: '確定嗎?!',
          text: '您將無法救回該員醫師!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除!!',
          cancelButtonText: '不, 取消!!',
        }).then(function () {
          self.$http.delete('/api/doctors/' + doctor.hashid, doctor).then(function (response) {
            self.$router.go('/doctors')
            swal(
              '已刪除!!',
              '該員醫師已經刪除!!',
              'success'
            );
          }, function (response) {
            show_stack_error('刪除醫師發生錯誤!!', response)
          })
        }, function (dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該員醫師維持現狀 :)',
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
