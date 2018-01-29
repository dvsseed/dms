<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>方案資料核對</h4>
      <h5><p>
        您可以在此匯入其他HIS醫療系統的::[<b>健保資料</b>]及檢驗單位的[<b>檢驗數據</b>], 並據以核對後匯出文字檔供健保VPN上傳使用，功能有：<b>匯入、資料核對、匯出</b>...等
      </p></h5>
    </div>

    <div class="container">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title" style="padding: 12px 0px; font-size: 25px;">
            <strong>請注意...匯入與匯出資料的正確及完整 -- 此動作將會影響貴單位的資料庫及VPN上傳!!</strong>
          </h3>
          <h4>
            註：<br>
            1.請注意...匯入的檔案格式(CSV、XLS)及匯出的文字檔案格式(TXT)<br>
            2.匯入數據的資料欄位名稱請先行修改正確<br>
            3.Excel內的儲存格格式建議設定為: 文字<br>
            4.有關Excel內資料寬度有誤或資料內容錯誤者, 請自行修正<br>
          </h4>
        </div>
        <div class="panel-body">
          <h3>1.匯入資料:</h3>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
              <p><pre>檢驗資料 : {{ checks.length}}</pre></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
              <p><pre>HIS資料 : {{ realsun.length }}</pre></p>
            </div>
          </div>
          <form style="border: 2px solid #a1a1a1; margin-top: 15px; padding: 15px;" action="/api/cases/importExcel" class="form-horizontal" method="POST" enctype="multipart/form-data">
            <input type="file" id="importCSVFile" accept="text/csv, .csv" name="importCSVFile" />
            <button type="button" id="importCSVButton" @click="importCSVData" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload" aria-hidden="true"></i> 匯入檢驗(CSV)資料</button>
            <button type="button" id="deleteCSVButton" @click="deleteCSVData" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash" aria-hidden="true"></i> 刪除檢驗匯入資料</button>
            <div style="color:red">匯入的 CSV 檔案的編碼需指定為 [UTF-8] (可使用 記事本 另存轉換)</div>
            <div style="color:red">匯入的 CSV 資料欄位名稱(需為英文): [name,visitday,inform_addr,tel1,pid,birthday,diagnosis]</div>
            <div class="progress">
              <progressbar :now="dynamicData" label type="primary" striped animated></progressbar>
            </div>
            <button type="button" id="checkCSVButton" @click="checkCSVData" class="btn btn-info btn-flat"><i class="fa fa-compress" aria-hidden="true"></i> 檢驗核對</button>
            <hr />
            <input type="file" id="importXLSFile" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="importXLSFile" />
            <button type="button" id="importXLSButton" @click="importXLSData" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload" aria-hidden="true"></i> 匯入Excel資料</button>&nbsp;
            <button type="button" id="deleteXLSButton" @click="deleteXLSData" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash" aria-hidden="true"></i> 刪除HIS匯入資料</button>
            <div style="color:red">匯入 (P1407C、P1408C、P1409C、P4301C、P4302C...等)</div>
            <div style="color:red">匯入的 Excel 資料 [第一列] 為欄位名稱(需為英文): [check_date,checkno,name,birthday,pid,tel,diseasename,prescriptiondose,expirydate]</div>
          </form>
          <!-- a href="/api/cases/refreshCheck"><button class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> 更新檢驗數據至方案</button></a -->
          <div class="bs-example">
            <sidebar :show.sync="checkRight" placement="right" header="檢驗 <-> 方案" :width="350">
              <h4>檢查方案[<span style="color:GoldenRod">DM複診</span>]中的[<span style="color:red">檢驗項目: A1C</span>]是否空值?!</h4><br />
              <table class="table table-striped">
                <thead>
                <tr>
                  <th bgcolor="LightSeaGreen">身份證號</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="pid1 in listpid1">
                  <td bgcolor="LightSeaGreen">{{ pid1.pid }}</td>
                </tr>
                </tbody>
              </table>
              <hr>
              <h4>檢查方案[<span style="color:GoldenRod">DM年度、CKD+DM複診、CKD複診+DM年度</span>]中的[<span style="color:red">檢驗項目: A1C、LDL、Triglyceride、eGFR、Creatinine</span>，<span style="color:DarkOrchid">A/C ratio、U<sub>PCR</sub>(二擇一)</span>]是否空值?!</h4><br />
              <table class="table table-striped">
                <thead>
                <tr>
                  <th bgcolor="LightPink">身份證號</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="pids in listpid">
                  <td bgcolor="LightPink">{{ pids.pid }}</td>
                </tr>
                </tbody>
              </table>
              <div class="aside-footer">
                <button type="button" class="btn btn-default" @click="checkRight = false">Close</button>
              </div>
            </sidebar>
          </div>

          <h3>2.比對資料:</h3>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
              <p><pre>比對資料 : {{ rawdatavpn.length }}</pre></p>
            </div>
          </div>
          <div style="border: 2px solid #a1a1a1; margin-top: 15px; padding: 15px;">
            <!-- a href="/api/cases/refreshCheck"><button class="btn btn-info"><i class="fa fa-compress" aria-hidden="true"></i> 比對::方案與HIS</button></a -->
            <div><button type="button" class="btn btn-info btn-flat" @click="duplicateCases"><i class="fa fa-compress" aria-hidden="true"></i> 檢查方案是否重複</button><br><br></div>
            <button type="button" class="btn btn-info btn-flat" @click="compareCases"><i class="fa fa-compress" aria-hidden="true"></i> 比對::HIS vs.方案</button>
            <button type="button" id="deleteRawdatavpnButton" @click="deleteRawdatavpn" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash" aria-hidden="true"></i> 刪除比對資料</button>
          </div>
          <div class="bs-example">
            <sidebar :show.sync="showLeft" placement="left" header="方案重複" :width="400">
              <h4>檢查結果</h4>
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>診療階段</th>
                  <th>收案日期</th>
                  <th>身份證號</th>
                  <th>重複筆數</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="dup in dupcase">
                  <td>{{ dup.cure_stage }}</td>
                  <td>{{ dup.case_date }}</td>
                  <td>{{ dup.pid }}</td>
                  <td>{{ dup.n }}</td>
                </tr>
                </tbody>
              </table>
              <div class="aside-footer">
                <button type="button" class="btn btn-default" @click="showLeft = false">Close</button>
              </div>
            </sidebar>
          </div>
          <div class="bs-example">
            <sidebar :show.sync="showRight" placement="right" header="HIS <-> 方案" :width="500">
              <h4>比對結果</h4>
              <table class="table table-striped">
                <thead>
                <tr>
                  <th bgcolor="#ffffb8">方案代碼</th>
                  <th bgcolor="#ffffb8">看診日期</th>
                  <th>身份證號</th>
                  <th bgcolor="#82f282">診療階段</th>
                  <th bgcolor="#82f282">收案日期</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="his in hiscase">
                  <td bgcolor="#ffffb8">{{ his.rcode }}</td>
                  <td bgcolor="#ffffb8">{{ his.rdate }}</td>
                  <td>{{ his.rpid }}</td>
                  <td v-if="(his.rcode == 'P1407C' && his.cstage != 'DM初診') || (his.rcode == 'P1408C' && (his.cstage != 'DM複診' && his.cstage != 'CKD初診+DM複診' && his.cstage != 'CKD+DM複診')) || (his.rcode == 'P1409C' && (his.cstage != 'DM年度' && his.cstage != 'CKD複診+DM年度')) || (his.rcode == 'P4301C' && (his.cstage != 'CKD初診' && his.cstage != 'CKD初診+DM複診')) || (his.rcode == 'P4302C' && (his.cstage != 'CKD+DM複診' && his.cstage != 'CKD複診+DM年度'))" style="background-color:red">{{ his.cstage }}</td>
                  <td v-else style="background-color:#82f282">{{ his.cstage }}</td>
                  <td v-if="(his.rcode == 'P1407C' && his.cstage != 'DM初診') || (his.rcode == 'P1408C' && (his.cstage != 'DM複診' && his.cstage != 'CKD初診+DM複診' && his.cstage != 'CKD+DM複診')) || (his.rcode == 'P1409C' && (his.cstage != 'DM年度' && his.cstage != 'CKD複診+DM年度')) || (his.rcode == 'P4301C' && (his.cstage != 'CKD初診' && his.cstage != 'CKD初診+DM複診')) || (his.rcode == 'P4302C' && (his.cstage != 'CKD+DM複診' && his.cstage != 'CKD複診+DM年度'))" style="background-color:red">{{ his.cdate }}</td>
                  <td v-else style="background-color:#82f282">{{ his.cdate }}</td>
                </tr>
                </tbody>
              </table>
              <div class="aside-footer">
                <button type="button" class="btn btn-success" @click="checkCorrect">核對無誤</button>
                <button type="button" class="btn btn-default" @click="showRight = false">Close</button>
              </div>
            </sidebar>
          </div>

          <h3>3.匯出資料:</h3>
          <div style="border: 2px solid #a1a1a1; margin-top: 15px; padding: 15px;">
            <div style="color:red"><strong>請務必在匯出資料後，要存入歷史記錄，若未執行將無記錄!!</strong></div>
            <!-- a href="/api/cases/downloadExcel/xls"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 下載 Excel xls</button></a -->
            <!-- a href="/api/cases/downloadExcel/xlsx"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 下載 Excel xlsx</button></a -->
            <div><a href="/api/cases/downloadtxt"><button class="btn btn-warning"><i class="fa fa-cloud-download" aria-hidden="true"></i> 匯出 TXT</button></a><br><br></div>
            <button type="button" class="btn btn-info btn-flat" @click="saveHistory"><i class="fa fa-compress" aria-hidden="true"></i> 將資料存入歷史記錄</button>
            <div class="progress">
              <progressbar :now="historyBar" label type="info" striped animated></progressbar>
            </div>
          </div>
          <!-- a href="/api/cases/"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 存入歷史記錄</button></a -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
              <p><pre>檢驗歷史資料 : {{ checkhistory.length}}</pre></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
              <p><pre>上傳歷史資料 : {{ vpnhistory.length }}</pre></p>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="alert alert-danger" role="alert" v-if="error">
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      {{ errors }}
    </div>
    <span v-for="error in errors" class="text-danger">{{ error }}</span>
    <div class="form-group content">
      <div class="input-group hidden has-feedback" :class="{'has-error':errors.content}">
        <span class="input-group-addon">Content</span>
        <textarea class="form-control content" name="content" v-model="content"></textarea>
        <span class="fa fa-warning-sign form-control-feedback"></span>
      </div>
    </div>
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import docCode from './vue-strap/src/docCode.vue'
  import sidebar from './vue-strap/src/Aside.vue'
  import progressbar from './vue-strap/src/Progressbar.vue'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info, show_stack_notice } from '../Pnotice.js'

  export default {
    http: {
      root: 'dashboard',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('content')
      }
    },
    components: {
      docCode,
      sidebar,
      progressbar
    },
    data () {
      return {
        // mcode: null
        // error: false,
        dynamicData: 0,
        historyBar: 0,
        token: csrf_token,
        content: '',
        errors: [],
        showRight: false,
        showLeft: false,
        checkRight: false,
        listpid: {},
        listpid1: {},
        hiscase: {},
        dupcase: {},
        checks: {},
        checkhistory: {},
        realsun: {},
        rawdatavpn: {},
        vpnhistory: {}
      }
    },
    created () {
      // this.mcode = this.$route.params.mcode
      this.fetchOwnUser()
      this.fetchChecks()
      this.fetchCheckhistory()
      this.fetchRealsun()
      this.fetchRawdatavpn()
      this.fetchVpnhistory()
    },
    methods: {
      fetchChecks () {
        this.$http({url: '/api/checks/all', method: 'GET'})
          .then(function (response) {
            this.$set('checks', response.data.data)
          })
      },
      fetchCheckhistory () {
        this.$http({url: '/api/checkhistory/all', method: 'GET'})
          .then(function (response) {
            this.$set('checkhistory', response.data.data)
          })
      },
      fetchRealsun () {
        this.$http({url: '/api/realsun/all', method: 'GET'})
          .then(function (response) {
            this.$set('realsun', response.data.data)
          })
      },
      fetchRawdatavpn () {
        this.$http({url: '/api/rawdatavpn/all', method: 'GET'})
          .then(function (response) {
            this.$set('rawdatavpn', response.data.data)
          })
      },
      fetchVpnhistory () {
        this.$http({url: '/api/vpnhistory/all', method: 'GET'})
          .then(function (response) {
            this.$set('vpnhistory', response.data.data)
          })
      },
      fetchOwnUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
//            this.$set('userowner', response.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      importCSVData (event) {
        if (confirm("確定匯入嗎?!")) {
          // var file = $('input[type=file]').val()
          var files = document.getElementById('importCSVFile').files[0]
          if (typeof files === 'undefined' || files === null || files === '') {
            alert('未選擇...匯入檔案!!')
            return false
          } else {
            var filename = files.name
            // check file's extension
            var a = filename.toLowerCase().indexOf("csv")
            if (a < 0) {
              alert('您選擇的[檔案格式(或檔案類型)]錯誤, 請指定 CSV 檔案!!')
              $("#importCSVFile").val('')
              return false
            } else {
              show_stack_notice('檢驗數據匯入中--請稍候!!', '')
              // ASCII to UTF-8
              var data = new FormData()
              data.append('importCSVFile', files)
//              data.append('importCSVFile', blob)
              this.$http.post('/api/cases/importCSV', data)
                .then(function (response) {
                  if (response.data.status == 'error') {
                    if (response.data.message == 'Database error::23000') {
                      show_stack_error('檢驗數據匯入失敗--有重複項目!!', response)
                    } else {
                      show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                    }
                    $("#importCSVFile").val('')
                    return false
                  } else {
                    // disable
                    // document.getElementById("importCSVFile").disabled = true
                    // document.getElementById("importCSVButton").disabled = true
                    show_stack_info('檢驗數據已匯入成功!!', response)
                    this.dynamicData = 8 // 進度表
                    // update A1C
                    show_stack_notice('檢驗數據更新方案中--請稍候!!', '')
                    this.$http.post('/api/cases/updatea1c', '')
                      .then(function (response) {
                        if (response.data.status == 'error') {
                          show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                          return false
                        } else {
                          show_stack_info('檢驗[A1C]數據已更新成功!!', response)
                          this.dynamicData = 16 // 進度表
                          // update CHO
                          // show_stack_notice('檢驗[CHO]數據更新方案中--請稍候!!', '')
                          this.$http.post('/api/cases/updatecho', '')
                            .then(function (response) {
                              if (response.data.status == 'error') {
                                show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                return false
                              } else {
                                show_stack_info('檢驗[CHO]數據已更新成功!!', response)
                                this.dynamicData = 24 // 進度表
                                // update TG
                                // show_stack_notice('檢驗[TG]數據更新方案中--請稍候!!', '')
                                this.$http.post('/api/cases/updatetg', '')
                                  .then(function (response) {
                                    if (response.data.status == 'error') {
                                      show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                      return false
                                    } else {
                                      show_stack_info('檢驗[TG]數據已更新成功!!', response)
                                      this.dynamicData = 32 // 進度表
                                      // update LDL
                                      // show_stack_notice('檢驗[LDL]數據更新方案中--請稍候!!', '')
                                      this.$http.post('/api/cases/updateldl', '')
                                        .then(function (response) {
                                          if (response.data.status == 'error') {
                                            show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                            return false
                                          } else {
                                            show_stack_info('檢驗[LDL]數據已更新成功!!', response)
                                            this.dynamicData = 40 // 進度表
                                            // update HDLC
                                            // show_stack_notice('檢驗[HDLC]數據更新方案中--請稍候!!', '')
                                            this.$http.post('/api/cases/updatehdlc', '')
                                              .then(function (response) {
                                                if (response.data.status == 'error') {
                                                  show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                  return false
                                                } else {
                                                  show_stack_info('檢驗[HDLC]數據已更新成功!!', response)
                                                  this.dynamicData = 48 // 進度表
                                                  // update GPT
                                                  // show_stack_notice('檢驗[GPT]數據更新方案中--請稍候!!', '')
                                                  this.$http.post('/api/cases/updategpt', '')
                                                    .then(function (response) {
                                                      if (response.data.status == 'error') {
                                                        show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                        return false
                                                      } else {
                                                        show_stack_info('檢驗[GPT]數據已更新成功!!', response)
                                                        this.dynamicData = 56 // 進度表
                                                        // update CRE
                                                        // show_stack_notice('檢驗[CRE]數據更新方案中--請稍候!!', '')
                                                        this.$http.post('/api/cases/updatecre', '')
                                                          .then(function (response) {
                                                            if (response.data.status == 'error') {
                                                              show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                              return false
                                                            } else {
                                                              show_stack_info('檢驗[CRE]數據已更新成功!!', response)
                                                              this.dynamicData = 64 // 進度表
                                                              // update UA
                                                              // show_stack_notice('檢驗[UA]數據更新方案中--請稍候!!', '')
                                                              this.$http.post('/api/cases/updateua', '')
                                                                .then(function (response) {
                                                                  if (response.data.status == 'error') {
                                                                    show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                                    return false
                                                                  } else {
                                                                    show_stack_info('檢驗[UA]數據已更新成功!!', response)
                                                                    this.dynamicData = 72 // 進度表
                                                                    // update MIC
                                                                    // show_stack_notice('檢驗[MIC]數據更新方案中--請稍候!!', '')
                                                                    this.$http.post('/api/cases/updatemic', '')
                                                                      .then(function (response) {
                                                                        if (response.data.status == 'error') {
                                                                          show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                                          return false
                                                                        } else {
                                                                          show_stack_info('檢驗[MIC]數據已更新成功!!', response)
                                                                          this.dynamicData = 80 // 進度表
                                                                          // update PCR
                                                                          // show_stack_notice('檢驗[PCR]數據更新方案中--請稍候!!', '')
                                                                          this.$http.post('/api/cases/updatepcr', '')
                                                                            .then(function (response) {
                                                                              if (response.data.status == 'error') {
                                                                                show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                                                return false
                                                                              } else {
                                                                                show_stack_info('檢驗[PCR]數據已更新成功!!', response)
                                                                                this.dynamicData = 88 // 進度表
                                                                                // update UPRO
                                                                                // show_stack_notice('檢驗[UPRO]數據更新方案中--請稍候!!', '')
                                                                                this.$http.post('/api/cases/updateupro', '')
                                                                                  .then(function (response) {
                                                                                    if (response.data.status == 'error') {
                                                                                      show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                                                      return false
                                                                                    } else {
                                                                                      show_stack_info('檢驗[UPRO]數據已更新成功!!', response)
                                                                                      this.dynamicData = 92 // 進度表
                                                                                      // update GFR
                                                                                      // show_stack_notice('檢驗[GFR]數據更新方案中--請稍候!!', '')
                                                                                      this.$http.post('/api/cases/updategfr', '')
                                                                                        .then(function (response) {
                                                                                          if (response.data.status == 'error') {
                                                                                            show_stack_error('檢驗數據匯入失敗--' + response.data.message, response)
                                                                                            return false
                                                                                          } else {
                                                                                            show_stack_info('檢驗[GFR]數據已更新成功!!', response)
                                                                                            this.dynamicData = 100 // 進度表
                                                                                          }
                                                                                        })
                                                                                    }
                                                                                  })
                                                                              }
                                                                            })
                                                                        }
                                                                      })
                                                                  }
                                                                })
                                                            }
                                                          })
                                                      }
                                                    })
                                                }
                                              })
                                          }
                                        })
                                    }
                                  })
                              }
                            })
                        }
                      })

                  }
                })
            }
          }
        }
      },
      checkCSVData() {
        this.$http({url: '/api/cases/checkcsv', method: 'GET'})
          .then(function (response) {
            if (response.data.length == 0) {
              this.$set('listpid', [{pid: '無資料'}])
            } else {
              this.$set('listpid', response.data)
            }
          })
          .catch(function (response) {
            console.log(response)
          })

        this.$http({url: '/api/cases/checkcsv1', method: 'GET'})
          .then(function (response) {
            if (response.data.length == 0) {
              this.$set('listpid1', [{pid: '無資料'}])
            } else {
              this.$set('listpid1', response.data)
            }
          })
          .catch(function (response) {
            console.log(response)
          })

        this.checkRight = true
      },
      compareCases() {
        this.$http({url: '/api/cases/comparecases', method: 'GET'})
          .then(function (response) {
            if (response.data.length == 0) {
              this.$set('hiscase', [{rcode: '無資料', rdate: '', rpid: '', cstage: '', cdate: '', cpid: ''}])
            } else {
              this.$set('hiscase', response.data)
            }
          })
          .catch(function (response) {
            console.log(response)
          })

        this.showRight = true
      },
      duplicateCases() {
        this.$http({url: '/api/cases/duplicatecases', method: 'GET'})
          .then(function (response) {
            if (response.data.length == 0) {
              this.$set('dupcase', [{cure_stage: '無資料', case_date: '', pid: '', n: ''}])
            } else {
              this.$set('dupcase', response.data)
            }
          })
          .catch(function (response) {
            console.log(response)
          })

        this.showLeft = true
      },
      checkCorrect() {
        if (confirm("確定資料核對正確, 準備匯出、上傳嗎?!")) {
          show_stack_notice('整理VPN上傳資料中--請稍候!!', '')
          this.$http.post('/api/cases/checkcorrect', '')
            .then(function (response) {
              if (response.data.status == 'error') {
                show_stack_error('整理VPN上傳資料失敗--' + response.data.message, response)
                return false
              } else {
                show_stack_info('整理VPN上傳資料成功, 可以匯出資料了!!', response)
              }
            })
        }
      },
      importXLSData(event) {
        if (confirm("確定匯入嗎?!")) {
          var files = document.getElementById('importXLSFile').files[0]
          if (typeof files === 'undefined' || files === null || files === '') {
            alert('未選擇...匯入檔案!!')
            return false
          } else {
            var filename = files.name
            // check file's extension
            var a = filename.toLowerCase().indexOf("xls")
            if (a < 0) {
              alert('您選擇的[檔案格式(或檔案類型)]錯誤, 請指定 Excel(xls, xlsx)檔案!!')
              $("#importXLSFile").val('')
              return false
            } else {
              show_stack_notice('HIS數據匯入中--請稍候!!', '')
              var dcode = null
              var dc = filename.toLowerCase().indexOf("p1407c")
              if (dc >= 0) dcode = 7
              var dc = filename.toLowerCase().indexOf("p1408c")
              if (dc >= 0) dcode = 8
              var dc = filename.toLowerCase().indexOf("p1409c")
              if (dc >= 0) dcode = 9
              var dc = filename.toLowerCase().indexOf("p4301c")
              if (dc >= 0) dcode = 1
              var dc = filename.toLowerCase().indexOf("p4302c")
              if (dc >= 0) dcode = 2
              var data = new FormData()
              data.append('importXLSFile', files)
              this.$http.post('/api/cases/importexcel/' + dcode, data)
                .then(function (response) {
//                  console.log(response.data.status)
//                  console.log(response.data.message)
//                  return false
                  if (response.data.status == 'error') {
                    if (response.data.message == 'Database error::23000') {
                      show_stack_error('HIS數據--重複，匯入失敗!!', response)
//                      alert('HIS數據--有重複項目，匯入失敗!!')
                    } else {
                      show_stack_error('HIS數據匯入失敗--' + response.data.message, response)
//                      alert('HIS數據匯入失敗--' + response.data.message)
                    }
                    $("#importXLSFile").val('')
                    return false
                  } else {
                    show_stack_info('HIS數據已匯入成功!!', response)
//                    this.dynamicData = 33 // 進度表
//                    this.$router.go('/')
                    // document.getElementById("importXLSFile").disabled = true
                    // document.getElementById("importXLSButton").disabled = true
                  }
                })
            }
          }
        }
      },
      deleteRawdatavpn(event) {
        let self = this
        swal({
          title: '您確定刪除嗎?!',
          text: '您將無法救回比對的資料!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.$http.delete('/api/cases/destroyrawdatavpn').then(function (response) {
            self.$router.go('/checkcases')
            swal(
              '已刪除!!',
              '比對的資料已經刪除!!',
              'success'
            )
          }, function (response){
            show_stack_error('刪除比對資料發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '比對資料維持現狀 :)',
              'error'
            )
          }
        })
      },
      deleteXLSData(event) {
        let self = this
        swal({
          title: '您確定刪除嗎?!',
          text: '您將無法救回HIS匯入的資料!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.$http.delete('/api/cases/destroyExcel').then(function (response) {
            self.$router.go('/checkcases')
            swal(
              '已刪除!!',
              'HIS匯入的資料已經刪除!!',
              'success'
            )
          }, function (response){
            show_stack_error('刪除HIS匯入的資料發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              'HIS匯入的資料維持現狀 :)',
              'error'
            )
          }
        })
      },
      deleteCSVData(event) {
        let self = this
        swal({
          title: '您確定刪除嗎?!',
          text: '您將無法救回檢驗匯入的資料!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.$http.delete('/api/cases/destroychecks').then(function (response) {
            self.$router.go('/checkcases')
            swal(
              '已刪除!!',
              '檢驗匯入的資料已經刪除!!',
              'success'
            )
          }, function (response){
            show_stack_error('刪除檢驗匯入的資料發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '檢驗匯入的資料維持現狀 :)',
              'error'
            )
          }
        })
      },
      saveHistory() {
        if (confirm("確定存入歷史記錄嗎?!")) {
          show_stack_notice('檢驗數據存入歷史記錄中--請稍候!!', '')
          this.$http.post('/api/cases/insertcheckhistory', '')
            .then(function (response) {
              if (response.data.status == 'error') {
                show_stack_error('檢驗數據存入歷史記錄失敗--' + response.data.message, response)
                return false
              } else {
                show_stack_info('檢驗數據存入歷史記錄成功!!', response)
                this.historyBar = 25
                this.$http.delete('/api/cases/destroychecks')
                  .then(function (response) {
                    if (response.data.status == 'error') {
                      show_stack_error('刪除檢驗匯入數據失敗--' + response.data.message, response)
                      return false
                    } else {
                      show_stack_info('檢驗匯入數據已刪除成功!!', response)
                      this.historyBar = 50
                      this.$http.post('/api/cases/insertvpnhistory', '')
                        .then(function (response) {
                          if (response.data.status == 'error') {
                            show_stack_error('VPN上傳數據存入歷史記錄失敗--' + response.data.message, response)
                            return false
                          } else {
                            show_stack_info('VPN上傳數據存入歷史記錄成功!!', response)
                            this.historyBar = 75
                            this.$http.delete('/api/cases/destroyrawdatavpn')
                              .then(function (response) {
                                if (response.data.status == 'error') {
                                  show_stack_error('刪除VPN上傳數據失敗--' + response.data.message, response)
                                  return false
                                } else {
                                  show_stack_info('VPN上傳數據已刪除成功!!', response)
                                  this.historyBar = 100 // 進度表
                                }
                              })
                          }
                        })
                    }
                  })
              }
            })
        }
      }
    }
  }
</script>

<style>
  /* body { */
    /* background-color: #ff0000; */
    .panel-primary > .panel-heading {
      background-image: none !important;
      background-color: red !important;
      color: white !important;
    }
  /* } */
</style>
