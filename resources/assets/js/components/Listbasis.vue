<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>病歷搜尋</h4>
      <h5><p>
        您可以在此搜尋::[<b>基本資料</b>]--(<b>本共照平台的病患</b>)，功能有：<b>即時查詢、血糖資料</b>...等
      </p></h5>
    </div>

    <!-- form-group -->
      <div class="row">
        <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">身份證號</span></label>
        <div class="col-xs-7">
          <bs-input name="pid" :value.sync="baseBy.pid" pattern="^[A-Z][1-2][0-9]+$" maxlength="10" :mask="mask"></bs-input>
        </div>
        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2 col-xs-offset-1" for="name"><span class="text-danger">姓名</span></label>
        <div class="col-xs-7">
          <bs-input name="name" :value.sync="baseBy.name"></bs-input>
        </div>
        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2 col-xs-offset-1" for="birthday"><span class="text-danger">出生日期</span></label>
        <div class="col-xs-7">
          <bs-input name="birthday" :value.sync="baseBy.birthday" pattern="^[0-9]+$" maxlength="7" placeholder="請輸入...民國 3位數, 例: 0501231"></bs-input>
        </div>
        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
      </div>
    <!-- /form-group -->
    <div class="col-xs-12 col-xs-offset-3">
      <button type="button" class="btn btn-warning" @click="fetchBase(baseBy)"><i class="fa fa-search"></i>查詢</button>
    </div>
    <div class="col-xs-12"><hr></div>

    <!-- i id="filtersubmit" class="fa fa-search" aria-hidden="true"></i -->
    <!-- input v-model="searchKey" type="text" class="input-lg search-query" style="margin-bottom: 12px; border-radius: 22px;" placeholder="請輸入搜尋字詞..." / -->
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有病歷</h3>
          </div>
          <!-- pagination -->
          <div class="dataTables_length" id="page_length">
            <label for="thispage">Show<i class="fa fa-eye" aria-hidden="true"></i></label>
              <select v-model="thispage" name="page_length" @change="showPage(thispage)">
                <option v-for="thispage in pages" :value="thispage">{{ thispage }}</option>
              </select> 筆數
          </div>
          <!-- /.box-header -->
          <div id="printpage" class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped">
              <tr>
                <th>
                  <a href="#" @click="sortBy('pid')" :class="{ active: sortKey == 'pid' }">身分證號
                    <i class="fa {{ faSortpid }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('name')" :class="{ active: sortKey == 'name' }">病患姓名
                    <i class="fa {{ faSortname }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('birthday')" :class="{ active: sortKey == 'birthday' }">生日
                    <i class="fa {{ faSortbirthday }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>醫事機構代碼</th>
                <th>功能</th>
              </tr>
              <tr v-for="base in basis | filterBy searchKey | orderBy sortKey reverse">
                <td>{{ base.pid }}</td>
                <td>{{ base.name }}</td>
                <td>{{ base.birthday }}</td>
                <td>{{ base.hosp_id }}</td>
                <td class="col-md-3">
                  <div class="btn-group">
                    <button v-link="{ name: 'showmresult', params: { basePid: base.pid } }" type="button" class="btn btn-info">血糖管理</button>
                    <button v-link="{ name: 'batchmresult', params: { basePid: base.pid } }" type="button" class="btn btn-warning">批次增</button>
                    <button v-link="{ name: 'batchdelbg', params: { basePid: base.pid } }" type="button" class="btn btn-danger">批次刪</button>
                  </div>
                </td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="basis" :resource_url="resource_url" :options="options"></v-paginator>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'
  import bsInput from './vue-strap/src/Input.vue'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      VPaginator: VuePaginator,
      bsInput
    },
    created () {
      this.baseId = this.$route.params.baseid
    },
    ready () {
      this.fetchUser()
      // this.fetchBasis()
    },
    data () {
      return {
        token: csrf_token,
        options: {
          remote_data: 'data',
          remote_current_page: 'current_page',
          remote_last_page: 'last_page',
          remote_next_page_url: 'next_page_url',
          remote_prev_page_url: 'prev_page_url'
        },
        pages: [20, 50, 100, 500, 1000],
        thispage: 20,
        baseId: '',
        userowner: {},
        basis: [],
        baseBy: {},
        sortKey: '',
        reverse: 1,
        faSortpid: 'fa-sort',
        faSortname: 'fa-sort',
        faSortbirthday: 'fa-sort',
        searchKey: ''
      }
    },
    methods: {
      fetchUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
//            this.$set('hospId', response.data.hosp_id)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchBasis () {
//        this.$http({url: '/api/basis/showpage/' + this.thispage, method: 'GET'})
//          .then(function (response) {
//            this.$set('basis', response.data.data)
//          })
//          .catch(function(response) {
//            console.log(response)
//            if (response.data == "Unauthorized." && response.status == 401) {
//              alert('Auto Logout after idle for 20 mins!!')
//              window.location.assign('/auth/logout')
//            }
//          })
      },
      fetchBase (baseBy) {
        this.$http.patch('/api/basis/showby/' + this.thispage, baseBy).then(function (response) {
          // console.log(response.data.data)
          // show_stack_success('備註已更新!', response)
          this.$set('basis', response.data.data)
        }, function (response) {
          console.log(response)
          if (response.data == "Unauthorized." && response.status == 401) {
            alert('Auto Logout after idle for 20 mins!!')
            window.location.assign('/auth/logout')
          }
          // console.log(response.data.data)
          // show_stack_error('備註更新失敗!', response)
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
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse
        this.sortKey = sortKey
        if (sortKey == 'pid') {
          (this.reverse == 1) ? this.faSortpid = "fa-sort-asc" : this.faSortpid = "fa-sort-desc"
        } else {
          this.faSortpid = 'fa-sort'
        }
        if (sortKey == 'name') {
          (this.reverse == 1) ? this.faSortname = "fa-sort-asc" : this.faSortname = "fa-sort-desc"
        } else {
          this.faSortname = 'fa-sort'
        }
        if (sortKey == 'birthday') {
          (this.reverse == 1) ? this.faSortbirthday = "fa-sort-asc" : this.faSortbirthday = "fa-sort-desc"
        } else {
          this.faSortbirthday = 'fa-sort'
        }
      },
      showPage (page) {
        this.$http({url: '/api/basis/showpage/' + page, method: 'GET'}).then(function (response) {
          this.$set('basis', response.data.data)
          this.$set('thispage', page)
        })
      }
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/basis/' + this.baseId) {
          return '/api/basis/' + this.baseId
        } else {
          return '/api/basis/showpage/' + this.thispage
        }
      },
      isPage: function () {
        return (this.thispage == 20)
      },
    },
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
  a {
    font-weight: normal;
    color: blue;
  }
  a:active {
    font-weight: bold;
    color: black;
  }
  #filtersubmit {
    position: relative;
    z-index: 1;
    left: 7px;
    top: 1px;
    color: #7B7B7B;
    cursor: pointer;
    width: 0;
  }
</style>
