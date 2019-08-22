<template>
  <!-- Display Validation Errors -->
  <!-- general form elements -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">編輯個管</h3>
    </div>
    <!-- /.box-header -->
      <!-- form start -->
      <!-- form role="form" novalidate -->
      <form role="form">
        <div class="box-body">
          <form-group :valid.sync="valid.update">
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
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <!-- button type="button" :disabled="!valid.update" @click="updateTrack(tracks)" class="btn btn-lg btn-primary btn-flat" -->
          <button type="button" @click="updateTrack(tracks)" class="btn btn-lg btn-primary btn-flat">
            <i class="fa fa-cloud" aria-hidden="true"></i> 存</button>
          <!-- button type="button" @click="deleteTrack(tracks)" class="btn btn-lg btn-danger btn-flat"><i class="fa fa-trash-o" aria-hidden="true"></i> 刪</button -->
        </div>
      </form>
    <!-- /validator -->
  </div>
  <!-- /.box -->
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
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

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
      Multiselect
    },
    created () {
      this.trackId = this.$route.params.trackId
      this.fetchToday()
      this.fetchEducator()
      this.fetchTrack()
    },
    ready () {
      this.fetchOwnUser()
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        active: 0,
        userowner: {},
        trackId: '',
        tracks: {},
        educators: [],
        valid: {
          update: false
        },
        today: ''
      }
    },
    methods: {
      fetchOwnUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
//              this.$http({url: 'logout', method: 'GET'}).then(function (response) {
                window.location.assign('/auth/logout')
//              })
            }
          })
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
      fetchTrack () {
        this.$http({url: '/api/tracks/' + this.trackId + '/edit', method: 'GET'}).then(function (response) {
          // success callback
          this.$set('tracks', response.data)
        }, function (response) {
          // error callback
        })
      },
      updateTrack (utrack) {
        //event.preventDefault();
        this.$http.patch('/api/tracks/' + utrack.id, utrack).then(function (response) {
          show_stack_success('個管已更新!', response)
        }, function (response) {
          show_stack_error('個管更新失敗!', response)
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
      updateMachineexception (value) {
        this.tracks.machine_exception = value
      },
//      deleteTrack (dtrack) {
//        let self = this
//        swal({
//          title: '確定嗎?!',
//          text: '您將無法救回此個管!!',
//          type: 'warning',
//          showCancelButton: true,
//          confirmButtonText: '是的, 刪除!!',
//          cancelButtonText: '不, 取消!!',
//        }).then(function () {
//          self.$http.delete('/api/tracks/' + dtrack.id, dtrack).then(function (response) {
//            self.$router.go('/tracks')
//            swal(
//              '已刪除!!',
//              '個管已經刪除!!',
//              'success'
//            );
//          }, function (response) {
//            show_stack_error('刪除個管發生錯誤!!', response)
//          })
//        }, function (dismiss) {
//          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
//          if (dismiss === 'cancel') {
//            swal(
//              '已取消',
//              '個管維持現狀 :)',
//              'error'
//            );
//          }
//        });
//      },
    },
    computed: {
    },
    watch: {
      disabled () {
        this.$refs.dp.getDateRange()
      },
      format (newV) {
        this.value = this.$refs.dp.stringify()
      },
    }
  }
</script>
