<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>血糖批次新增</h4>
      <h5><p>
        您可以在此新增::[<b>血糖批次資料</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、查看</b>...等
      </p></h5>
    </div>

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
            <th>電話</th>
          </tr>
          </thead>
          <tbody>
          <tr class="bg-warning">
            <td>{{ basis.pid }}</td>
            <td>{{ basis.name }}</td>
            <td>{{ basis.birthday }}，{{ patientage }} 歲</td>
            <td>{{ basis.tel1 }}</td>
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
          <label class="control-label col-xs-1 col-xs-offset-1" for="mrdate">起算日</label>
          <div class="col-xs-2">
            <datepicker v-ref:dp :value.sync="mrdate" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="150px"></datepicker>
          </div>
          <label class="control-label col-xs-2 text-danger" for="ivalue">輸入區間</label>
          <div class="col-xs-4">
            <multiselect :options="[{'name':'1天','ivalue':1},{'name':'3天','ivalue':3},{'name':'5天','ivalue':5},{'name':'7天','ivalue':7},{'name':'10天','ivalue':10},{'name':'14天','ivalue':14},{'name':'30天','ivalue':30}]"
                         :selected.sync="indays" :close-on-select="false" :searchable="true" :custom-lable="nameWithInterval" @update="updateinDays" placeholder="請選天數" label="name" key="name">  </multiselect>
          </div>
          <div class="col-xs-1 col-xs-offset-1"><span>{{ indays.ivalue }} </span></div>
        </div>
        <br />
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div id="printpage" class="box-body table-responsive no-padding">
                <!-- batch-table :fromdate="mrdate" :days="indays.ivalue" :pid="pid"> </batch-table -->
                <table class="tbltiny table table-bordered">
                  <thead>
                  <tr class="bg-warning">
                    <th class="col-xs-1">量測日期</th>
                    <th class="col-xs-1">凌晨</th>
                    <th class="col-xs-1">晨起</th>
                    <th class="col-xs-1">早餐前</th>
                    <th class="col-xs-1 bg-danger">早餐後</th>
                    <th class="col-xs-1">中餐前</th>
                    <th class="col-xs-1 bg-danger">中餐後</th>
                    <th class="col-xs-1">晚餐前</th>
                    <th class="col-xs-1 bg-danger">晚餐後</th>
                    <th class="col-xs-1">睡前</th>
                    <th class="col-xs-1 bg-info">衛教備註</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="mdt in mrdates">
                    <td id="{{ mdt.mdate }}_d0">{{ mdt.mdate }}</td>
                    <td id="{{ mdt.mdate }}_d1"><bs-input :value.sync="mdt.d1"></bs-input></td>
                    <td id="{{ mdt.mdate }}_d2"><bs-input :value.sync="mdt.d2"></bs-input></td>
                    <td id="{{ mdt.mdate }}_d3"><bs-input :value.sync="mdt.d3"></bs-input></td>
                    <td class="bg-danger" id="{{ mdt.mdate }}_d4"><bs-input :value.sync="mdt.d4"></bs-input></td>
                    <td id="{{ mdt.mdate }}_d5"><bs-input :value.sync="mdt.d5"></bs-input></td>
                    <td class="bg-danger" id="{{ mdt.mdate }}_d6"><bs-input :value.sync="mdt.d6"></bs-input></td>
                    <td id="{{ mdt.mdate }}_d7"><bs-input :value.sync="mdt.d7"></bs-input></td>
                    <td class="bg-danger" id="{{ mdt.mdate }}_d8"><bs-input :value.sync="mdt.d8"></bs-input></td>
                    <td id="{{ mdt.mdate }}_d9"><bs-input :value.sync="mdt.d9"></bs-input></td>
                    <td class="bg-info" id="{{ mdt.mdate }}_d10"><bs-input :value.sync="mdt.d10"></bs-input></td>
                  </tr>
                  </tbody>
                </table>
                <br />
                <button type="button" class="btn btn-info" @click="saveBatch(mrdates)">存檔</button>
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
  import datepicker from './vue-strap/src/Datepicker.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      popover,
      modal,
      bsInput,
      datepicker,
      Multiselect
    },
    created () {
      this.pid = this.$route.params.basePid
      this.fetchToday()
    },
    ready () {
      this.fetchBasis(this.pid)
      //this.fetchBsrange(this.pid)
    },
    data () {
      return {
        token: csrf_token,
        pid: '',
        basis: {},
        patientage: '',
        currentYear: '',
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        mrdate: '',
        mrdates: [],
        indays: {'name': '3天', 'ivalue': 3},
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
      saveBatch (mrdates) {
        //console.log(this.pid)
        //console.log(JSON.stringify(mrdates))
        this.$http.patch('/api/mresults/savebatch/' + this.pid, {mresult: mrdates}).then(function (response) {
//          console.log(response.data.message)
          show_stack_success('血糖批次已更新!', response)
//          this.$set('mrdates', [])  // 清空
//          this.fetchBatch(this.mrdate, this.indays.ivalue)  // 預設3天
          this.$router.go('/listmresults/' + this.pid)
        }, function (response) {
//          console.log(response.data)
          show_stack_error('血糖批次更新失敗!', response)
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
        this.mrdate = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
      },
      fetchBatch (fromdate, days) {
        this.$set('mrdates', [])
        var dd = 0
        while (days > 0) {
          // 日期區間, 最多30天
          this.mrdates.push({'mdate': this.addDate(fromdate, dd),'d1': '','d2': '','d3': '','d4': '','d5': '','d6': '','d7': '','d8': '','d9': '','d10': ''})
          dd = dd - 1
          days = days - 1
        }
        this.mrdates.sort()
        this.mrdates.reverse()
      },
      fetchBasis (pid) {
        this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('basis', response.data)
            var ptage = this.basis.birthday
            this.patientage = this.currentYear - ( parseInt(ptage.substring(0, 3)) + 1911 )
            this.fetchBatch(this.mrdate, this.indays.ivalue)  // 預設3天
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
      nameWithInterval (name, ivalue) {
        return '${name} — [${ivalue}]'
      },
      updateinDays (value) {
        this.indays = value
        if(this.indays.ivalue != null) {
          this.fetchBatch(this.mrdate, this.indays.ivalue)
        }
      },
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
    padding: 0 !important;
    text-align: center;
    vertical-align: middle;
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
    text-align: center;
    vertical-align: middle;
  }

  .table > tbody > tr > td {
    text-align: center;
    vertical-align: middle;
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
