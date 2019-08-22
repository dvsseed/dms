<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>血糖資料管理</h4>
      <h5><p>
        您可以在此管理::[<b>血糖資料</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除、查看、即時查詢、H2數據</b>...等
      </p></h5>
    </div>

    <modal title="BSrange Modal" :show.sync="bsrangeModal" large>
      <div slot="modal-header" class="modal-header">
        <div class="col-xs-4 col-xs-offset-5"><h4 class="modal-title"><i>血 糖 區 間</i> <code>時段、目標值</code> <b></b></h4></div>
      </div>
      <div slot="modal-body" class="modal-body">
        <table class="tbltiny table-bordered table-hover">
          <thead>
            <tr>
              <th class="col-xs-4 bg-success">血糖區間</th>
              <th class="col-xs-2 bg-info">起</th>
              <th class="col-xs-2 bg-info">迄</th>
              <th class="col-xs-2 bg-warning">低標</th>
              <th class="col-xs-2 bg-warning">高標</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="col-xs-1">凌晨</td>
              <td class="col-xs-1 bg-info"><bs-input name="deepnightftime" :value.sync="bsrange.deepnightftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="deepnightttime" :value.sync="bsrange.deepnightttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="deepnightlow" :value.sync="bsrange.deepnightlow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="deepnighthigh" :value.sync="bsrange.deepnighthigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">晨起</td>
              <td class="col-xs-1 bg-info"><bs-input name="weekupftime" :value.sync="bsrange.weekupftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="weekupttime" :value.sync="bsrange.weekupttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="weekuplow" :value.sync="bsrange.weekuplow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="weekuphigh" :value.sync="bsrange.weekuphigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">早餐前</td>
              <td class="col-xs-1 bg-info"><bs-input name="beforebreakftime" :value.sync="bsrange.beforebreakftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="beforebreakttime" :value.sync="bsrange.beforebreakttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforebreaklow" :value.sync="bsrange.beforebreaklow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforebreakhigh" :value.sync="bsrange.beforebreakhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">早餐後</td>
              <td class="col-xs-1 bg-info"><bs-input name="afterbreakftime" :value.sync="bsrange.afterbreakftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="afterbreakttime" :value.sync="bsrange.afterbreakttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afterbreaklow" :value.sync="bsrange.afterbreaklow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afterbreakhigh" :value.sync="bsrange.afterbreakhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">中餐前</td>
              <td class="col-xs-1 bg-info"><bs-input name="beforenoonftime" :value.sync="bsrange.beforenoonftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="beforenoonttime" :value.sync="bsrange.beforenoonttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforenoonlow" :value.sync="bsrange.beforenoonlow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforenoonhigh" :value.sync="bsrange.beforenoonhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">中餐後</td>
              <td class="col-xs-1 bg-info"><bs-input name="afternoonftime" :value.sync="bsrange.afternoonftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="afternoonttime" :value.sync="bsrange.afternoonttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afternoonlow" :value.sync="bsrange.afternoonlow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afternoonhigh" :value.sync="bsrange.afternoonhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">晚餐前</td>
              <td class="col-xs-1 bg-info"><bs-input name="beforedinnerftime" :value.sync="bsrange.beforedinnerftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="beforedinnerttime" :value.sync="bsrange.beforedinnerttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforedinnerlow" :value.sync="bsrange.beforedinnerlow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="beforedinnerhigh" :value.sync="bsrange.beforedinnerhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">晚餐後</td>
              <td class="col-xs-1 bg-info"><bs-input name="afterdinnerftime" :value.sync="bsrange.afterdinnerftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="afterdinnerttime" :value.sync="bsrange.afterdinnerttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afterdinnerlow" :value.sync="bsrange.afterdinnerlow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="afterdinnerhigh" :value.sync="bsrange.afterdinnerhigh"></bs-input></td>
            </tr>
            <tr>
              <td class="col-xs-1">睡前</td>
              <td class="col-xs-1 bg-info"><bs-input name="sleepftime" :value.sync="bsrange.sleepftime"></bs-input></td>
              <td class="col-xs-1 bg-info"><bs-input name="sleepttime" :value.sync="bsrange.sleepttime"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="sleeplow" :value.sync="bsrange.sleeplow"></bs-input></td>
              <td class="col-xs-1 bg-warning"><bs-input name="sleephigh" :value.sync="bsrange.sleephigh"></bs-input></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div slot="modal-footer" class="modal-footer">
        <button type="button" class="btn btn-default" @click='bsrangeModal = false'>離開</button>
        <button type="button" class="btn btn-success" @click='updateBSrange(bsrange)'>存檔</button>
      </div>
    </modal>

    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">病患</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <thead>
          <tr class="bg-success">
            <th>身分證號</th>
            <th>病患姓名</th>
            <th>生日</th>
            <th>血糖來源</th>
            <th>開始時間</th>
            <th>結束時間</th>
            <th>功能</th>
          </tr>
          </thead>
          <tbody>
          <tr class="bg-warning">
            <td>{{ basis.pid }}</td>
            <td>{{ basis.name }}</td>
            <td>{{ basis.birthday }}，{{ patientage }} 歲</td>
            <td>{{ basis.bloodsugar_source }}</td>
            <td>{{ basis.start_date }}</td>
            <td>{{ basis.end_date }}</td>
            <td>
              <button v-if="existBsrange" type="button" class="btn btn-info" @click="bsrangeEdit($event, $index)">修改血糖時段、目標值</button>
              <button v-else type="button" class="btn btn-success" @click="bsrangeAdd($event, $index)">新增血糖時段、目標值</button>
              <button v-link="{ name: 'batchmresult', params: { basePid: basis.pid } }" type="button" class="btn btn-warning">批次增</button>
              <button v-link="{ name: 'batchdelbg', params: { basePid: basis.pid } }" type="button" class="btn btn-danger">批次刪</button>
              <button type="button" id="tracksButton" @click="tracksData(basis.pid, basis.start_date)" class="btn btn-info btn-flat"><i class="fa fa-compress" aria-hidden="true"></i>個管訊息</button>
              <button type="button" id="contactButton" @click="contactData(basis.pid, basis.start_date)" class="btn btn-info btn-flat"><i class="fa fa-compress" aria-hidden="true"></i>聯絡訊息</button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="bs-example">
      <sidebar :show.sync="tracksRight" placement="right" header="個管訊息" :width="350">
        <div class="row">
          <label for="track_reason"><span class="text-danger">追蹤原因</span></label>
          <input v-model="listtrack.track_reason" class="form-control disabled" name="track_reason" disabled="true">
        </div>
        <div class="row">
          <label for="monitor_mode"><span class="text-danger">監測模式</span></label>
          <input v-model="listtrack.monitor_mode" class="form-control disabled" name="monitor_mode" disabled="true">
        </div>
        <div class="row">
          <label for="medication"><span class="text-danger">用藥</span></label>
          <bs-input name="medication" :value.sync="listtrack.medication" type="textarea" @focus="event = 'focused'" @blur="event = 'blured'" disabled="true"></bs-input>
        </div>
        <div class="row">
          <label for="adjustprinciple_unit"><span class="text-danger">調整原則</span></label><br />
            <label for="adjustprinciple_unit">加減 </label> {{ listtrack.adjustprinciple_unit }} 單位<br />
            <label for="adjustprinciple_isf">ISF: </label> {{ listtrack.adjustprinciple_isf }} mg/dl ＋ {{ listtrack.adjustprinciple_u }} U<br />
            <label for="adjustprinciple_ci_morning">C/I: </label> 早： {{ listtrack.adjustprinciple_ci_morning }} 午： {{ listtrack.adjustprinciple_ci_afternoon }} 晚： {{ listtrack.adjustprinciple_ci_evening }}
        </div>
        <div class="row">
          <label for="dietplan"><span class="text-danger">飲食計劃</span></label><br />
            {{ listtrack.dietplan }}
        </div>
        <div class="aside-footer">
          <button type="button" class="btn btn-default" @click="tracksRight = false">Close</button>
        </div>
      </sidebar>
    </div>

    <div class="bs-example">
      <sidebar :show.sync="contactRight" placement="right" header="聯絡訊息" :width="350">
        <div class="row">
          <label for="contact_tel"><span class="text-danger">聯絡電話</span></label>
          <input v-model="listcontact.contact_tel" class="form-control disabled" name="contact_tel" disabled="true">
        </div>
        <div class="row">
          <label for="contact_mobile"><span class="text-danger">聯絡手機</span></label>
          <input v-model="listcontact.contact_mobile" class="form-control disabled" name="monitor_mode" disabled="true">
        </div>
        <div class="row">
          <label for="track1_contact"><span class="text-danger">追蹤聯繫人1</span></label>
          {{ listcontact.track1_contact }} 電話: {{ listcontact.track1_tel }} 手機: {{ listcontact.track1_mobile }}
        </div>
        <div class="row">
          <label for="track2_contact"><span class="text-danger">追蹤聯繫人2</span></label>
          {{ listcontact.track2_contact }} 電話: {{ listcontact.track2_tel }} 手機: {{ listcontact.track2_mobile }}
        </div>
        <div class="row">
          <label for="track3_contact"><span class="text-danger">追蹤聯繫人3</span></label>
          {{ listcontact.track3_contact }} 電話: {{ listcontact.track3_tel }} 手機: {{ listcontact.track3_mobile }}
        </div>
        <div class="aside-footer">
          <button type="button" class="btn btn-default" @click="contactRight = false">Close</button>
        </div>
      </sidebar>
    </div>

    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">預定追踪日</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <table class="borderless">
          <tbody>
          <tr class="bg-warning">
            <td class="bg-warning">
              <bs-input name="track_date" :value.sync="trackdate" disabled></bs-input>
            </td>
            <td class="bg-warning"><div class="col-xs-1"></div></td>
            <th class="bg-danger">今加</th>
            <td class="bg-danger"><div class="col-xs-1"></div></td>
            <td class="bg-danger">
              <multiselect :options="dayoptions" :selected.sync="traceday" :multiple="false" :searchable="false" :close-on-select="false"
                           :show-labels="false" @update="updateDay">
              </multiselect>
            <td class="bg-danger"><div class="col-xs-1"></div></td>
            <th class="bg-danger">天</th>
            <td class="bg-warning"><div class="col-xs-1"></div></td>
            <th class="bg-info">狀態</th>
            <td class="bg-info"><div class="col-xs-1"></div></td>
            <td class="bg-info">
              <v-select name="trace_status" :options="['已完成','未完成']" :value.sync="tracestatus" clear-button></v-select>
            </td>
            <td class="bg-warning"><div class="col-xs-1"></div></td>
            <th class="bg-success">衛教師</th>
            <td class="bg-success"><div class="col-xs-1"></div></td>
            <td class="bg-success">
              <!-- multiselect :options="educators" :selected.sync="tracks.educator_user_id" :multiple="false" :searchable="false" :close-on-select="false"
                           :show-labels="false" @update="updateEducator" :custom-label="nameWithid" label="id" key="id">  </multiselect -->
              <bs-input name="track_username" :value.sync="trackusername" disabled></bs-input>
            </td>
            <td class="bg-warning"><div class="col-xs-1"></div></td>
            <td class="bg-warning">
              <button type="button" class="btn btn-info" @click="trackSave($event, $index)">存檔</button>
              <button type="button" class="btn btn-danger" @click="trackUndo($event, $index)">復原</button>
            </td>
            <td class="bg-warning"><div class="col-xs-1"></div></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">近四筆 HbA1C</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <thead>
          <tr class="bg-info">
            <th>日期</th>
            <td v-for="case in cases">{{ case.case_date }}</td>
          </tr>
          </thead>
          <tbody>
          <tr class="bg-warning">
            <td>HbA1C</td>
            <td v-for="case in cases">{{ case.hba1c }}</td>
          </tr>
          </tbody>
        </table>
      </div>

      <div class="box-header with-border">
        <h3 class="box-title">血糖</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <label class="control-label col-xs-2 text-danger" for="ivalue">查詢區間</label>
          <div class="col-xs-4">
            <multiselect
                    :options="[{name:'近二周',ivalue:1},{name:'近一個月',ivalue:2},{name:'近二個月',ivalue:4},{name:'近三個月',ivalue:6},{name:'近半年',ivalue:13},{name:'近一年',ivalue:26}]"
                    :selected.sync="intervalue" ,
                    :searchable="true" :close-on-select="false" placeholder="請選查詢區間" label="name"
                    :custom-lable="nameWithInterval" @update="updateinterValue" key="name"></multiselect>
          </div>
          <div class="col-xs-1 col-xs-offset-5"><span>{{ intervalue.ivalue }} </span></div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div id="printpage" class="box-body table-responsive no-padding">
                <label for="iconimg" style="vertical-align: middle; text-align: center">
                  <img src="/img/cross.gif"/>：新增血糖 &nbsp;
                  <img src="/img/hotcafe.gif"/>：新增或修改(非正餐飲食) &nbsp;
                  <img src="/img/rice.gif"/>：新增或修改(正餐飲食) &nbsp;
                  *：表有血糖備註
                </label>
                <br/>
                <div v-for="n in intervalue.ivalue">
                  <blood-table :num="n" :fromdate="fromdate[n]" :todate="todate[n]" :pid="pid"
                               :ivalue="intervalue.ivalue"></blood-table>
                  <br/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import popover from './vue-strap/src/Popover.vue'
  import modal from './vue-strap/src/Modal.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import VueTimepicker from './vue-timepicker.vue'
  import BloodTable from './blood-table.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import sidebar from './vue-strap/src/Aside.vue'
  import vSelect from './vue-strap/src/Select.vue'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      popover,
      modal,
      bsInput,
      VueTimepicker,
      BloodTable,
      Multiselect,
      sidebar,
      vSelect,
    },
    created () {
      this.pid = this.$route.params.basePid
      this.fetchToday()
      this.fetchDays()
      this.fetchEducator()
      this.fetchTrack()
    },
    ready () {
      this.fetchBasis(this.pid)
      this.fetchBsrange(this.pid)
      this.fetchCases(this.pid)
      this.fetchOwnUser()
    },
    data () {
      return {
        token: csrf_token,
        pid: '',
        basis: {},
        cases: [],
        userowner: {},
        patientage: '',
        currentYear: '',
        fromdate: [],
        todate: [],
        intervalue: {name: '近一個月', ivalue: 2},
        bsrangeModal: false,
        bsrange: {},
        listtrack: {},
        listcontact: {},
        dayoptions: [],
        traceday: 0,
        trackdate: '',
        trackusername: '',
        tracestatus: '',
        tracks: {},
        educators: [],
        tracksRight: false,
        contactRight: false,
        existBsrange: false
      }
    },
    methods: {
      fetchOwnUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
            // console.log(this.userowner.username)
            this.$set('trackusername', this.userowner.username)
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchEducator () {
        this.$http({url: '/api/users/educator/', method: 'GET'})
          .then(function (response) {
            this.$set('educators', response.data)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      fetchTrack () {
        this.$http({url: '/api/tracks/showpid1/' + this.pid, method: 'GET'}).then(function (response) {
          // success callback
          this.$set('tracks', response.data)
          this.$set('trackdate', this.tracks.track_date)
          // onsole.log(this.tracks.trace_status)
          if (this.tracks.trace_status)
            this.$set('tracestatus', this.tracks.trace_status)
          else
            this.$set('tracestatus', '已完成')
        }, function (response) {
          // error callback
        })
      },
      updateBSrange (bsrange) {
        //console.log(bloods.mr_time)
        this.$http.patch('/api/mresults/savebsrange/' + this.pid, bsrange).then(function (response) {
          //console.log(response.data.message)
          show_stack_success('血糖區間值已更新!', response)
        }, function (response) {
          show_stack_error('血糖區間值更新失敗!', response)
        })
        //this.$set('bloods', {})
        //this.bloods = {}
        this.bsrangeModal = false
      },
      bsrangeAdd (event, index) {
        //var bd = event.currentTarget.name
        this.$set('bsrange.deepnightlow', '100')
        this.$set('bsrange.deepnighthigh', '150')
        this.$set('bsrange.weekuplow', '90')
        this.$set('bsrange.weekuphigh', '130')
        this.$set('bsrange.beforebreaklow', '90')
        this.$set('bsrange.beforebreakhigh', '130')
        this.$set('bsrange.afterbreaklow', '100')
        this.$set('bsrange.afterbreakhigh', '190')
        this.$set('bsrange.beforenoonlow', '90')
        this.$set('bsrange.beforenoonhigh', '130')
        this.$set('bsrange.afternoonlow', '100')
        this.$set('bsrange.afternoonhigh', '190')
        this.$set('bsrange.beforedinnerlow', '90')
        this.$set('bsrange.beforedinnerhigh', '130')
        this.$set('bsrange.afterdinnerlow', '100')
        this.$set('bsrange.afterdinnerhigh', '190')
        this.$set('bsrange.sleeplow', '100')
        this.$set('bsrange.sleephigh', '150')
        this.$set('bsrange.deepnightftime', '00:01')
        this.$set('bsrange.deepnightttime', '06:00')
        this.$set('bsrange.weekupftime', '06:01')
        this.$set('bsrange.weekupttime', '07:00')
        this.$set('bsrange.beforebreakftime', '07:01')
        this.$set('bsrange.beforebreakttime', '09:00')
        this.$set('bsrange.afterbreakftime', '09:01')
        this.$set('bsrange.afterbreakttime', '11:00')
        this.$set('bsrange.beforenoonftime', '11:01')
        this.$set('bsrange.beforenoonttime', '13:00')
        this.$set('bsrange.afternoonftime', '13:01')
        this.$set('bsrange.afternoonttime', '17:00')
        this.$set('bsrange.beforedinnerftime', '17:01')
        this.$set('bsrange.beforedinnerttime', '19:00')
        this.$set('bsrange.afterdinnerftime', '19:01')
        this.$set('bsrange.afterdinnerttime', '20:30')
        this.$set('bsrange.sleepftime', '20:31')
        this.$set('bsrange.sleepttime', '24:00')
        this.bsrangeModal = true
      },
      bsrangeEdit (event, index) {
        // 查血糖區間值
        this.$http({url: '/api/mresults/showbsrange/' + this.pid, method: 'GET'})
          .then(function (response) {
            //console.log(response.data[0])
            if (response.data != '') {
              this.$set('existBsrange', true)
              this.$set('bsrange', response.data)
              //console.log(this.bsrange.deepnightftime)
              this.bsrangeModal = true
            }
          })
          .catch(function(response) {
            //console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      trackSave (event, index) {
        // 個案存檔
        // event.preventDefault();
        // console.log(this.tracks.id)
        // console.log(this.traceday)
        // console.log(this.tracestatus)
        // console.log(this.trackusername)
        // return false
        // 今天加幾日
        // var today = new Date()
        // var tdate = new Date()
        // tdate.setDate(today.getDate() + this.traceday)
        Date.prototype.addDays = function(days) {
          this.setDate(this.getDate() + parseInt(days))
          return this
        }
        var tdate = new Date().addDays(this.traceday)
        var ttdate = tdate.getFullYear() + "-" + (tdate.getMonth() + 1) + "-" + tdate.getDate()
        // console.log(ttdate)
        var track = { "id": this.tracks.id, "trackdate": ttdate, "tracestatus": this.tracestatus }
        // var track = []
        // track.push( { "id": this., name: 'Yashwant', age: 30 } )
        // track.push({id:200,name:'Mahesh',age:35})
        // console.log(track.id)
        this.$http.patch('/api/mresults/' + track.id, track).then(function (response) {
//          console.log(response.data)
          this.$set('trackdate', response.data.track_date)
          this.$set('tracestatus', response.data.trace_status)
//          var uname = response.data.educator_user_id
//          var uuname = uname.substr(-5, 3)
//          console.log(uuname)
//          this.$set('trackusername', uuname)
          this.$set('trackusername', this.trackusername)
          show_stack_success('個管已存檔!', response)
//          console.log(this.pid)
          this.$router.go('/listmresults/' + this.pid)
        }, function (response) {
          show_stack_error('個管存檔失敗!', response)
        })
        // this.$router.go('/')
      },
      trackUndo (event, index) {
        // 個案復原
        //console.log(this.pid)
        //console.log(JSON.stringify(mrdates))
        Date.prototype.addDays = function(days) {
          this.setDate(this.getDate() + parseInt(days))
          return this
        }
        var tdate = new Date().addDays(this.traceday)
        var ttdate = tdate.getFullYear() + "-" + (tdate.getMonth() + 1) + "-" + tdate.getDate()
        var track = { "id": this.tracks.id, "trackdate": ttdate, "tracestatus": this.tracestatus }
        this.$http.patch('/api/mresults/trackundo/' + track.id, track).then(function (response) {
//          console.log(response.data.message)
          this.$set('trackdate', response.data.track_date)
          this.$set('tracestatus', response.data.trace_status)
//          var uname = response.data.educator_user_id
//          var uuname = uname.substr(-5, 3)
//          console.log(uuname)
//          this.$set('trackusername', uuname)
          this.$set('trackusername', this.trackusername)
          show_stack_success('個案已復原!', response)
//          this.$set('mrdates', [])  // 清空
//          this.fetchBatch(this.mrdate, this.indays.ivalue)  // 預設3天
//          this.$router.go('/listmresults/' + this.pid)
        }, function (response) {
//          console.log(response.data)
          show_stack_error('個案復原失敗!', response)
        })
      },
      addDate (date, days) {
        // 日期加減天數
        var d = new Date(date)
        d.setDate(d.getDate() + days)
        var m = d.getMonth() + 1
        return d.getFullYear() + '-' + ('0' + m).slice(-2) + '-' + ('0' + d.getDate()).slice(-2)
      },
      fetchToday () {
        var date = new Date()
        this.currentYear = date.getFullYear()
        this.fromdate[0] = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
        this.todate[0] = this.addDate(this.fromdate[0], -13)
        var i = 0
        while (i < 27) {
          // 14天一區間, 共 六區間, 最多一年(52周)
          this.fromdate[i + 1] = this.addDate(this.todate[i], -1)
          this.todate[i + 1] = this.addDate(this.fromdate[i + 1], -13)
          i = i + 1
        }
      },
      fetchDays () {
        // range = from 0 ~ 30
        var days = []
        for (var d = 0; d < 31; d++) {
          days.push(d)
        }
        this.$set('dayoptions', days)
      },
      updateEducator (value) {
        this.tracks.educator_user_id = value
      },
      nameWithid ({ id, username }) {
        return `${id} -[${username}]`
      },
      fetchBasis (pid) {
        this.$http({url: '/api/basis/showpid2/' + pid, method: 'GET'})
          .then(function (response) {
            // console.log(response.data[0])
            this.$set('basis', response.data[0])
            var ptage = this.basis.birthday
            this.patientage = this.currentYear - ( parseInt(ptage.substring(0, 3)) + 1911 )
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchCases (pid) {
        this.$http({url: '/api/cases/showpid2/' + pid, method: 'GET'})
          .then(function (response) {
            // console.log(response.data)
            this.$set('cases', response.data)
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      tracksData(pid, sdate) {
        this.$http({url: '/api/basis/gettrack/' + pid + '/' + sdate, method: 'GET'})
          .then(function (response) {
            // console.log(response.data[0])
            if (response.data[0].length == 0) {
              this.$set('listtrack', [{track_reason: '無資料'}])
            } else {
              this.$set('listtrack', response.data[0])
            }
          })
          .catch(function (response) {
            // console.log(response)
          })
        this.tracksRight = true
      },
      contactData(pid, sdate) {
        this.$http({url: '/api/basis/getcontact/' + pid + '/' + sdate, method: 'GET'})
          .then(function (response) {
            if (response.data[0].length == 0) {
              this.$set('listcontact', [{contact_tel: '無資料'}])
            } else {
              this.$set('listcontact', response.data[0])
            }
          })
          .catch(function (response) {
            // console.log(response)
          })
        this.contactRight = true
      },
      updateDay (value) {
        this.traceday = value
      },
      fetchBsrange (pid) {
        // 查血糖區間值
        this.$http({url: '/api/mresults/showbsrange/' + pid, method: 'GET'})
          .then(function (response) {
            if (response.data != '') {
              this.$set('existBsrange', true)
              this.$set('bsrange', response.data)
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
      printDiv () {
        if (confirm("確定列印嗎?!")) {
          var divstr = document.getElementById("printpage").innerHTML
          var header = '<header><div align="center"><h3 style="color:#EB5005">列印方案清單</h3></div><br></header><hr><br>'
          var footer = ""
          var windowUrl = "about:blank"
          var popupWin = window.open(windowUrl, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0')
          popupWin.document.open()
          popupWin.document.write('<html><head><title>方案清單</title><style type="text/css">@media print{@page {size:portrait;}} table{border:1px solid black;} th{border:1px solid black;padding:5px;background-color:grey;color:white;} td{border:1px solid black;padding:5px;}</style></head><body onload="window.print()"><p style="font-size: xx-small;">' + divstr + '</p></body></html>' + footer)
          popupWin.document.close()
        }
      },
      updateinterValue (value) {
        this.intervalue = value
      },
      nameWithInterval (name, ivalue) {
        return '${name} — [${ivalue}]'
      }
    },
    computed: {
    },
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
    /* margin: 2em 0; */
  }
  a {
    font-weight: normal;
    color: blue;
  }
  a:active {
    font-weight: bold;
    color: black;
  }

  .table td {
    border: black solid 1px !important;
  }
  .table th {
    border: black solid 1px !important;
  }
  .tbltiny {
    background-color: #eee;
    /* padding: 0 !important; */
    /* text-align: center; */
    /* vertical-align: middle; */
    /* height: 10%; */
    /* margin-bottom: 0; */
    /* margin: 0; */
    /* overflow: auto; */
    /* font-size: 12px; */
    /* line-height : 6px; */
  }

  /* centered columns styles */
  /* .row-centered { */
  .table > thead > tr > th {
    /* text-align: center; */
    /* vertical-align: middle; */
  }

  .table > tbody > tr > td {
    text-align: left !important;
    /* vertical-align: middle; */
  }

  .borderless {
    border: none;
  }

  /*
  .col-centered {
    display: inline-block;
    float: none;
    text-align: left;
    margin-right: -4px;
  }
  */
</style>
