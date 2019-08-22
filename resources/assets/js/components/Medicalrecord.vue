<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>衛教記錄(病歷查詢)</h4>
      <h5><p>
        您可以在此管理::[<b>個案管理</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除</b>...等
      </p></h5>
    </div>

    <!-- general form elements -->
    <div class="box box-primary">
      <!-- /.box-header -->
      <div class="bs-example">
      <!-- div class="row" -->
        <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
          <!-- p><pre>Valid query : {{-- valid.query --}}</pre></p -->
        <!-- /div -->
        <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
          <!-- p><pre>Valid create : {{-- valid.create --}}</pre></p -->
        <!-- /div -->
        <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
          <!-- p><pre>Valid closed : {{-- valid.closed --}}</pre></p -->
        <!-- /div -->
        <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
          <!-- p><pre>All valid : {{-- valid.all --}}</pre></p -->
        <!-- /div -->
      <!-- /div -->
        <div class="container-fluid">
        <div class="row">
          <form-group :valid.sync="valid.all">
            <tabs :active.sync="actives">
              <tab header="衛教記錄-查詢">
                <form-group :valid.sync="valid.query">
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="hosp_id"><span class="text-danger">*醫事機構代碼</span></label>
                    <div class="col-xs-7">
                      <bs-input name="hosp_id" :value.sync="qrecords.hosp_id" disabled></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                    <div class="col-xs-7">
                      <bs-input name="pid" :value.sync="qrecords.pid" maxlength="10" :mask="mask" placeholder="請輸入...身份證字號" required></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </form-group>
                <div class="col-xs-12 col-xs-offset-3">
                  <button type="button" class="btn btn-warning" :disabled="!valid.query" @click="fetchMedicalrecord(qrecords.pid)"><i class="fa fa-search"></i>查詢</button>
                  <!-- button type="button" class="btn btn-primary" :disabled="!isCreateMrecord" @click="createMrecords()"><i class="fa fa-plus-circle"></i>新增</button -->
                </div>
                <div class="col-xs-12"><br></div>
              </tab>
              <tab header="衛教記錄-病歷清單" :disabled="!disablequery">
                <form-group :valid.sync="valid.query">
                  <div id="printpage" class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                      <tr>
                        <th>
                          <!-- a href="#" @click="sortBy('name')" :class="{ active: sortKey == 'name' }" -->病患姓名
                            <!-- i class="fa {{ faSortname }}" aria-hidden="true"></i -->
                          <!-- /a -->
                        </th>
                        <th>
                          <!-- a href="#" @click="sortBy('pid')" :class="{ active: sortKey == 'pid' }" -->身分證號
                            <!-- i class="fa {{ faSortpid }}" aria-hidden="true"></i -->
                          <!-- /a -->
                        </th>
                        <th>血糖更新日</th>
                        <th>個管人員</th>
                        <th>SOAP衛教師</th>
                        <th>功能</th>
                      </tr>
                      <!-- tr v-for="mrecord in medicalrecords" -->
                      <tr v-for="mlrecord in medicalrecords | orderBy sortKey reverse">
                        <td>{{ mlrecord.name }}</td>
                        <td>{{ mlrecord.pid }}</td>
                        <td>{{ mlrecord.mrdate }}</td>
                        <td>{{ mlrecord.ename }}</td>
                        <td>{{ mlrecord.sname }}</td>
                        <td class="col-md-4">
                          <div class="btn-group">
                            <button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createMedicalrecords(mlrecord)"><!-- i class="fa fa-plus-circle"></i -->加入SOAP</button>
                            <button type="button" id="soapsButton" @click="soapsDate(mlrecord.pid)" class="btn btn-info btn-flat"><!-- i class="fa fa-compress" aria-hidden="true"></i -->SOAP歷史</button>
                            <!-- button type="button" class="btn btn-danger" @click="deleteMrecord(mrecord, $event)">歸檔</button -->
                          </div>
                        </td>
                      </tr>
                    </table>
                    <!-- div -->
                      <!-- v-paginator :resource.sync="medicalrecords" :resource_url="resource_url" :options="options"></v-paginator -->
                    <!-- /div -->
                  </div>
                </form-group>
              </tab>
            </tabs>
          </form-group>
        </div>
        </div>
      </div>
      <!-- /.box-body -->

      <div class="bs-example">
        <sidebar :show.sync="soapsRight" placement="right" header="SOAP歷史" :width="350">
          <table class="table table-striped">
            <thead>
              <tr>
                <th bgcolor="#ffffb8">日期</th>
                <th>功能</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="soap in listdate">
                <td bgcolor="#ffffb8">{{ soap.soap_date }}</td>
                <td>
                  <button v-link="{ name: 'showmresult', params: { soapsPid: soap.id } }" type="button" class="btn btn-info">修改</button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="aside-footer">
            <button type="button" class="btn btn-default" @click="soapsRight = false">Close</button>
          </div>
        </sidebar>
      </div>

      <div class="box-footer">
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /.box -->
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import formGroup from './vue-strap/src/FormGroup.vue'
  import buttonGroup from './vue-strap/src/buttonGroup.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import tab from './vue-strap/src/Tab.vue'
  import tabs from './vue-strap/src/Tabset.vue'
  import vSelect from './vue-strap/src/Select.vue'
  import vCheckbox from './vue-strap/src/Checkbox.vue'
  import vOption from './vue-strap/src/Option.vue'
  import accordion from './vue-strap/src/Accordion.vue'
  import panel from './vue-strap/src/Panel.vue'
  import radio from './vue-strap/src/Radio.vue'
  import datepicker from './vue-strap/src/Datepicker.vue'
  import modal from './vue-strap/src/Modal.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import sidebar from './vue-strap/src/Aside.vue'
  import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      formGroup,
      buttonGroup,
      bsInput,
      tab,
      tabs,
      vSelect,
      vCheckbox,
      vOption,
      accordion,
      panel,
      radio,
      datepicker,
      modal,
      Multiselect,
      sidebar,
      VPaginator: VuePaginator,
      sortKey: '',
      reverse: 1,
      faSortname: 'fa-sort',
      faSortpid: 'fa-sort'
    },
    created () {
      // this.fetchToday()
      this.fetchUser()
      this.fetchEducator()
    },
    ready () {
      // this.fetchMrecords()
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        thispage: 20,
        actives: 0,
        disablequery: 0,
        disablemquery: 0,
        disablecreate: 0,
        showModal: false,
        valid: {
          query: false,
          create: false,
          closed: false,
          all: false
        },
        userowner: {},
        medicalrecords: {},
        mrecords: {},
        listdate: {},
        qrecords: {},
        educators: [],
        today: '',
        soapsRight: false
      }
    },
    methods: {
      mask (value) {
        return value.toUpperCase()
      },
//      fetchToday () {
//        //today
//        var date = new Date()
//        var thisday = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
//        this.today = thisday
//        this.$set('mrecords.start_date', thisday)
//        this.$set('mrecords.end_date', thisday)
//        this.$set('mrecords.track_date', thisday)
//      },
      fetchUser () {
        //登入者
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
            this.$set('qrecords.hosp_id', response.data.hosp_id)
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
      fetchPid (pid) {
        this.$http({url: '/api/mrecords/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('mrecords', response.data)
          })
          .catch(function(response) {
            console.log(response)
          })
      },
      fetchEducator () {
        this.$http({url: '/api/users/educator/', method: 'GET'})
          .then(function (response) {
            this.$set('educators', response.data)
            // this.$set('educators', JSON.stringify(response.data))
            // console.log(JSON.stringify(response.data))
            // console.log(response.data[1].username)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
//      fetchMrecords () {
//        this.$http({url: '/api/mrecords/showpage1/' + this.thispage, method: 'GET'})
//          .then(function (response) {
//            // console.log(response.data)
//            this.$set('mrecords', response.data.data)
//          })
//          .catch(function(response) {
//            console.log(response)
//            if (response.data == "Unauthorized." && response.status == 401) {
//              alert('Auto Logout after idle for 20 mins!!')
//              window.location.assign('/auth/logout')
//            }
//          })
//      },
      fetchMedicalrecord (pid) {
        // var pid = this.medicalrecords.pid
        // var pid = $('input[name="pid"]').val()
        // alert($('input[name="pid"]').val())
        // event.preventDefault()
        // let mrecordid = null
        // this.$http({url: '/api/mrecordscache/', method: 'GET'})
        //  .then(function (response) {
        //    this.$set('medicalrecords.pid', response.data.pid)
        //    mrecordid = this.medicalrecords.pid
        //  })
        // alert(mrecordid)
        // if ($('input[name="pid"]').val()) {
        // console.log(pid)
        if (pid) {
          // 先確認 基本資料 是否已存在
          this.$http({url: '/api/mrecords/listdata/' + pid, method: 'GET'})
            .then(function (response) {
              // console.log(response.data[0])
              // console.log(this.valid.query)
              // console.log(response.data.length)
              // console.log(response.data)
              // console.log(response.data.error.message)
              this.$set('medicalrecords', response.data)
              this.actives = 1
              // alert(this.medicalrecords.id)
              // this.disablecreate = 0
              // this.disablequery = 1
            })
        }
      },
      soapsDate(pid) {
        this.$http({url: '/api/soaps/listdate/' + pid, method: 'GET'})
          .then(function (response) {
            // console.log(response.data)
            if (response.data.length == 0) {
              this.$set('listdate', [{soap_date: '無資料'}])
            } else {
              this.$set('listdate', response.data)
            }
          })
          .catch(function (response) {
            // console.log(response)
          })
        this.soapsRight = true
      },
      deleteMrecords (mrecords) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名病患的個案管理!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.mrecords.$remove(mrecords)
          self.$http.delete('/api/mrecords/' + mrecords.id, mrecords).then(function (response) {
            self.$router.go('/mrecords')
            swal(
              '已刪除!!',
              '該名病患的個案管理已經刪除!!',
              'success'
            )
            this.actives = 0
            this.disablequery = 0
            this.showModal = false
          }, function (response){
            show_stack_error('刪除病患的個案管理發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名病患的個案管理維持現狀 :)',
              'error'
            )
          }
        })
      },
      createMedicalrecords (mlrecord) {
        var answer = confirm("確定新增 工作清單 嗎?!")
        if (answer) {
          this.active = 0
          this.disablemquery = 0
//          var input = this.medicalrecords
          this.$http.post('/api/mrecords', mlrecord)
            .then(function (response) {
              // console.log(response.data.message)
              show_stack_info('已新增', response)
//              var id = response.data.id
//              this.$http({url: '/api/mrecords/showid1/' + id, method: 'GET'})
//                .then(function (response) {
//                  this.$set('mrecords', response.data)
//                }, function (response) {
//                  show_stack_error('查詢 工作清單 失敗!', response)
//                })
              this.active = 1
              this.disablemquery = 1
              this.$router.go('/listmrecords')
            }, function (response) {
              show_stack_error('新增 工作清單 失敗!', response)
            })
        }
      },
      createMrecords () {
        var answer = confirm("確定新增個管嗎?!")
        if (answer) {
          this.actives = 0
          this.disablequery = 0
          var input = this.mrecords
          this.$http.post('/api/mrecords', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              var id = response.data.id
              this.$http({url: '/api/mrecords/showid1/' + id, method: 'GET'})
                .then(function (response) {
                  this.$set('mrecords', response.data)
                }, function (response) {
                  show_stack_error('查詢個案管理失敗!', response)
                })
              this.actives = 1
              this.disablequery = 1
            }, function (response) {
              show_stack_error('新增個案管理失敗!', response)
            })
        }
      },
      updateMrecords (mrecords) {
        this.$http.patch('/api/mrecords/' + mrecords.id, mrecords)
          .then(function (response) {
            // console.log(response)
            show_stack_success('病患的個案管理已更新!', response)
  //          alert(JSON.stringify(response))
          }, function (response) {
            show_stack_error('病患的個案管理更新失敗!', response)
  //          alert(response.data.error.message)
          })
          .catch(function(response) {
            console.log(response)
          })
        this.$router.go('/')
      },
      updateEducator (value) {
        this.mrecords.educator_user_id = value
      },
      nameWithid ({ id, username }) {
        return `${id} -[${username}]`
      },
      updateTrackReason (value) {
        this.mrecords.track_reason = value
      },
      updateDietPlan (value) {
        this.mrecords.dietplan = value
      },
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse;
        this.sortKey = sortKey;
        if (sortKey == 'name') {
          (this.reverse == 1) ? this.faSortname = "fa-sort-asc" : this.faSortname = "fa-sort-desc";
        } else {
          this.faSortname = 'fa-sort';
        }
        if (sortKey == 'pid') {
          (this.reverse == 1) ? this.faSortpid = "fa-sort-asc" : this.faSortpid = "fa-sort-desc";
        } else {
          this.faSortpid = 'fa-sort';
        }
      }
    },
    computed: {
//      resource_url: function () {
//        if (this.$route.path == '/mrecords/' + this.mrecordId) {
//          return '/api/mrecords/' + this.mrecordId
//        } else {
//          // return '/api/mrecords'
//          // next_page_url	"http://192.168.1.222:8000/api/mrecords/listdata/G120757749?page=2"
//          return '/api/mrecords/listdata/' + this.thispage
//        }
//      },
      isCloseMrecord: function () {
        if (this.valid.query == true && this.mrecords.dm == null && this.mrecords.undm == null && this.mrecords.offdm != null) {
          return false
        } else {
          return true
        }
        // this.mrecords.offdm != null && this.mrecords.offdm != ''
      },
      isCreateMrecord: function () {
        if (this.valid.query == true && this.disablecreate == 1) {
          return true
        } else {
          return false
        }
      }
    },
    watch: {
      disabled () {
        this.$refs.dp.getDateRange()
      },
      format (newV) {
        this.value = this.$refs.dp.stringify()
      }
    }
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
  a {
    font-weight: normal;
    color: blue;
  }
  a:active {
    font-weight: bold;
    color: black;
  }
</style>
