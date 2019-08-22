<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>健保方案(新增方案)管理</h4>
      <h5><p>
        您可以在此管理::[<b>健保方案</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除</b>...等
      </p></h5>
    </div>

    <!-- general form elements -->
    <div class="box box-primary">
      <!-- /.box-header -->
      <div class="bs-example">
        <!-- div class="row">
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <p><pre>Valid query : {{-- valid.query --}}</pre></p>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <p><pre>Valid create : {{-- valid.create --}}</pre></p>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <p><pre>Valid closed : {{-- valid.closed --}}</pre></p>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <p><pre>All valid : {{-- valid.all --}}</pre></p>
          </div>
        </div -->
        <div class="container-fluid">
        <div class="row">
          <form-group :valid.sync="valid.all">
            <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
              <tabs :active.sync="active">
                <tab header="健保方案-查詢">
                  <form-group :valid.sync="valid.query">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="hosp_id"><span class="text-danger">*醫事機構代碼</span></label>
                      <div class="col-xs-7">
                        <bs-input name="hosp_id" :value.sync="cases.hosp_id" disabled></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                      <div class="col-xs-7">
                        <bs-input name="pid" :value.sync="cases.pid" pattern="^[A-Z][1-2][0-9]+$" maxlength="10" :mask="mask" placeholder="請輸入...身份證字號" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                  </form-group>
                  <div class="col-xs-12 col-xs-offset-3">
                    <button type="button" class="btn btn-warning" :disabled="!valid.query" @click="fetchCase(cases.pid)"><i class="fa fa-search"></i>查詢</button>
                    <!-- button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createCases()"><i class="fa fa-plus-circle"></i>新增</button -->
                    <!-- button type="button" class="btn btn-success" v-link="{ name: 'importcases', params: { mcode: userowner.hosp_id } }">資料匯入</button -->
                  </div>
                  <div class="col-xs-12"><hr></div>
                  <form-group :valid.sync="valid.query">
                    <div class="col-xs-10">
                    <accordion one-at-atime type="warning">
                      <panel is-open type="warning">
                        <strong slot="header"><u>DM</u></strong>&nbsp;
                        <button type="button" class="btn btn-warning btn-flat" :disabled="!valid.query" @click="fetchDMCase(cases.pid)">查詢</button>
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>方案日期</th>
                                <th>項目</th>
                                <th>天數</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="list1 in listdm">
                                <td><div v-if="list1.citem == '今日'" style="color:red">{{ list1.cdate }}</div><div v-else>{{ list1.cdate }}</div></td>
                                <td>{{ list1.citem }}</td>
                                <td><!-- div v-if="list1.cday == 0">&nbsp;</div><div v-else -->{{ list1.cday }}<!-- /div --></td>
                              </tr>
                            </tbody>
                          </table>
                      </panel>
                    </accordion>
                    </div>
                    <div class="col-xs-10">
                      <accordion one-at-atim type="info">
                        <panel is-open type="info">
                          <strong slot="header"><u>CKD</u></strong>&nbsp;
                          <button type="button" class="btn btn-info btn-flat" :disabled="!valid.query" @click="fetchCKDCase(cases.pid)">查詢</button>
                          <table class="table table-striped">
                            <thead>
                            <tr>
                              <th>方案日期</th>
                              <th>項目</th>
                              <th>天數</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="list2 in listckd">
                              <td><div v-if="list2.citem == '今日'" style="color:red">{{ list2.cdate }}</div><div v-else>{{ list2.cdate }}</div></td>
                              <td>{{ list2.citem }}</td>
                              <td><!-- div v-if="list2.cday == 0">&nbsp;</div><div v-else -->{{ list2.cday }}<!-- /div --></td>
                            </tr>
                            </tbody>
                          </table>
                        </panel>
                      </accordion>
                    </div>
                    <div class="col-xs-10">
                      <accordion one-at-atim type="success">
                        <panel is-open type="success">
                          <strong slot="header"><u>DM無收案</u></strong>&nbsp;
                          <button type="button" class="btn btn-success btn-flat" :disabled="!valid.query" @click="fetchNONDMCase(cases.pid)">查詢</button>
                          <table class="table table-striped">
                            <thead>
                            <tr>
                              <th>方案日期</th>
                              <th>項目</th>
                              <th>天數</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="list3 in listnondm">
                              <td><div v-if="list3.citem == '今日'" style="color:red">{{ list3.cdate }}</div><div v-else>{{ list3.cdate }}</div></td>
                              <td>{{ list3.citem }}</td>
                              <td><!-- div v-if="list3.cday == 0">&nbsp;</div><div v-else -->{{ list3.cday }}<!-- /div --></td>
                            </tr>
                            </tbody>
                          </table>
                        </panel>
                      </accordion>
                    </div>
                  </form-group>
                  <div class="col-xs-12"><hr></div>
                  <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                      <label class="control-label" for="dm">請選擇: </label>
                      <v-select name="dm" :options="['DM初診','DM複診','DM年度','CKD初診','CKD初診+DM複診','CKD+DM複診','CKD複診+DM年度']" :value.sync="cases.dm" placeholder="健保方案" :parent="!cases.undm && !cases.offdm" clear-button search></v-select>
                      <v-select name="undm" :options="['非方案DM初診','非方案DM複診','非方案DM年度','一般']" placeholder="非健保方案" :value.sync="cases.undm" :parent="!cases.dm && !cases.offdm" clear-button search></v-select>
                      <!-- v-checkbox :checked.sync="cleardm">Clear</v-checkbox -->
                      <input type="checkbox" id="cleardm" @click="cleardm" />Clear
                    </div>
                    <div class="col-xs-3">
                      <button type="button" class="btn btn-primary" :disabled="isCreateCase" @click="createCases()"><i class="fa fa-plus-circle"></i>新增</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-5 col-xs-offset-4">
                      <v-select name="offdm" :options="['DM結案','CKD結案']" :value.sync="cases.offdm" placeholder="方案結案" :parent="!cases.dm && !cases.undm" clear-button search></v-select>
                    </div>
                    <div class="col-xs-2">
                      <button type="button" class="btn btn-danger" :disabled="isCloseCase" @click="showModal = true"><i class="fa fa-times-circle-o"></i>結案</button>
                    </div>
                  </div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><br></div>
                  <div class="col-xs-12"><hr></div>
                </tab>
                <tab header="健保方案-新增" :disabled="!disablequery">
                  <form-group :valid.sync="valid.create">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="case_date"><span class="text-danger">*收案日期</span></label>
                      <div class="col-xs-7">
                        <datepicker v-ref:dp :value.sync="cases.case_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="cure_stage"><span class="text-danger">*診療階段</span></label>
                      <div class="col-xs-7">
                        <bs-input name="cure_stage" :value.sync="cases.cure_stage" disabled></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="bp"><span class="text-danger">*血壓</span></label>
                      <div class="col-xs-2">
                        <bs-input name="base_sbp" :value.sync="cases.base_sbp" required></bs-input>
                      </div>
                      <div class="col-xs-1">
                        /
                      </div>
                      <div class="col-xs-2">
                        <bs-input name="base_ebp" :value.sync="cases.base_ebp" required></bs-input>
                      </div>
                      <div class="col-xs-2">
                        mmHg
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="pr"><span class="text-danger">*脈搏</span></label>
                      <div class="col-xs-5">
                        <bs-input name="pulse" :value.sync="cases.pulse" required></bs-input>
                      </div>
                      <div class="col-xs-2">
                        次/分
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="tall"><span class="text-danger">*身高</span></label>
                      <div class="col-xs-5">
                        <bs-input name="base_tall" :value.sync="cases.base_tall" required></bs-input>
                      </div>
                      <div class="col-xs-2">
                        cm
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="bw"><span class="text-danger">*體重</span></label>
                      <div class="col-xs-3">
                        <bs-input name="base_weight" :value.sync="cases.base_weight" required></bs-input>
                      </div>
                      <div class="col-xs-1">
                        kg
                      </div>
                      <div class="col-xs-3">
                        <v-checkbox :checked.sync="cases.noweight" value="無法測量" type="danger">無法測量</v-checkbox>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="acpc"><span class="text-danger">*血糖</span></label>
                      <div class="col-xs-2">
                        <radio :checked.sync="cases.blood_mne" value="早" type="info">早</radio>
                        <radio :checked.sync="cases.blood_mne" value="中" type="info">中</radio>
                        <radio :checked.sync="cases.blood_mne" value="晚" type="info">晚</radio>
                      </div>
                      <div class="col-xs-2">
                        <bs-input name="blood_acpc" :value.sync="cases.blood_acpc"></bs-input>
                      </div>
                      <div class="col-xs-1">
                        mg/dL
                      </div>
                      <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                    </div>
                    <div class="row">
                      <div class="col-xs-2 col-xs-offset-3">
                        <radio :checked.sync="cases.blood_ap" value="前" type="warning">前</radio>
                        <radio :checked.sync="cases.blood_ap" value="後" type="warning">後</radio>
                      </div>
                      <div class="col-xs-2">
                        <bs-input name="blood_mins" :value.sync="cases.blood_mins" :disabled="isNoneW"></bs-input>
                      </div>
                      <div class="col-xs-1">
                        分
                      </div>
                      <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="a1c"><span class="text-danger">*A1C</span></label>
                      <div class="col-xs-6">
                        <bs-input name="blood_hba1c" :value.sync="cases.blood_hba1c" required></bs-input>
                      </div>
                      <div class="col-xs-1">
                        %
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="smoking"><span class="text-danger">*抽菸</span></label>
                      <div class="col-xs-5">
                        <radio :checked.sync="cases.smoking" value="無">無</radio>
                      </div>
                      <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                    </div>
                    <div class="row">
                      <div class="col-xs-2 col-xs-offset-3">
                        <radio :checked.sync="cases.smoking" value="有" type="info">有</radio>
                      </div>
                      <div class="col-xs-3">
                        <div class="row">
                          <div class="col-xs-6">
                            <bs-input name="havesmoke" :value.sync="cases.havesmoke" :disabled="isNoneS"></bs-input>
                          </div>
                          <div class="col-xs-6">
                            支
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                    </div>
                    <div class="row">
                      <div class="col-xs-2 col-xs-offset-3">
                        <radio :checked.sync="cases.smoking" value="已戒" type="warning">已戒</radio>
                      </div>
                      <div class="col-xs-3">
                        <bs-input name="quitsmoke" :value.sync="cases.quitsmoke" :disabled="isNoneS"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                    </div>
                  </form-group>
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-warning" :disabled="!valid.create" @click="updateCases(cases)"><i class="fa fa-pencil-square-o"></i>修改</button>
                    <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteCases(cases)"><i class="fa fa-minus-circle"></i>刪除</button>
                  </div>
                </tab>
              </tabs>
              <!-- Modal -->
              <modal title="健保方案-結案" :show.sync="showModal">
                <div slot="modal-header" class="modal-header">
                  <h4 class="modal-title">健保方案-<b>結案</b></h4>
                </div>
                <div slot="modal-body" class="modal-body">
                  <form-group>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="close_date"><span class="text-danger">*結案日期</span></label>
                      <div class="col-xs-7">
                        <datepicker v-ref:dp :value.sync="cases.close_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                  </form-group>
                </div>
                <div slot="modal-footer" class="modal-footer">
                  <button type="button" class="btn btn-default" @click='showModal = false'>取消</button>
                  <!-- button type="button" class="btn btn-success" @click='showModal = false'>Custom Save</button -->
                  <button type="button" class="btn btn-warning" @click="closeCases()"><i class="fa fa-pencil-square-o"></i>結案</button>
                  <!-- button type="button" class="btn btn-danger" @click="deleteCases(cases)"><i class="fa fa-minus-circle"></i>刪除</button -->
                </div>
              </modal>
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
      modal
    },
    created () {
      this.fetchToday()
      this.fetchUser()
      this.fetchCache()
    },
    ready () {
      // this.fetchCase()
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        active: 0,
        disablequery: 0,
        showModal: false,
        valid: {},
        userowner: {},
        listdm: {},
        listckd: {},
        listnondm: {},
        cases: {},
        today: ''
      }
    },
    methods: {
      mask (value) {
        return value.toUpperCase()
      },
      fetchToday () {
        //today
        var date = new Date()
        var thisday = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
        this.today = thisday
        this.$set('cases.case_date', thisday)
        this.$set('cases.close_date', thisday)
//        console.log(this.basist2dm.close_date)
      },
      fetchUser () {
        //登入者
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
            this.$set('cases.hosp_id', response.data.hosp_id)
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
        this.$http({url: '/api/cases/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('cases', response.data)
  //          alert(this.cases.id)
          })
          .catch(function(response) {
            console.log(response)
          })
      },
      fetchCache () {
        this.$http({url: '/api/casescache/', method: 'GET'})
          .then(function (response) {
            this.$set('cases.pid', response.data.pid)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      fetchCase (pid) {
        // var pid = this.cases.pid
        // var pid = $('input[name="pid"]').val()
        // alert($('input[name="pid"]').val())
        // event.preventDefault()
        // let caseid = null
        // this.$http({url: '/api/casescache/', method: 'GET'})
        //  .then(function (response) {
        //    this.$set('cases.pid', response.data.pid)
        //    caseid = this.cases.pid
        //  })
        // alert(caseid)
        // if ($('input[name="pid"]').val()) {
        if (pid) {
          //身份證檢覈
          if(!this.checkTWID(pid)) {
            this.valid.query = false
            return false
          }
          // 先確認 基本資料 是否已存在
          this.$http({url: '/api/basis/listdata/' + pid, method: 'GET'})
            .then(function (response) {
              //  console.log(response.data[0])
              if (response.data.length == 0) {
                alert('該病患尚未建[基本資料], 無法新增[健保方案]!!')
                this.$router.go('/')
              } else {
                alert('該病患已建[基本資料], 可以查詢[健保方案]資料!!')
                this.$set('listdm', [{}])
                this.$set('listckd', [{}])
                this.$set('listnondm', [{}])
              }
            })
          // this.$http({url: '/api/cases/listcase/' + $('input[name="pid"]').val(), method: 'GET'})
        }
      },
      fetchDMCase (pid) {
        // 再查詢方案資料(三種)
        // 1.DM
//        setTimeout(function() { alert('查詢DM方案...') }, 1000)
        if (pid) {
          this.$http({url: '/api/cases/listdmcase/' + pid, method: 'GET'})
            .then(function (response) {
              // console.log(response.data[0])
              if (response.data.length == 0) {
                this.$set('listdm', [{cdate: '無資料', citem: '', cday: ''}])
              } else {
                this.$set('listdm', response.data)
              }
            })
            .catch(function (response) {
              console.log(response)
            })
        }
      },
      fetchCKDCase (pid) {
        // 2.CKD
//        setTimeout(function() { alert('查詢CKD方案...') }, 1000)
        if (pid) {
          this.$http({url: '/api/cases/listckdcase/' + pid, method: 'GET'})
            .then(function (response) {
              if (response.data.length == 0) {
                this.$set('listckd', [{cdate: '無資料', citem: '', cday: ''}])
              } else {
                this.$set('listckd', response.data)
              }
            })
              .catch(function (response) {
                console.log(response)
              })
        }
      },
      fetchNONDMCase (pid) {
        // 3.DM無收案
//        setTimeout(function() { alert('查詢非DM方案...') }, 1000)
        if (pid) {
          this.$http({url: '/api/cases/listnondmcase/' + pid, method: 'GET'})
            .then(function (response) {
              if (response.data.length == 0) {
                this.$set('listnondm', [{cdate: '無資料', citem: '', cday: ''}])
              } else {
                this.$set('listnondm', response.data)
              }
            })
            .catch(function (response) {
              console.log(response)
            })
        }
      },
      deleteCases (cases) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名病患的健保方案!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.cases.$remove(cases)
          self.$http.delete('/api/cases/' + cases.id, cases).then(function (response) {
            self.$router.go('/cases')
            swal(
              '已刪除!!',
              '該名病患的健保方案已經刪除!!',
              'success'
            )
            this.active = 0
            this.disablequery = 0
            this.showModal = false
          }, function (response){
            show_stack_error('刪除病患的健保方案發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名病患的健保方案維持現狀 :)',
              'error'
            )
          }
        })
      },
      createCases () {
        var answer = confirm("確定新增嗎?!")
        if (answer) {
          this.active = 0
          this.disablequery = 0
          var swtch = false
          if (this.cases.dm == null && this.cases.undm == null && this.cases.offdm == null) {
            alert('尚未拉選[診療階段], 無法新增[健保方案]!!')
            return swtch
          } else {
            var pid = this.cases.pid
            var msg = null
            // 檢查DM方案: DM初診,DM複診,DM年度(this.cases.dm)
            this.$http.patch('/api/cases/checkpid/' + pid, this.cases)
              .then(function (response) {
//                console.log(response.data.message)
                if (response.data.message != null) {
                  msg = "提醒訊息::不符合方案規定!! \r\n \r\n"
                  msg += "-------------------------- \r\n"
                  msg += response.data.message
                  msg += "-------------------------- \r\n"
                  msg += "您確定要新增方案嗎?? \r\n"
                  msg += "您確定要新增方案嗎?! \r\n"
                  msg += "您確定要新增方案嗎!!"
                  var answer1 = confirm(msg)
                  if (answer1) {
                    swtch = true
                  } else {
                    this.$router.go('/')
                  }
                } else {
                  swtch = true
                }
//                alert(swtch)
                if (swtch) {
                  var input = this.cases
                  this.$http.post('/api/cases', input)
                    .then(function (response) {
                      show_stack_info('已新增', response)
                      var id = response.data.id
                      this.$http({url: '/api/cases/showid1/' + id, method: 'GET'})
                        .then(function (response) {
                          this.$set('cases', response.data)
                          // 結案日期
                          this.$set('cases.close_date', this.today)
                        }, function (response) {
                          show_stack_error('查詢健保方案失敗!', response)
                        })
                      this.active = 1
                      this.disablequery = 1
                    }, function (response) {
                      show_stack_error('新增健保方案失敗!', response)
                    })
                } else {
                  return swtch
                }
              }, function (response) {
                show_stack_error('查詢健保方案失敗!', response)
              })
              .catch(function (response) {
                console.log(response)
              })
          }
        }
      },
      updateCases (cases) {
        this.$http.patch('/api/cases/' + cases.id, cases)
          .then(function (response) {
            show_stack_success('病患的健保方案已更新!', response)
  //          alert(JSON.stringify(response))
          }, function (response) {
            show_stack_error('病患的健保方案更新失敗!', response)
  //          alert(response.data.error.message)
          })
          .catch(function(response) {
            console.log(response)
          })
        this.$router.go('/')
      },
      closeCases (cases) {
        this.showModal = false
//        if (confirm("確定結案嗎?!")) {
          if (this.cases.offdm == null) {
            alert('尚未拉選[診療階段], 無法結案!!')
            return false
          }
//          this.active = 0
//          this.disablequery = 0
          var input = this.cases
          this.$http.post('/api/cases/closecase', input)
            .then(function (response) {
              show_stack_success('病患的健保方案已結案!', response)
            }, function (response) {
//              console.log(response.data.error.message)
              show_stack_error('病患的結案失敗!!', response)
            })
            .catch(function(response) {
              console.log(response)
            })
          this.$router.go('/')
//        }
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
      cleardm () {
        this.$set('cases.dm', [])
        this.$set('cases.undm', [])
        this.$set('cases.offdm', [])
        $('#cleardm').prop('checked', false)  // Unchecks it
      }
    },
    computed: {
      isNoneS: function () {
        if (this.cases.smoking != null) {
          var smokings = this.cases.smoking || ""
          var a = smokings.indexOf("無")
          if (a >= 0) {
            this.cases.havesmoke = ""
            this.cases.quitsmoke = ""
            var items = ['有','已戒']
            var ax
            items.forEach(function(item) {
              ax = smokings.indexOf(item)
              if (ax !== -1) {
                smokings.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneW: function () {
        if (this.cases.blood_ap == "前") {
          this.cases.blood_mins = ""
        }
        return (this.cases.blood_ap == "前")
      },
      isNone: function () {
        if (this.cases.drug_allergy != null) {
          var allergys = this.cases.drug_allergy || ""
          var a = allergys.indexOf("無")
          if (a >= 0) {
            this.cases.drug_allergy_insulin = ""
            this.cases.drug_allergy_other = ""
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
      },
      isCloseCase: function () {
        if (this.valid.query == true && this.cases.dm == null && this.cases.undm == null && this.cases.offdm != null) {
          return false
        } else {
          return true
        }
        // this.cases.offdm != null && this.cases.offdm != ''
      },
      isCreateCase: function () {
        if (this.valid.query == true && (this.cases.dm != null || this.cases.undm != null) && this.cases.offdm == null) {
          return false
        } else {
          return true
        }
        // this.cases.dm != null && this.cases.dm != '' && this.cases.undm != null && this.cases.undm != ''
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
</style>
