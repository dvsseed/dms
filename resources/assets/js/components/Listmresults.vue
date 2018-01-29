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
            <th>功能</th>
          </tr>
          </thead>
          <tbody>
          <tr class="bg-warning">
            <td>{{ basis.pid }}</td>
            <td>{{ basis.name }}</td>
            <td>{{ basis.birthday }}，{{ patientage }} 歲</td>
            <td>
              <button v-if="existBsrange" type="button" class="btn btn-info" @click="bsrangeEdit($event, $index)">修改血糖時段、目標值</button>
              <button v-else type="button" class="btn btn-warning" @click="bsrangeAdd($event, $index)">新增血糖時段、目標值</button>
            </td>
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
            <th v-for="hh in 4"> </th>
          </tr>
          </thead>
          <tbody>
          <tr class="bg-warning">
            <td>HbA1C</td>
            <td v-for="dd in 4"> </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="box box-success">
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
            <multiselect :options="[{name:'近二周',ivalue:1},{name:'近一個月',ivalue:2},{name:'近二個月',ivalue:4},{name:'近三個月',ivalue:6},{name:'近半年',ivalue:13},{name:'近一年',ivalue:26}]" :selected.sync="intervalue",
                         :searchable="true" :close-on-select="false" placeholder="請選查詢區間" label="name" :custom-lable="nameWithInterval" @update="updateinterValue" key="name">  </multiselect>
          </div>
          <div class="col-xs-1 col-xs-offset-5"><span>{{ intervalue.ivalue }} </span></div>
        </div>
        <br />
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div id="printpage" class="box-body table-responsive no-padding">
                <label for="iconimg" style="vertical-align: middle; text-align: center">
                  <img src="/img/cross.gif" />：新增血糖 &nbsp;
                  <img src="/img/hotcafe.gif" />：新增或修改(非正餐飲食) &nbsp;
                  <img src="/img/rice.gif" />：新增或修改(正餐飲食) &nbsp;
                  *：表有血糖備註
                </label>
                <br />
                <div v-for="n in intervalue.ivalue">
                  <blood-table :num="n" :fromdate="fromdate[n]" :todate="todate[n]" :pid="pid" :ivalue="intervalue.ivalue"> </blood-table>
                  <br />
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
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      popover,
      modal,
      bsInput,
      VueTimepicker,
      BloodTable,
      Multiselect
    },
    created () {
      this.pid = this.$route.params.basePid
      this.fetchToday()
    },
    ready () {
      this.fetchBasis(this.pid)
      this.fetchBsrange(this.pid)
    },
    data () {
      return {
        token: csrf_token,
        pid: '',
        basis: {},
        patientage: '',
        currentYear: '',
        fromdate: [],
        todate: [],
        intervalue: {name: '近一個月', ivalue: 2},
        bsrangeModal: false,
        bsrange: {},
        existBsrange: false
      }
    },
    methods: {
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
      fetchBasis (pid) {
        this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('basis', response.data)
            var ptage = this.basis.birthday
            this.patientage = this.currentYear - ( parseInt(ptage.substring(0, 3)) + 1911 )
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
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
            //console.log(response)
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

  /*
  .col-centered {
    display: inline-block;
    float: none;
    text-align: left;
    margin-right: -4px;
  }
  */
</style>
