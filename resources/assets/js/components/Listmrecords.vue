<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>衛教記錄(SOAP清單)</h4>
      <h5><p>
        您可以在此管理::[<b>衛教記錄</b>]--(<b>本共照平台的病患</b>)，功能有：<b>修改、刪除、查詢</b>...等
      </p></h5>
    </div>
    <!-- button v-if="isAdmin" type="button" @click="createMrecord" class="btn btn-md btn-primary btn-flat" style="margin-bottom: 10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> 新增</button>&nbsp; -->
    <!-- button type="button" @click="printDiv" class="btn btn-md btn-info btn-flat" style="margin-bottom: 10px;"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>&nbsp; -->
    <!-- a href="/api/mrecords/export_excel" -->
      <!-- button type="button" class="btn btn-md btn-warning btn-flat" onclick="return confirm('確定匯出嗎?!')" style="margin-bottom: 10px;"><i class="fa fa-download" aria-hidden="true"></i> 匯出Excel</button -->
    <!-- /a -->
    <i id="filtersubmit" class="fa fa-search" aria-hidden="true"></i>
    <input v-model="searchKey" type="text" class="input-lg search-query" style="margin-bottom: 12px; border-radius: 22px;" placeholder="請輸入搜尋字詞..." />
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有工作</h3>
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
                  <a href="#" @click="sortBy('name')" :class="{ active: sortKey == 'name' }">病患姓名
                    <i class="fa {{ faSortname }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('pid')" :class="{ active: sortKey == 'pid' }">身分證號
                    <i class="fa {{ faSortpid }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('soap_date')" :class="{ active: sortKey == 'soap_date' }">日期
                    <i class="fa {{ faSortsoapdate }}" aria-hidden="true"></i>
                </th>
                <th>
                  <a href="#" @click="sortBy('ename')" :class="{ active: sortKey == 'ename' }">個管人員
                    <i class="fa {{ faSortename }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>功能</th>
              </tr>
              <!-- tr v-for="mrecord in mrecords" -->
              <tr v-for="mrecord in mrecords | filterBy searchKey | orderBy sortKey reverse">
                <td>{{ mrecord.name }}</td>
                <td>{{ mrecord.pid }}</td>
                <td>{{ mrecord.soap_date }}</td>
                <td>{{ mrecord.ename }}</td>
                <td class="col-md-3">
                  <div class="btn-group">
                    <!-- a href="/api/me/{{-- mrecord.hashid --}}" target="blank" class="btn btn-success" role="button">看</a -->
                    <!-- button v-link="{ name: 'showmrecord', params: { hashid: s.hashid } }" type="button" class="btn btn-success">看</button -->
                    <button v-link="{ name: 'showmresult', params: { basePid: mrecord.pid } }" type="button" class="btn btn-info" v-if="mrecord.soapdate == null">新增</button>
                    <button v-link="{ name: '', params: { mrecordId: mrecord.id } }" type="button" class="btn btn-warning" v-else>修改</button>
                    <button type="button" class="btn btn-danger" @click="deleteMrecord(mrecord, $event)">刪除</button>
                  </div>
                </td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="mrecords" :resource_url="resource_url" :options="options"></v-paginator>
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
      this.mrecordId = this.$route.params.mrecordid
    },
    ready () {
      this.fetchUser()
      this.fetchMrecords()
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
        mrecordId: '',
        hospId: '',
        userowner: {},
        mrecords: {},
        sortKey: '',
        reverse: 1,
        faSortname: 'fa-sort',
        faSortpid: 'fa-sort',
        faSortsoapdate: 'fa-sort',
        faSortename: 'fa-sort',
        searchKey: ''
      }
    },
    methods: {
      fetchUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('hospId', response.data.hosp_id)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchMrecords () {
        this.$http({url: '/api/mrecords/showpage/' + this.thispage, method: 'GET'})
          .then(function (response) {
            // console.log(response.data.data)
            this.$set('mrecords', response.data.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      deleteMrecord (dmrecord, event) {
//        var cid = (event.currentTarget.id).substr(10)
        let self = this
//        console.log(dmrecord.cure_stage)
//        return false
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該筆工作清單!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.mrecords.$remove(dmrecord)
          self.$http.delete('/api/mrecords/' + dmrecord.id, dmrecord).then(function (response) {
            swal(
              '已刪除!!',
              '該筆工作清單已經刪除!!',
              'success'
            );
          }, function (response){
            show_stack_error('刪除工作清單發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該筆工作清單維持現狀 :)',
              'error'
            );
          }
        });
      },
      createMrecord () {
        if (confirm("確定新增嗎?!")) {
          this.$http({url: '/api/mrecords', method: 'POST'})
            .then(function (response) {
              show_stack_info('已新增', response)
              this.$router.go('/mrecords/' + response.data.id + '/edit')
            })
            .catch(function(response) {
              console.log(response)
              this.$router.go('/')
            })
        }
      },
      printDiv () {
        if (confirm("確定列印嗎?!")) {
          var divstr = document.getElementById("printpage").innerHTML;
          var header = '<header><div align="center"><h3 style="color:#EB5005">列印工作清單</h3></div><br></header><hr><br>';
          var footer = "";
          var windowUrl = "about:blank";
          var popupWin = window.open(windowUrl, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0');
          popupWin.document.open();
          popupWin.document.write('<html><head><title>工作清單</title><style type="text/css">@media print{@page {size:portrait;}} table{border:1px solid black;} th{border:1px solid black;padding:5px;background-color:grey;color:white;} td{border:1px solid black;padding:5px;}</style></head><body onload="window.print()"><p style="font-size: xx-small;">' + divstr + '</p></body></html>' + footer);
          popupWin.document.close();
        }
      },
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse;
        this.sortKey = sortKey;
        if (sortKey == 'name') {
          (this.reverse == 1) ? this.faSortname = "fa-sort-asc" : this.faSortname = "fa-sort-desc";
        } else {
          this.faSortname = 'fa-sort';
        }
        if (sortKey == 'pid') {
          (this.reverse == 1) ? this.faSortpid = "fa-sort-asc" : this.faSortpid = "fa-sort-desc";
        } else {
          this.faSortpid = 'fa-sort';
        }
        if (sortKey == 'soap_date') {
          (this.reverse == 1) ? this.faSortsoapdate = "fa-sort-asc" : this.faSortsoapdate = "fa-sort-desc";
        } else {
          this.faSortsoapdate = 'fa-sort';
        }
        if (sortKey == 'educator_user_id') {
          (this.reverse == 1) ? this.faSortename = "fa-sort-asc" : this.faSortename = "fa-sort-desc";
        } else {
          this.faSortename = 'fa-sort';
        }
      },
      showPage (page) {
        //if ($('select[name="page_length"] option[value="All"]').is(':selected')){
        //      this.thispage = page
        this.$http({url: '/api/mrecords/showpage/' + page, method: 'GET'}).then(function (response) {
          this.$set('mrecords', response.data.data)
          this.$set('thispage', page)
        })
      }
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/mrecords/' + this.mrecordId) {
          return '/api/mrecords/' + this.mrecordId
        } else {
          // return '/api/mrecords'
          return '/api/mrecords/showpage/' + this.thispage
        }
      },
      isPage: function () {
        // if ($('select[name="page_length"] option[value="10"]').is(':selected')){
        return (this.thispage == 20)
      }
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
