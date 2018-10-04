<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>基本資料管理</h4>
      <h5><p>
        您可以在此管理::[<b>基本資料</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除、清除</b>...等
      </p></h5>
    </div>

    <!-- general form elements -->
    <div class="box box-primary">
      <!-- /.box-header -->
      <div class="bs-example">
        <!-- div class="row" -->
          <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
            <!-- p><pre>Valid user data : {{-- valid.query --}}</pre></p -->
          <!-- /div -->
          <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
            <!-- p><pre>Valid direction : {{-- valid.create --}}</pre></p -->
          <!-- /div -->
          <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
            <!-- p><pre>All valid : {{-- valid.all --}}</pre></p -->
          <!-- /div -->
        <!-- /div -->

        <div class="container-fluid">
        <div class="row">
          <form-group :valid.sync="valid.all">
            <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
              <tabs :active.sync="active">
                <tab header="基本資料-查詢">
                  <form-group :valid.sync="valid.query">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="hosp_id"><span class="text-danger">*醫事機構代碼</span></label>
                      <div class="col-xs-7">
                        <bs-input name="hosp_id" :value.sync="basis.hosp_id" disabled></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                      <div class="col-xs-7">
                        <bs-input name="pid" :value.sync="basis.pid" pattern="^[A-Z][1-2][0-9]+$" maxlength="10" :mask="mask" placeholder="請輸入...身份證字號" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="birthday"><span class="text-danger">*出生日期</span></label>
                      <div class="col-xs-7">
                        <bs-input name="birthday" :value.sync="basis.birthday" pattern="^[0-9]+$" maxlength="7" placeholder="請輸入...生日(民國 3位數, 例: 0501231)" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                  </form-group>
                  <div class="col-xs-12 col-xs-offset-3">
                    <button type="button" class="btn btn-warning" :disabled="!valid.query" @click="fetchCase(basis.pid)"><i class="fa fa-search"></i>本院診斷查詢</button>
                    <button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createBasis()"><i class="fa fa-plus-circle"></i>新增</button>
                    <button type="button" class="btn btn-info" :disabled="!valid.query" @click="clear2(basis)"><i class="fa fa-eraser"></i>清除</button>
                    <button type="button" class="btn btn-success" v-link="{ name: 'importbasis', params: { mcode: userowner.hosp_id } }">資料匯入</button>
                  </div>
                  <div class="col-xs-12"><hr></div>
                  <form-group :valid.sync="valid.query">
                    <div class="col-xs-10">
                    <accordion one-at-atim type="warning">
                      <panel is-open type="warning">
                        <strong slot="header"><u>診斷查詢</u></strong>
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>診斷別</th>
                                <th>建檔時間</th>
                                <th>結案時間</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="case in cases">
                                <td>{{ case.type }}</td>
                                <td>{{ case.createdate }}</td>
                                <td>{{ (case.close_date) ? case.close_date : "未結案" }}</td>
                              </tr>
                              <!-- tr v-else -->
                              <!-- td colspan="3">無資料</td -->
                              <!-- /tr -->
                            </tbody>
                          </table>
                      </panel>
                    </accordion>
                    </div>
                  </form-group>
                </tab>
                <tab header="基本資料-新增" :disabled="!disablequery">
                  <form-group :valid.sync="valid.create">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="medical_number">病歷號碼</label>
                      <div class="col-xs-7">
                        <bs-input name="medical_number" :value.sync="basis.medical_number"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="name"><span class="text-danger">*姓名</span></label>
                      <div class="col-xs-7">
                        <bs-input name="name" :value.sync="basis.name" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="sex"><span class="text-danger">*性別</span></label>
                      <div class="col-xs-7">
                        <v-select name="sex" :options="['男','女']" :value.sync="basis.sex" clear-button required></v-select>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="tel1">住家電話1</label>
                      <div class="col-xs-7">
                        <bs-input name="tel1" :value.sync="basis.tel1"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="tel2">住家電話2</label>
                      <div class="col-xs-7">
                        <bs-input name="tel2" :value.sync="basis.tel2"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="company_tel">公司電話</label>
                      <div class="col-xs-7">
                        <bs-input name="company_tel" :value.sync="basis.company_tel"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="mobile1">行動電話1</label>
                      <div class="col-xs-7">
                        <bs-input name="mobile1" :value.sync="basis.mobile1"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="mobile2">行動電話2</label>
                      <div class="col-xs-7">
                        <bs-input name="mobile2" :value.sync="basis.mobile2"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="address">地址</label>
                      <div class="col-xs-7">
                        <bs-input name="address" :value.sync="basis.address"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="emergency_contact">緊急聯絡人</label>
                      <div class="col-xs-7">
                        <bs-input name="emergency_contact" :value.sync="basis.emergency_contact"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="emergency_relationship">緊急聯絡人關係</label>
                      <div class="col-xs-2">
                        <v-select :options="['夫妻','父子','父女','母子','母女','祖孫','同居人','朋友','主僱','其他']" :value.sync="basis.emergency_relationship" clear-button></v-select>
                      </div>
                      <div class="col-xs-5">
                        <bs-input name="emergency_relationship_other" :value.sync="basis.emergency_relationship_other" placeholder="若拉選[其他], 請填寫於此..."></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="emergency_tel">緊急聯絡人住家電話</label>
                      <div class="col-xs-7">
                        <bs-input name="emergency_tel" :value.sync="basis.emergency_tel"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="emergency_mobile">警急聯絡人行動電話</label>
                      <div class="col-xs-7">
                        <bs-input name="emergency_mobile" :value.sync="basis.emergency_mobile"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <label class="control-label col-xs-2 col-xs-offset-1" for="language">語言</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="basis.language" buttons="false">
                        <p><pre>Values: {{ basis.language }}</pre></p>
                        <v-checkbox value="國語" type="warning">國語</v-checkbox>
                        <v-checkbox value="台語" type="warning">台語</v-checkbox>
                        <v-checkbox value="客語" type="warning">客語</v-checkbox>
                        <v-checkbox value="原住民語" type="success">原住民語</v-checkbox>
                        <v-checkbox value="美(英)語" type="success">美(英)語</v-checkbox>
                        <v-checkbox value="越語" type="success">越語</v-checkbox>
                        <v-checkbox value="泰語" type="success">泰語</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="其他">其他</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="language_other" :value.sync="basis.language_other"></bs-input>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    <label class="control-label col-xs-2 col-xs-offset-1" for="drug_allergy"><span class="text-danger">*藥物過敏史</span></label>
                    <div class="col-xs-7">
                      <p><pre>Values: {{ basis.drug_allergy }}</pre></p>
                      <button-group :value.sync="basis.drug_allergy" buttons="false" required>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="Pyrine" type="warning" :disabled="isNone">Pyrine</v-checkbox>
                        <v-checkbox value="NSAID" type="warning" :disabled="isNone">NSAID</v-checkbox>
                        <v-checkbox value="Penicillin" type="warning" :disabled="isNone">Penicillin</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="胰島素" type="warning" :disabled="isNone">胰島素</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="drug_allergy_insulin" :value.sync="basis.drug_allergy_insulin" :disabled="isNone"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="其他" type="danger" :disabled="isNone">其他</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="drug_allergy_other" :value.sync="basis.drug_allergy_other" :disabled="isNone"></bs-input>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </form-group>
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-warning" :disabled="!valid.all" @click="updateBasis(basis)"><i class="fa fa-pencil-square-o"></i>修改</button>
                    <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteBasis(basis)"><i class="fa fa-minus-circle"></i>刪除</button>
                  </div>
                </tab>
              </tabs>
            <!-- /div -->
          </form-group>
        </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /.box -->
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

//  import { buttonGroup, bsInput, tab, tabs, vSelect, vCheckbox, vOption, accordion, panel } from 'vue-strap/dist/vue-strap.min'
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
      panel
    },
    created () {
      this.fetchUser()
      this.fetchCache()
    },
    ready () {
      // this.fetchCase()
    },
    data () {
      return {
        token: csrf_token,
        active: 0,
        disablequery: 0,
        valid: {},
        userowner: {},
        basis: {},
        cases: {}
      }
    },
    methods: {
      mask (value) {
        return value.toUpperCase()
      },
      fetchUser () {
        //登入者
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
            this.$set('basis.hosp_id', response.data.hosp_id)
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
        this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('basis', response.data)
  //          alert(this.basis.id)
          })
          .catch(function(response) {
            console.log(response)
          })
      },
      fetchCache () {
        this.$http({url: '/api/basiscache/', method: 'GET'})
          .then(function (response) {
            this.$set('basis.pid', response.data.pid)
            this.$set('basis.birthday', response.data.birthday)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      fetchCase (pid) {
        // var pid = this.basis.pid
        // var pid = $('input[name="pid"]').val()
        // alert($('input[name="pid"]').val())
        // event.preventDefault()
        // let caseid = null
        // this.$http({url: '/api/basiscache/', method: 'GET'})
        //  .then(function (response) {
        //    this.$set('basis.pid', response.data.pid)
        //    caseid = this.basis.pid
        //  })
        // alert(caseid)
        // if ($('input[name="pid"]').val()) {
        // if ($('input[name="pid"]').val()) {
        if (pid) {
          // this.$http({url: '/api/basis/listcase/' + $('input[name="pid"]').val(), method: 'GET'})
          this.$http({url: '/api/basis/listcase/' + pid, method: 'GET'})
            .then(function (response) {
  //            console.log(response.data[0])
              if (response.data.length == 0) {
                this.$set('cases', [{ type: '無資料', createdate: '', close_date: '' }])
              } else {
                this.$set('cases', response.data)
              }
            })
            .catch(function (response) {
              console.log(response)
            })
        }
      },
      deleteBasis (basis) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名使用者的基本資料!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.basis.$remove(basis)
          self.$http.delete('/api/basis/' + basis.id, basis).then(function (response) {
            self.$router.go('/basis')
            swal(
              '已刪除!!',
              '該名使用者的基本資料已經刪除!!',
              'success'
            )
            this.active = 0
            this.disablequery = 0
          }, function (response){
            show_stack_error('刪除使用者的基本資料發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名使用者的基本資料維持現狀 :)',
              'error'
            )
          }
        })
      },
      createBasis () {
        // this.$http({url: '/api/basis', method: 'POST'}).then(function (response) {
        // this.$router.go('/basis/' + response.data.id + '/edit')
        // this.$set('basis', response.data)
        // console.log(response)
        // this.$set('basis.language', JSON.stringify(this.basis.language))
        // 去除language的comma
        // this.$set('basis.language', langarray.toString().replace(',', ' '))

        if (confirm("確定新增嗎?!")) {
          //身份證檢覈
          var chkpid = $('input[name="pid"]').val()
          if(!this.checkTWID(chkpid)) {
  //          show_stack_error('身份證檢覈不符, 請檢查[身份證]是否輸入錯誤!!', response)
            this.valid.query = false
            return false
          }
          //出生日期檢覈
          if(!this.checkBirthday()) {
  //          show_stack_error('出生日期檢覈不符, 請檢查[出生日期]是否輸入錯誤!!', response)
            this.valid.query = false
            return false
          }

          this.active = 0
          this.disablequery = 0
          var input = this.basis
          this.$http.post('/api/basis', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              // console.log(response.data.message)

              var pid = $('input[name="pid"]').val()
    //          this.fetchPid(pid)
              this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
                .then(function (response) {
                  this.$set('basis', response.data)
                })
                .catch(function(response) {
                  console.log(response)
                })
              this.active = 1
              this.disablequery = 1
            }, function (response) {
              show_stack_error('新增資料失敗!!', response)
              // console.log(response.data.error)

              if (response.data.error.message == "The pid has already been taken.") {
                show_stack_error('該基本資料已新增, 可修改!!', response)
                var pid = $('input[name="pid"]').val()
  //              this.fetchPid(pid)
                this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
                  .then(function (response) {
                    this.$set('basis', response.data)
                  })
                  .catch(function(response) {
                    console.log(response)
                  })
                this.active = 1
                this.disablequery = 1
              }
            })
            .catch(function(response) {
              console.log(response)
            })
        }
      },
      updateBasis (basis) {
  //      event.preventDefault()
  //      alert(basis.id)
        this.$http.patch('/api/basis/' + basis.id, basis)
          .then(function (response) {
            show_stack_success('使用者的基本資料已更新!', response)
  //          alert(JSON.stringify(response))
          }, function (response) {
            show_stack_error('使用者的基本資料更新失敗!', response)
  //          alert(response.data.error.message)
          })
          .catch(function(response) {
            console.log(response)
          })
        this.$router.go('/')
      },
      clear2 (basis) {
        //刪除暫存
        this.$http.delete('/api/basiscache/1', basis)
          .then(function (response) {
            show_stack_success('使用者的輸入資料(暫存)已刪除!', response)
            $('input[name="pid"]').val('')
            $('input[name="birthday"]').val('')
          }, function (response) {
            show_stack_success('使用者的輸入資料(暫存)刪除失敗!', response)
            console.log(response.data.message)
          })
          .catch(function(response) {
            console.log(response)
          })
      },
      checkTWID (id) {
        // 台灣身份證檢查
        // 建立字母分數陣列(A~Z)
        var city = new Array(
          1,10,19,28,37,46,55,64,39,73,82,2,11,
          20,48,29,38,47,56,65,74,83,21,3,12,30
        )
        id = id.toUpperCase()
        // 使用「正規表達式」檢驗格式
        // if (id.search(/^[A-Z](1|2)\d{8}$/i) == -1) {
        // 身份證的正規表示式
        var reg = /^[A-Z]{1}[1-2]{1}[0-9]{8}$/
        if(reg.test(id)) {
          // 將字串分割為陣列(IE必需這麼做才不會出錯)
          id = id.split('')
          // 計算總分
          var total = city[id[0].charCodeAt(0) - 65]
          for(var i = 1; i <= 8; i++) {
            total += eval(id[i]) * (9 - i)
          }
          // 補上檢查碼(最後一碼)
          total += eval(id[9])
          // 檢查比對碼(餘數應為0)
          if (total % 10 == 0) {
            return true
          } else {
            alert('*  身份證號驗證計算錯誤！')
            return false
          }
        } else {
          alert('*  身份證號基本格式錯誤！')
          return false
        }
      },
      checkBirthday () {
        //檢查出生日期格式是否正確
        var errmsg = ""
        var date = $('input[name="birthday"]').val()
        date = date.replace(/ /g, "")
        date = date.replace(/　/g, "")
        if (date.length < 7)
          errmsg = errmsg + "*  出生日期長度不符\n"
        // returns the current year
        var thisyear = new Date().getFullYear()
        thisyear = thisyear - 1911
        var TodayDate = new Date()
        var thismonth = TodayDate.getMonth() + 1
        if (date.substr(0, 3) > thisyear)
          errmsg = errmsg + "*  出生日期: 年份不可為" + date.substr(0, 3) + "，今年為民國" + thisyear + "年\n"
        if (date.substr(0, 3) == thisyear && date.substr(3, 2) > thismonth)
          errmsg = errmsg + "*  出生日期: 年份為" + date.substr(0, 3) + "，月份不可大於" + date.substr(3, 2) + "\n"
        if (date.substr(3, 2) > '12')
          errmsg = errmsg + "*  出生日期: 月份不可為" + date.substr(3, 2) + "\n"
        if (date.substr(5, 2) > '31')
          errmsg = errmsg + "*  出生日期: 日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) > '30' && (date.substr(3, 2) != '01' && date.substr(3, 2) != '03' && date.substr(3, 2) != '05' && date.substr(3, 2) != '07' && date.substr(3, 2) != '08' && date.substr(3, 2) != '10' && date.substr(3, 2) != '12'))
          errmsg = errmsg + "*  出生日期: 小月日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) > '29' && date.substr(3, 2) == '02')
          errmsg = errmsg + "*  出生日期: 2月份日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) == '29' && date.substr(3, 2) == '02' && ((parseInt(date.substr(0, 3), 10) + 1911) % 4) != 0)
          errmsg = errmsg + "*  出生日期: 非閏年，日期不可為" + date.substr(5, 2) + "\n"
        if (errmsg != "") {
          errmsg = errmsg + "*  以上欄位錯誤！"
          alert(errmsg)
          return false
        } else {
          return true
        }
      },
  //    show (value) {
  //      return value instanceof Array ? value.join(', ') : value
  //    }
    },
    computed: {
  //    isAdmin: function () {
  //      return (this.userowner.role_level >= 7)
  //    },
      isNone: function () {
        if (this.basis.drug_allergy != null) {
          var allergys = this.basis.drug_allergy || ""
          var a = allergys.indexOf("無")
          if (a >= 0) {
            this.basis.drug_allergy_insulin = ""
            this.basis.drug_allergy_other = ""
            var items = ['Pyrine', 'NSAID', 'Penicillin', '胰島素', '其他']
            var ax
            items.forEach(function(item) {
              ax = allergys.indexOf(item)
              if (ax !== -1) {
                allergys.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      }
  //    isHas: function () {
  //      if (this.basis.drug_allergy != null) {
  //        var allergys = this.basis.drug_allergy || ""
  //        var items = ['Pyrine', 'NSAID', 'Penicillin', '胰島素', '其他']
  //        var a = null
  //        items.forEach(function(item) {
  //          a = allergys.indexOf(item)
  //          if (a >= 0) {
  //            var ax
  //            if ((ax = allergys.indexOf("無")) !== -1) {
  //              allergys.splice(ax, 1)
  //            }
  //          }
  //        })
  //        return (a >= 0)
  //      }
  //    }
    }
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
</style>
