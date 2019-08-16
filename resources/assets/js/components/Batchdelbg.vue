<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>血糖批次刪除</h4>
      <h5><p>
        您可以在此刪除::[<b>血糖批次資料</b>]--(<b>本共照平台的病患</b>)，功能有：<b>刪除、查看</b>...等
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
          <label class="control-label col-xs-1 col-xs-offset-1" for="startdate">起始日期</label>
          <div class="col-xs-2">
            <datepicker v-ref:dp :value.sync="startdate" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="150px"></datepicker>
          </div>
          <label class="control-label col-xs-1" for="enddate">結束日期</label>
          <div class="col-xs-2">
            <datepicker v-ref:dp :value.sync="enddate" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="150px"></datepicker>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-1">
            <v-checkbox :checked.sync="checkboxValue" value="2" type="info">只刪除H2上傳的資料</v-checkbox>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-1">
            <radio :checked.sync="radioValue" value="1" type="danger">依血糖量測時間搜尋</radio>
          </div>
          <div class="col-xs-2">
            <radio :checked.sync="radioValue" value="2" type="warning">依上傳時間搜尋</radio>
          </div>
          <div class="col-xs-2">
            <button type="button" class="btn btn-info" @click="showBatch(startdate, enddate)">查詢</button>
          </div>
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
                      <th class="col-xs-1">序號</th>
                      <th class="col-xs-1">姓名</th>
                      <th class="col-xs-1">日期</th>
                      <th class="col-xs-1 bg-danger">血糖值</th>
                      <th class="col-xs-1 bg-danger">時段</th>
                      <th class="col-xs-1 bg-danger">上傳時間</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="mrslt in mresults">
                      <td>{{ mrslt.id }}</td>
                      <td>{{ mrslt.bname }}</td>
                      <td>{{ mrslt.mr_date }}</td>
                      <td class="bg-danger">{{ mrslt.value1 }}</td>
                      <td class="bg-danger">{{ mrslt.slot }}:{{ mrslt.slotname }}</td>
                      <td class="bg-danger">{{ mrslt.addnewdate }}</td>
                    </tr>
                  </tbody>
                </table>
                <br />
                <button type="button" class="btn btn-danger" @click="destroyBatch(mresults)">全部刪除</button>
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

  import buttonGroup from './vue-strap/src/buttonGroup.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import vCheckbox from './vue-strap/src/Checkbox.vue'
  import radio from './vue-strap/src/Radio.vue'
  import datepicker from './vue-strap/src/Datepicker.vue'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      buttonGroup,
      bsInput,
      vCheckbox,
      radio,
      datepicker
    },
    created () {
      this.pid = this.$route.params.basePid
      this.fetchToday()
    },
    ready () {
      this.fetchBasis(this.pid)
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
        startdate: '',
        enddate: '',
        mresults: [],
        checkboxValue: '2',
        radioValue: '1',
      }
    },
    methods: {
      showBatch (sdate, edate) {
        //console.log(this.radioValue)
        if(this.radioValue == '1') {
          this.$http({url: '/api/mresults/showbgbydate/' + this.pid + '/' + sdate + '/' + edate, method: 'GET'})
            .then(function (response) {
              //console.log(response.data)
              this.$set('mresults', response.data)
            })
            .catch(function (response) {
              console.log(response)
            })
        }
        if(this.radioValue == '2') {
          this.$http({url: '/api/mresults/showbgbyadd/' + this.pid + '/' + sdate + '/' + edate, method: 'GET'})
            .then(function (response) {
              this.$set('mresults', response.data)
            })
            .catch(function (response) {
              console.log(response)
            })
        }
      },
      fetchToday () {
        var date = new Date()
        this.currentYear = date.getFullYear()
        this.startdate = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
        this.enddate = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
      },
      fetchBatch (fromdate, days) {
        this.$set('mresults', [])
        var dd = 0
        while (days > 0) {
          // 日期區間, 最多30天
          this.mresults.push({'mdate': this.addDate(fromdate, dd),'d1': '','d2': '','d3': '','d4': '','d5': '','d6': '','d7': '','d8': '','d9': '','d10': ''})
          dd = dd - 1
          days = days - 1
        }
        this.mresults.sort()
        this.mresults.reverse()
      },
      fetchBasis (pid) {
        this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('basis', response.data)
            var ptage = this.basis.birthday
            this.patientage = this.currentYear - ( parseInt(ptage.substring(0, 3)) + 1911 )
            //this.fetchBatch(this.mrdate, this.indays.ivalue)  // 預設3天
            this.$set('checkboxValue', '')
            //this.$set('radioValue', '1')
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      destroyBatch (mresults) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該批次血糖值!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.$http.delete('/api/mresults/destroybatch/1', {mresult: mresults}).then(function (response) {
            //console.log(response.data.message)
            swal(
              '已刪除!!',
              '該批次血糖值已經刪除!!',
              'success'
            );
            self.$set('mresults', [])
            self.$router.go('/listbasis')
          }, function (response){
            show_stack_error('批次刪除血糖值發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該批次血糖值維持現狀 :)',
              'error'
            );
          }
        });
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
