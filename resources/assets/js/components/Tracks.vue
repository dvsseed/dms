<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>個案管理(新增個管)</h4>
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
            <tabs :active.sync="active">
              <tab header="個案管理-查詢">
                <form-group :valid.sync="valid.query">
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="hosp_id"><span class="text-danger">*醫事機構代碼</span></label>
                    <div class="col-xs-7">
                      <bs-input name="hosp_id" :value.sync="tracks.hosp_id" disabled></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                    <div class="col-xs-7">
                      <bs-input name="pid" :value.sync="tracks.pid" maxlength="18" :mask="mask" placeholder="請輸入...身份證字號" required></bs-input>
                      <!-- pattern="^[A-Z][1-2][0-9]+$" -->
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </form-group>
                <div class="col-xs-12 col-xs-offset-3">
                  <button type="button" class="btn btn-warning" :disabled="!valid.query" @click="fetchTrack(tracks.pid)"><i class="fa fa-search"></i>查詢</button>
                  <button type="button" class="btn btn-primary" :disabled="!isCreateTrack" @click="createTracks()"><i class="fa fa-plus-circle"></i>新增</button>
                </div>
                <div class="col-xs-12"><br></div>
              </tab>
              <tab header="個案管理-新增" :disabled="!disablequery">
                <form-group :valid.sync="valid.create">
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="educator_user_id">衛教師</label>
                    <div class="col-xs-7">
                      <multiselect :options="educators" :selected.sync="tracks.educator_user_id" :multiple="false" :searchable="false" :close-on-select="false"
                                   :show-labels="false" @update="updateEducator" :custom-label="nameWithid" label="id" key="id">  </multiselect>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="start_date"><span class="text-danger">*開始時間</span></label>
                    <div class="col-xs-7">
                      <datepicker v-ref:dp :value.sync="tracks.start_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="end_date"><span class="text-danger">*結束時間</span></label>
                    <div class="col-xs-7">
                      <datepicker v-ref:dp :value.sync="tracks.end_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="track_date"><span class="text-danger">*預訂追蹤日期</span></label>
                    <div class="col-xs-7">
                      <datepicker v-ref:dp :value.sync="tracks.track_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="contact_period"><span class="text-danger">*聯絡時段</span></label>
                    <div class="col-xs-7">
                      <radio :checked.sync="tracks.contact_period" value="整日" type="info">整日</radio>
                      <radio :checked.sync="tracks.contact_period" value="早上" type="info">早上</radio>
                      <radio :checked.sync="tracks.contact_period" value="下午" type="info">下午</radio>
                      <radio :checked.sync="tracks.contact_period" value="晚上" type="info">晚上</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="bloodsugar_source"><span class="text-danger">*血糖來源</span></label>
                    <div class="col-xs-7">
                      <radio :checked.sync="tracks.bloodsugar_source" value="傳輸" type="warning">傳輸</radio>
                      <radio :checked.sync="tracks.bloodsugar_source" value="電話" type="warning">電話</radio>
                      <radio :checked.sync="tracks.bloodsugar_source" value="回診討論" type="warning">回診討論</radio>
                      <radio :checked.sync="tracks.bloodsugar_source" value="傳真" type="warning">傳真</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="contact_tel"><span class="text-danger">*聯絡電話</span></label>
                    <div class="col-xs-7">
                      <bs-input name="contact_tel" :value.sync="tracks.contact_tel" required></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="contact_mobile"><span class="text-danger">*聯絡手機</span></label>
                    <div class="col-xs-7">
                      <bs-input name="contact_mobile" :value.sync="tracks.contact_mobile" required></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-1 col-xs-offset-1" for="track1_contact">追蹤聯繫人1</label>
                    <div class="col-xs-2">
                      <bs-input name="track1_contact" :value.sync="tracks.track1_contact"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track1_tel">聯繫人1電話</label>
                    <div class="col-xs-2">
                      <bs-input name="track1_tel" :value.sync="tracks.track1_tel"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track1_mobile">聯繫人1手機</label>
                    <div class="col-xs-2">
                      <bs-input name="track1_mobile" :value.sync="tracks.track1_mobile"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-1 col-xs-offset-1" for="track2_contact">追蹤聯繫人2</label>
                    <div class="col-xs-2">
                      <bs-input name="track2_contact" :value.sync="tracks.track2_contact"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track2_tel">聯繫人2電話</label>
                    <div class="col-xs-2">
                      <bs-input name="track2_tel" :value.sync="tracks.track2_tel"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track2_mobile">聯繫人2手機</label>
                    <div class="col-xs-2">
                      <bs-input name="track2_mobile" :value.sync="tracks.track2_mobile"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-1 col-xs-offset-1" for="track3_contact">追蹤聯繫人3</label>
                    <div class="col-xs-2">
                      <bs-input name="track3_contact" :value.sync="tracks.track3_contact"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track3_tel">聯繫人3電話</label>
                    <div class="col-xs-2">
                      <bs-input name="track3_tel" :value.sync="tracks.track3_tel"></bs-input>
                    </div>
                    <label class="control-label col-xs-1" for="track3_mobile">聯繫人3手機</label>
                    <div class="col-xs-2">
                      <bs-input name="track3_mobile" :value.sync="tracks.track3_mobile"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="track_reason"><span class="text-danger">*追蹤原因</span></label>
                    <div class="col-xs-7">
                      <multiselect :options="['無','低血糖','初次使用胰島素','初次使用SU','初次使用GLP-1','MDI&#8805;3針','Insulin punp','T1DM','糖尿病懷孕','妊娠糖尿病','提醒驗血糖','研究或計劃']"
                                   :selected.sync="tracks.track_reason" :multiple="false" :searchable="false"
                                   :close-on-select="false" :show-labels="false" @update="updateTrackReason" placeholder="Select one">  </multiselect>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="dietplan">飲食計劃</label>
                    <div class="col-xs-4">
                      <multiselect :options="['一般','CKD','懷孕','管灌']" :selected.sync="tracks.dietplan" :multiple="true" :searchable="false" :allow-empty="false"
                                   :close-on-select="false" :clear-on-select="false" @update="updateDietPlan" placeholder="Pick some">  </multiselect>
                    </div>
                    <div class="col-xs-4 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-3">
                      <bs-input name="dietplan_meal" :value.sync="tracks.dietplan_meal"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      餐
                    </div>
                    <div class="col-xs-1">
                      <bs-input name="dietplan_dessert" :value.sync="tracks.dietplan_dessert"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      點
                    </div>
                    <label class="control-label col-xs-1" for="dietplan_sugaramount">醣量：C</label>
                    <div class="col-xs-2">
                      <bs-input name="dietplan_sugaramount" :value.sync="tracks.dietplan_sugaramount"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="monitor_mode"><span class="text-danger">*監測模式</span></label>
                    <div class="col-xs-7">
                      <radio :checked.sync="tracks.monitor_mode" value="早晚餐前&plus;睡前" type="danger">早晚餐前&plus;睡前</radio>
                      <radio :checked.sync="tracks.monitor_mode" value="睡前&plus;晨起" type="danger">睡前&plus;晨起</radio>
                      <radio :checked.sync="tracks.monitor_mode" value="三餐前&plus;睡前" type="danger">三餐前&plus;睡前</radio>
                      <radio :checked.sync="tracks.monitor_mode" value="四配對" type="danger">四配對</radio>
                      <radio :checked.sync="tracks.monitor_mode" value="其他" type="danger">其他</radio>
                      <bs-input name="monitor_other" :value.sync="tracks.monitor_other"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="bloodsugar"><span class="text-danger">*血糖目標</span></label>
                    <div class="col-xs-1">
                      餐前
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_from_beforemeal" :value.sync="tracks.bloodsugar_from_beforemeal"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      ～
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_to_beforemeal" :value.sync="tracks.bloodsugar_to_beforemeal"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-2"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-3">
                      餐後
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_from_aftermeal" :value.sync="tracks.bloodsugar_from_aftermeal"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      ～
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_to_aftermeal" :value.sync="tracks.bloodsugar_to_aftermeal"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-2"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-3">
                      睡前
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_from_beforesleep" :value.sync="tracks.bloodsugar_from_beforesleep"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      ～
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="bloodsugar_to_beforesleep" :value.sync="tracks.bloodsugar_to_beforesleep"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-2"><span> </span></div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="adjustprinciple_unit">調整原則</label>
                    <div class="col-xs-1">
                      加減
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="adjustprinciple_unit" :value.sync="tracks.adjustprinciple_unit"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      單位
                    </div>
                    <div class="col-xs-1 col-xs-offset-4"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-3">
                      ISF:
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="adjustprinciple_isf" :value.sync="tracks.adjustprinciple_isf"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      mg/dl ＋
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="adjustprinciple_u" :value.sync="tracks.adjustprinciple_u"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      U
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-3">
                      C/I:
                    </div>
                    <div class="col-xs-1 col-xs-offset-7"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 col-xs-offset-4">
                      早：
                    </div>
                    <div class="col-xs-1">
                      <bs-input name="adjustprinciple_ci_morning" :value.sync="tracks.adjustprinciple_ci_morning"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      午：
                    </div>
                    <div class="col-xs-1">
                      <bs-input name="adjustprinciple_ci_afternoon" :value.sync="tracks.adjustprinciple_ci_afternoon"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      晚：
                    </div>
                    <div class="col-xs-1">
                      <bs-input name="adjustprinciple_ci_evening" :value.sync="tracks.adjustprinciple_ci_evening"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="medication"><span class="text-danger">*用藥</span></label>
                    <div class="col-xs-7">
                      <bs-input name="medication" :value.sync="tracks.medication" type="textarea" @focus="event = 'focused'" @blur="event = 'blured'"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="remarks">備註</label>
                    <div class="col-xs-7">
                      <bs-input name="remarks" :value.sync="tracks.remarks" type="textarea" @focus="event = 'focused'" @blur="event = 'blured'"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </form-group>
                <div class="col-xs-12">
                  <button type="button" class="btn btn-warning" :disabled="!valid.create" @click="updateTracks(tracks)"><i class="fa fa-pencil-square-o"></i>修改</button>
                  <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteTracks(tracks)"><i class="fa fa-minus-circle"></i>刪除</button>
                </div>
              </tab>
            </tabs>
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
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
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
      Multiselect
    },
    created () {
      this.fetchToday()
      this.fetchUser()
      this.fetchEducator()
    },
    ready () {
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        active: 0,
        disablequery: 0,
        disablecreate: 0,
        showModal: false,
        valid: {
          query: false,
          create: false,
          closed: false,
          added: false,
          all: false
        },
        userowner: {},
        tracks: {},
        educators: [],
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
        this.$set('tracks.start_date', thisday)
        this.$set('tracks.end_date', thisday)
        this.$set('tracks.track_date', thisday)
      },
      fetchUser () {
        //登入者
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
            this.$set('tracks.hosp_id', response.data.hosp_id)
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
        this.$http({url: '/api/tracks/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('tracks', response.data)
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
      fetchTrack (pid) {
        // var pid = this.tracks.pid
        // var pid = $('input[name="pid"]').val()
        // alert($('input[name="pid"]').val())
        // event.preventDefault()
        // let trackid = null
        // this.$http({url: '/api/trackscache/', method: 'GET'})
        //  .then(function (response) {
        //    this.$set('tracks.pid', response.data.pid)
        //    trackid = this.tracks.pid
        //  })
        // alert(trackid)
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
                alert('該病患尚未建[基本資料], 無法新增[個案管理]!!')
                this.$router.go('/')
              } else {
                // this.valid.added = true
                alert('該病患已建[基本資料], 可以查詢[個案管理]資料!!')
                // console.log(this.valid.query)
                // this.$set(valid.added, true)
                // 查詢是否已建個管
                this.$http({url: '/api/tracks/showpid1/' + pid, method: 'GET'})
                  .then(function (response) {
                    // console.log(response.data.length)
                    // console.log(response.data.error.message)
                    if (response.data.result == 404) {
                      alert('該病患未建[個管資料], 可以新增[個案管理]資料!!')
                      this.disablecreate = 1
                    } else {
                      alert('該病患已建[個管資料], 可以修改[個案管理]資料!!')
                      // console.log(response.data)
                      this.$set('tracks', response.data)
                      // alert(this.tracks.id)
                      this.disablecreate = 0
                      this.active = 1
                      this.disablequery = 1
                    }
                  })
                  // .catch(function(response) {
                    // console.log(response.date.length)
                    // console.log(response.data.error.message)
                  // })
              }
            })
          // this.$http({url: '/api/tracks/listtrack/' + $('input[name="pid"]').val(), method: 'GET'})
        }
      },
      deleteTracks (tracks) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名病患的個案管理!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.tracks.$remove(tracks)
          self.$http.delete('/api/tracks/' + tracks.id, tracks).then(function (response) {
            self.$router.go('/tracks')
            swal(
              '已刪除!!',
              '該名病患的個案管理已經刪除!!',
              'success'
            )
            this.active = 0
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
      createTracks () {
        var answer = confirm("確定新增個管嗎?!")
        if (answer) {
          this.active = 0
          this.disablequery = 0
          var input = this.tracks
          this.$http.post('/api/tracks', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              var id = response.data.id
              this.$http({url: '/api/tracks/showid1/' + id, method: 'GET'})
                .then(function (response) {
                  this.$set('tracks', response.data)
                }, function (response) {
                  show_stack_error('查詢個案管理失敗!', response)
                })
              this.active = 1
              this.disablequery = 1
            }, function (response) {
              show_stack_error('新增個案管理失敗!', response)
            })
        }
      },
      updateTracks (tracks) {
        this.$http.patch('/api/tracks/' + tracks.id, tracks)
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
        this.tracks.educator_user_id = value
      },
      nameWithid ({ id, username }) {
        return `${id} -[${username}]`
      },
      updateTrackReason (value) {
        this.tracks.track_reason = value
      },
      updateDietPlan (value) {
        this.tracks.dietplan = value
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
      }
    },
    computed: {
      isCloseTrack: function () {
        if (this.valid.query == true && this.tracks.dm == null && this.tracks.undm == null && this.tracks.offdm != null) {
          return false
        } else {
          return true
        }
        // this.tracks.offdm != null && this.tracks.offdm != ''
      },
      isCreateTrack: function () {
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
</style>
