<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>健保方案(歷史記錄[檢驗])管理</h4>
      <h5><p>
        您可以在此管理::[<b>檢驗歷史記錄</b>]--(<b>本共照平台的病患</b>)，功能有：<b>查看、即時查詢</b>...等
      </p></h5>
    </div>
    <!-- button type="button" @click="printDiv" class="btn btn-md btn-info btn-flat" style="margin-bottom: 10px;"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>&nbsp; -->
    <!-- a href="/api/checkhistory/export_excel" -->
      <!-- button type="button" class="btn btn-md btn-warning btn-flat" onclick="return confirm('確定匯出嗎?!')" style="margin-bottom: 10px;"><i class="fa fa-download" aria-hidden="true"></i> 匯出Excel</button -->
    <!-- /a -->
    <i id="filtersubmit" class="fa fa-search" aria-hidden="true"></i>
    <input v-model="searchKey" type="text" class="input-lg search-query" style="margin-bottom: 12px; border-radius: 22px;" placeholder="請輸入搜尋字詞..." />
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有檢驗記錄</h3>
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
                  <a href="#" @click="sortBy('check_date')" :class="{ active: sortKey == 'check_date' }">送檢日期
                    <i class="fa {{ faSortcheckdate }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('name')" :class="{ active: sortKey == 'name' }">姓名
                    <i class="fa {{ faSortname }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('birthday')" :class="{ active: sortKey == 'birthday' }">生日
                    <i class="fa {{ faSortbirthday }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('pid')" :class="{ active: sortKey == 'pid' }">身分證號
                    <i class="fa {{ faSortpid }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('items')" :class="{ active: sortKey == 'items' }">檢驗項目
                    <i class="fa {{ faSortitems }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('values')" :class="{ active: sortKey == 'values' }">檢驗結果
                    <i class="fa {{ faSortvalues }}" aria-hidden="true"></i>
                  </a>
                </th>
              </tr>
              <tr v-for="chk in checkhistory | filterBy searchKey | orderBy sortKey reverse">
                <td>{{ chk.check_date }}</td>
                <td>{{ chk.name }}</td>
                <td>{{ chk.birthday }}</td>
                <td>{{ chk.pid }}</td>
                <td>{{ chk.items }}</td>
                <td>{{ chk.values }}</td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="checkhistory" :resource_url="resource_url" :options="options"></v-paginator>
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
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      VPaginator: VuePaginator
    },
    created () {
      this.checkId = this.$route.params.checkid
    },
    ready () {
      this.fetchCheckhistory()
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
        pages: [20, 50, 100, 500, 1000, 2000, 5000, 10000],
        thispage: 20,
        checkId: '',
        hospId: '',
        userowner: {},
        checkhistory: [],
        sortKey: '',
        reverse: 1,
        faSortcheckdate: 'fa-sort',
        faSortname: 'fa-sort',
        faSortbirthday: 'fa-sort',
        faSortpid: 'fa-sort',
        faSortitems: 'fa-sort',
        faSortvalues: 'fa-sort',
        searchKey: ''
      }
    },
    methods: {
      fetchCheckhistory () {
        this.$http({url: '/api/checkhistory/showpage/' + this.thispage, method: 'GET'})
          .then(function (response) {
//            console.log(response.data)
            this.$set('checkhistory', response.data.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      printDiv () {
        if (confirm("確定列印嗎?!")) {
          var divstr = document.getElementById("printpage").innerHTML;
          var header = '<header><div align="center"><h3 style="color:#EB5005">列印檢驗清單</h3></div><br></header><hr><br>';
          var footer = "";
          var windowUrl = "about:blank";
          var popupWin = window.open(windowUrl, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0');
          popupWin.document.open();
          popupWin.document.write('<html><head><title>檢驗清單</title><style type="text/css">@media print{@page {size:portrait;}} table{border:1px solid black;} th{border:1px solid black;padding:5px;background-color:grey;color:white;} td{border:1px solid black;padding:5px;}</style></head><body onload="window.print()"><p style="font-size: xx-small;">' + divstr + '</p></body></html>' + footer);
          popupWin.document.close();
        }
      },
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse;
        this.sortKey = sortKey;
        if (sortKey == 'check_date') {
          (this.reverse == 1) ? this.faSortcheckdate = "fa-sort-asc" : this.faSortcheckdate = "fa-sort-desc";
        } else {
          this.faSortcheckdate = 'fa-sort';
        }
        if (sortKey == 'name') {
          (this.reverse == 1) ? this.faSortname = "fa-sort-asc" : this.faSortname = "fa-sort-desc";
        } else {
          this.faSortname = 'fa-sort';
        }
        if (sortKey == 'birthday') {
          (this.reverse == 1) ? this.faSortbirthday = "fa-sort-asc" : this.faSortbirthday = "fa-sort-desc";
        } else {
          this.faSortbirthday = 'fa-sort';
        }
        if (sortKey == 'pid') {
          (this.reverse == 1) ? this.faSortpid = "fa-sort-asc" : this.faSortpid = "fa-sort-desc";
        } else {
          this.faSortpid = 'fa-sort';
        }
        if (sortKey == 'items') {
          (this.reverse == 1) ? this.faSortitems = "fa-sort-asc" : this.faSortitems = "fa-sort-desc";
        } else {
          this.faSortitems = 'fa-sort';
        }
        if (sortKey == 'values') {
          (this.reverse == 1) ? this.faSortvalues = "fa-sort-asc" : this.faSortvalues = "fa-sort-desc";
        } else {
          this.faSortvalues = 'fa-sort';
        }
      },
      showPage (page) {
        this.$http({url: '/api/checkhistory/showpage/' + page, method: 'GET'}).then(function (response) {
          this.$set('checkhistory', response.data.data)
          this.$set('thispage', page)
        })
      }
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/checkhistory/' + this.checkId) {
          return '/api/checkhistory/' + this.checkId
        } else {
          return '/api/checkhistory/showpage/' + this.thispage
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
