<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>基本資料匯入</h4>
      <h5><p>
        您可以在此匯入其他HIS醫療系統的::[<b>基本資料</b>]--(<b>其他系統的病患</b>)，功能有：<b>上傳、匯入、下載</b>...等
      </p></h5>
    </div>

    <div class="container">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title" style="padding: 12px 0px; font-size: 25px;">
            <strong>請注意...匯入資料的正確及完整 -- 此動作將會影響貴單位的資料庫!!</strong>
          </h3>
          <h4>
            註：<br>
            1.匯入的Excel資料欄位名稱(需為英文): [name,visitday,inform_addr,tel1,pid,birthday,diagnosis]<br>
            2.Excel內的儲存格格式請設定為: 文字<br>
            3.有關Excel內資料重複者, 請自行刪除<br>
            4.有關Excel內資料寬度有誤或資料內容錯誤者, 請自行修正<br>
          </h4>
        </div>
        <div class="panel-body">

          <h3>匯入資料:</h3>
          <form style="border: 2px solid #a1a1a1; margin-top: 15px; padding: 15px;" action="/api/basis/importExcel" class="form-horizontal" method="POST" enctype="multipart/form-data">
            <input type="file" id="importfile" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="importfile" />
            <!-- input v-el="importfile" type="file" name="importfile" id="importfile" v-on="change:importData" -->
            <br />
            <!-- button class="btn btn-primary"><i class="fa fa-cloud-upload" aria-hidden="true"></i> 匯入 Excel 資料</button -->
            <button type="button" @click="importData" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload" aria-hidden="true"></i> 匯入 Excel 資料</button>
          </form>
          <br />

          <h3>匯出資料:</h3>
          <div style="border: 2px solid #a1a1a1; margin-top: 15px; padding: 15px;">
            <a href="/api/basis/downloadExcel/xls"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 下載 Excel xls</button></a>
            <a href="/api/basis/downloadExcel/xlsx"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 下載 Excel xlsx</button></a>
            <a href="/api/basis/downloadExcel/csv"><button class="btn btn-success"><i class="fa fa-cloud-download" aria-hidden="true"></i> 下載 CSV</button></a>
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

  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    http: {
      root: 'dashboard',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('content')
      }
    },
    data () {
      return {
        // mcode: null
        // error: false,
        token: csrf_token,
        content: '',
        errors: []
      }
    },
    created () {
      // this.mcode = this.$route.params.mcode
    },
    methods: {
      importData (event) {
        if (confirm("確定匯入嗎?!")) {
          var files = document.getElementById('importfile').files[0]
          if (typeof files === 'undefined' || files === null || files === '') {
            alert('未選擇...匯入檔案!!')
            return false
          } else {
            var filename = files.name
            // check file's extension
            var a = filename.toLowerCase().indexOf("xls")
            if (a < 0) {
              alert('您選擇的[檔案格式(或檔案類型)]錯誤, 請指定 Excel(xls, xlsx)檔案!!')
              $("#importfile").val('')
              return false
            } else {
              var data = new FormData()
              data.append('importfile', files)
              // for single file
              this.$http.post('/api/basis/importExcel', data)
                .then(function (response) {
                  // alert('異質基本資料已匯入成功!!')
                  if (response.data.status == 'error') {
                    if (response.data.message == 'Database error::23000') {
                      // show_stack_error('異質基本資料--重複，匯入失敗!!', response)
                      alert('異質基本資料--有重複項目，匯入失敗!!')
                      $("#importfile").val('')
                    } else {
                      // show_stack_error(response.data.message, response)
                      alert(response.data.message)
                      $("#importfile").val('')
                    }
                  } else {
                    show_stack_info('異質基本資料已匯入成功!!', response)
                    this.$router.go('/')
                  }
                })
                .catch(function(response) {
                  console.log(response)
                  if (response.data == "Unauthorized." && response.status == 401) {
                    alert('Auto Logout after idle for 20 mins!!')
                    window.location.assign('/auth/logout')
                  }
                  $("#importfile").val('')
                })
            }
          }
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
