<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>使用者管理</h4>
      <h5><p>
        您可以在此管理::[<b>使用者</b>]--(<b>本共照平台的使用者</b>)，功能有：<b>新增、修改、刪除、查看、即時查詢、列印、匯出</b>...等
      </p></h5>
    </div>
    <button v-if="isAdmin" type="button" @click="createUser" class="btn btn-md btn-primary btn-flat" style="margin-bottom: 10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> 新增</button>&nbsp;
    <button type="button" @click="printDiv" class="btn btn-md btn-info btn-flat" style="margin-bottom: 10px;"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>&nbsp;
    <a href="/api/users/export_excel">
      <button type="button" class="btn btn-md btn-warning btn-flat" onclick="return confirm('確定匯出嗎?!')" style="margin-bottom: 10px;"><i class="fa fa-download" aria-hidden="true"></i> 匯出Excel</button>
    </a>
    <a id="downloadImage">
      <button type="button" class="btn btn-md btn-warning btn-flat" @click="convertDiv" style="margin-bottom: 10px;"><i class="fa fa-file-image-o" aria-hidden="true"></i> 匯出圖像</button>&nbsp;
    </a>
    <i id="filtersubmit" class="fa fa-search" aria-hidden="true"></i>
    <input v-model="searchKey" type="text" class="input-lg search-query" style="margin-bottom: 12px; border-radius: 22px;" placeholder="請輸入搜尋字詞..." />
    <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12">
        <div class="box" style="background-color: white">
          <div class="box-header">
            <h3 class="box-title">所有使用者</h3>
          </div>
          <!-- pagination -->
          <div class="dataTables_length" id="page_length">
            <label for="thispage">Show<i class="fa fa-eye" aria-hidden="true"></i></label>
              <select v-model="thispage" name="page_length" @change="showPage(thispage)">
                <option v-for="thispage in pages" :value="thispage">{{ thispage }}</option>
              </select> 筆數
          </div>
          <!-- /.box-header -->
          <div id="printpage" class="box-body table-responsive no-padding" style="background-color: white">
            <table class="table table-hover table-striped" style="background-color: white">
              <tr>
                <th>
                  <a href="#" @click="sortBy('hashid')" :class="{ active: sortKey == 'hashid' }">ID
                    <i class="fa {{ faSortid }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('name')" :class="{ active: sortKey == 'name' }">使用者
                    <i class="fa {{ faSortname }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  全名
                </th>
                <th>
                  <a href="#" @click="sortBy('hosp_id')" :class="{ active: sortKey == 'hosp_id' }">醫事機構代碼
                    <i class="fa {{ faSorthospid }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('email')" :class="{ active: sortKey == 'email' }">登入Email
                    <i class="fa {{ faSortemail }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>
                  <a href="#" @click="sortBy('bio')" :class="{ active: sortKey == 'bio' }">個人簡歷
                    <i class="fa {{ faSortbio }}" aria-hidden="true"></i>
                  </a>
                </th>
                <th>管理者</th>
                <th>病患</th>
                <th>功能</th>
              </tr>
              <!-- tr v-for="user in users" -->
              <tr v-for="user in users | filterBy searchKey | orderBy sortKey reverse">
                <td>{{ user.hashid }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.username }}</td>
                <td>{{ user.hosp_id }}</td>
                <td style ="word-break: break-all;">{{ user.email }}</td>
                <td>{{ user.bio }}</td>
                <td><span class="label label-success">{{ user.role_level >= 7 }}</span></td>
                <td><span class="label label-danger">{{ user.role_level == 0 }}</span></td>
                <td class="col-md-3">
                  <div class="btn-group">
                    <!-- a href="/api/me/{{-- user.hashid --}}" target="blank" class="btn btn-success" role="button">看</a -->
                    <button v-link="{ name: 'showuser', params: { hashid: user.hashid } }" type="button" class="btn btn-success">看</button>
                    <button v-if="isAdmin" v-link="{ name: 'edituser', params: { hashid: user.hashid } }" type="button" class="btn btn-info">改</button>
                    <button v-if="isAdmin" type="button" class="btn btn-danger" @click="deleteUser(user)">刪</button>
                  </div>
                </td>
              </tr>
            </table>
            <div>
              <v-paginator :resource.sync="users" :resource_url="resource_url" :options="options"></v-paginator>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
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
      this.userId = this.$route.params.hashid
    },
    ready () {
      this.fetchUser()
      this.fetchUsers()
    },
    data () {
      return {
        token: csrf_token,
        options: {
          remote_data: 'data',
          remote_current_page: 'meta.pagination.current_page',
          remote_last_page: 'meta.pagination.total_pages',
          remote_next_page_url: 'meta.pagination.links.next',
          remote_prev_page_url: 'meta.pagination.links.previous'
        },
        userowner: {},
        users: [],
        pages: [10, 25, 50, 100, 500, 1000],
        thispage: 10,
        userId: '',
        sortKey: '',
        reverse: 1,
        faSortid: 'fa-sort',
        faSortname: 'fa-sort',
        faSorthospid: 'fa-sort',
        faSortemail: 'fa-sort',
        faSortbio: 'fa-sort',
        searchKey: '',
      }
    },
    methods: {
      fetchUser () {
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
          })
          .catch(function(response) {
            console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
//              this.$http({url: 'logout', method: 'GET'}).then(function (response) {
                window.location.assign('/auth/logout')
//              })
            }
          })
      },
      fetchUsers () {
        this.$http({url: '/api/users', method: 'GET'})
          .then(function (response) {
            this.$set('users', response.data.data)
          })
      },
      deleteUser (user) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名使用者!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.users.$remove(user)
          self.$http.delete('/api/users/' + user.hashid, user).then(function (response) {
            swal(
              '已刪除!!',
              '該名使用者已經刪除!!',
              'success'
            );
          }, function (response){
            show_stack_error('刪除使用者發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名使用者維持現狀 :)',
              'error'
            );
          }
        });
      },
      createUser () {
        if (confirm("確定新增嗎?!")) {
          this.$http({url: '/api/users', method: 'POST'})
            .then(function (response) {
              show_stack_info('已新增', response)
              this.$router.go('/users/' + response.data.hashid + '/edit')
            })
            .catch(function(response) {
              console.log(response)
              this.$router.go('/')
            })
        }
      },
      printDiv () {
        if (confirm("確定列印嗎?!")) {
          var divstr = document.getElementById("printpage").innerHTML
          var header = '<header><div align="center"><h3 style="color:#EB5005">列印使用者清單</h3></div><br></header><hr><br>'
          var footer = ""
          var windowUrl = "about:blank"
          var popupWin = window.open(windowUrl, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0')
          popupWin.document.open()
          popupWin.document.write('<html><head><title>使用者清單</title><style type="text/css">@media print{@page {size:portrait;}} table{border:1px solid black;} th{border:1px solid black;padding:5px;background-color:grey;color:white;} td{border:1px solid black;padding:5px;}</style></head><body onload="window.print()"><p style="font-size: xx-small;">' + divstr + '</p></body></html>' + footer)
          popupWin.document.close()
        }
      },
      convertDiv () {
        if (confirm("確定匯出jpg圖檔嗎?!")) {
          var element = $("#printpage")
          html2canvas(element, {
            allowTaint: true,
            taintTest: false,
            logging: true,
            useCORS: true,
            onrendered: function(canvas) {
              var imageData = canvas.toDataURL("image/jpeg")
//              var myImage = canvas.toDataURL("image/png")
//              window.open(myImage, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0')
              $("#downloadImage").attr("download", "savetoimage.jpg").attr("href", imageData)
              document.getElementById('downloadImage').click()
            }
          })
        }
      },
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse;
        this.sortKey = sortKey;
        if (sortKey == 'hashid') {
          (this.reverse == 1) ? this.faSortid = "fa-sort-asc" : this.faSortid = "fa-sort-desc";
        } else {
          this.faSortid = 'fa-sort';
        }
        if (sortKey == 'name') {
          (this.reverse == 1) ? this.faSortname = "fa-sort-asc" : this.faSortname = "fa-sort-desc";
        } else {
          this.faSortname = 'fa-sort';
        }
        if (sortKey == 'hosp_id') {
          (this.reverse == 1) ? this.faSorthospid = "fa-sort-asc" : this.faSorthospid = "fa-sort-desc";
        } else {
          this.faSorthospid = 'fa-sort';
        }
        if (sortKey == 'email') {
          (this.reverse == 1) ? this.faSortemail = "fa-sort-asc" : this.faSortemail = "fa-sort-desc";
        } else {
          this.faSortemail = 'fa-sort';
        }
        if (sortKey == 'bio') {
          (this.reverse == 1) ? this.faSortbio = "fa-sort-asc" : this.faSortbio = "fa-sort-desc";
        } else {
          this.faSortbio = 'fa-sort';
        }
      },
      showPage (page) {
        //if ($('select[name="page_length"] option[value="All"]').is(':selected')){
  //      this.thispage = page
        this.$http({url: '/api/users/showpage/' + page, method: 'GET'}).then(function (response) {
          this.$set('users', response.data.data)
          this.$set('thispage', page)
        })
      }
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/users/' + this.userId) {
          return '/api/users/' + this.userId
        } else {
  //        return '/api/users'
          return '/api/users/showpage/' + this.thispage
        }
      },
      isAdmin: function () {
        return (this.userowner.role_level >= 7)
      },
      isPage: function () {
  //      if ($('select[name="page_length"] option[value="10"]').is(':selected')){
        return (this.thispage == 10)
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
