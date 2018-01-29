<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>健保方案(歷史記錄[VPN上傳])管理</h4>
      <h5><p>
        您可以在此管理::[<b>VPN上傳歷史記錄</b>]--(<b>本共照平台的病患</b>)，功能有：<b>查看、即時查詢</b>...等
      </p></h5>
    </div>
    <!-- button type="button" @click="printDiv" class="btn btn-md btn-info btn-flat" style="margin-bottom: 10px;"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>&nbsp; -->
    <!-- a href="/api/vpnhistory/export_excel" -->
      <!-- button type="button" class="btn btn-md btn-warning btn-flat" onclick="return confirm('確定匯出嗎?!')" style="margin-bottom: 10px;"><i class="fa fa-download" aria-hidden="true"></i> 匯出Excel</button -->
    <!-- /a -->
    <i id="filtersubmit" class="fa fa-search" aria-hidden="true"></i>
    <input v-model="searchKey" type="text" class="input-lg search-query" style="margin-bottom: 12px; border-radius: 22px;" placeholder="請輸入搜尋字詞..." />
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">所有VPN上傳記錄</h3>
          </div>
          <!-- pagination -->
          <div class="dataTables_length" id="page_length">
            <label for="thispage">Show<i class="fa fa-eye" aria-hidden="true"></i></label>
              <select v-model="thispage" name="page_length" @change="showPage(thispage)">
                <option v-for="thispage in pages" :value="thispage">{{ thispage }}</option>
              </select> 筆數
          </div>
        </div>
        <!-- /.box -->
      </div>
    </div>

    <div class="container-fluid0">
      <!-- /.box-header -->
      <div id="printpage" class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped">
          <tr>
            <th>
              <a href="#" @click="sortBy('segment')" :class="{ active: sortKey == 'segment' }">資料段
                <i class="fa {{ faSortsegment }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('branch_code')" :class="{ active: sortKey == 'branch_code' }">業務組別
                <i class="fa {{ faSortbranchcode }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('hosp_id')" :class="{ active: sortKey == 'hosp_id' }">醫事機構代碼
                <i class="fa {{ faSorthospid }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('pid')" :class="{ active: sortKey == 'pid' }">身分證號
                <i class="fa {{ faSortpid }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('birthday')" :class="{ active: sortKey == 'birthday' }">出生日期
                <i class="fa {{ faSortbirthday }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('prsn_id')" :class="{ active: sortKey == 'prsn_id' }">醫事人員身分證號
                <i class="fa {{ faSortprsnid }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('cure_stage')" :class="{ active: sortKey == 'cure_stage' }">診療階段
                <i class="fa {{ faSortcurestage }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('case_date')" :class="{ active: sortKey == 'case_date' }">收案日期
                <i class="fa {{ faSortcasedate }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('iopd_type')" :class="{ active: sortKey == 'iopd_type' }">門住診別
                <i class="fa {{ faSortiopdtype }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('fami_medic_his')" :class="{ active: sortKey == 'fami_medic_his' }">家族病史
                <i class="fa {{ faSortfamimedichis }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('f_medic_his')" :class="{ active: sortKey == 'f_medic_his' }">父系家族病史
                <i class="fa {{ faSortfmedichis }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('m_medic_his')" :class="{ active: sortKey == 'm_medic_his' }">母系家族病史
                <i class="fa {{ faSortmmedichis }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('c_medic_his')" :class="{ active: sortKey == 'c_medic_his' }">子女病史
                <i class="fa {{ faSortcmedichis }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('stage2_yn')" :class="{ active: sortKey == 'stage2_yn' }">是否為第二階段照護
                <i class="fa {{ faSortstage2yn }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('diabetes_type')" :class="{ active: sortKey == 'diabetes_type' }">糖尿病病型
                <i class="fa {{ faSortdiabetestype }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('fall_ill_year')" :class="{ active: sortKey == 'fall_ill_year' }">發病起始年
                <i class="fa {{ faSortfallillyear }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('base_chk_date')" :class="{ active: sortKey == 'base_chk_date' }">基本檢驗日期
                <i class="fa {{ faSortbasechkdate }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('base_tall')" :class="{ active: sortKey == 'base_tall' }">身高
                <i class="fa {{ faSortbasetall }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('base_weight')" :class="{ active: sortKey == 'base_weight' }">體重
                <i class="fa {{ faSortbaseweight }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('base_sbp')" :class="{ active: sortKey == 'base_sbp' }">血壓-收縮壓
                <i class="fa {{ faSortbasesbp }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('base_ebp')" :class="{ active: sortKey == 'base_ebp' }">血壓-舒張壓
                <i class="fa {{ faSortbaseebp }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_chk_date')" :class="{ active: sortKey == 'blood_chk_date' }">血液檢驗日期
                <i class="fa {{ faSortbloodchkdate }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_hba1c')" :class="{ active: sortKey == 'blood_hba1c' }">糖化血色素
                <i class="fa {{ faSortbloodhba1c }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_ldl')" :class="{ active: sortKey == 'blood_ldl' }">低密度脂蛋白
                <i class="fa {{ faSortbloodldl }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_ac')" :class="{ active: sortKey == 'blood_ac' }">飯前血糖值
                <i class="fa {{ faSortbloodac }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_pc')" :class="{ active: sortKey == 'blood_pc' }">飯後血糖值
                <i class="fa {{ faSortbloodpc }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_tg')" :class="{ active: sortKey == 'blood_tg' }">三酸甘油酯
                <i class="fa {{ faSortbloodtg }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('urine_chk_date')" :class="{ active: sortKey == 'urine_chk_date' }">尿液檢驗日期
                <i class="fa {{ faSorturinechkdate }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('urine_micro')" :class="{ active: sortKey == 'urine_micro' }">尿液微量白蛋白
                <i class="fa {{ faSorturinemicro }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('urine_routine')" :class="{ active: sortKey == 'urine_routine' }">尿液常規檢查
                <i class="fa {{ faSorturineroutine }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('year_mark')" :class="{ active: sortKey == 'year_mark' }">年度檢查註記
                <i class="fa {{ faSortyearmark }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('foot_chk_left')" :class="{ active: sortKey == 'foot_chk_left' }">左腳足部檢查
                <i class="fa {{ faSortfootchkleft }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('foot_chk_right')" :class="{ active: sortKey == 'foot_chk_right' }">右腳足部檢查
                <i class="fa {{ faSortfootchkright }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('blood_creat')" :class="{ active: sortKey == 'blood_creat' }">肌酸酐
                <i class="fa {{ faSortbloodcreat }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('eye_chk8')" :class="{ active: sortKey == 'eye_chk8' }">眼睛檢查及病變
                <i class="fa {{ faSorteyechk8 }}" aria-hidden="true"></i>
              </a>
            </th>
            <th>
              <a href="#" @click="sortBy('egfr')" :class="{ active: sortKey == 'egfr' }">腎絲球濾過率值
                <i class="fa {{ faSortegfr }}" aria-hidden="true"></i>
              </a>
            </th>
          </tr>
          <tr v-for="vpn in vpnhistory | filterBy searchKey | orderBy sortKey reverse">
            <td>{{ vpn.segment }}</td>
            <td>{{ vpn.branch_code }}</td>
            <td>{{ vpn.hosp_id }}</td>
            <td>{{ vpn.pid }}</td>
            <td>{{ vpn.birthday }}</td>
            <td>{{ vpn.prsn_id }}</td>
            <td>{{ vpn.cure_stage }}</td>
            <td>{{ vpn.case_date }}</td>
            <td>{{ vpn.iopd_type }}</td>
            <td>{{ vpn.fami_medic_his }}</td>
            <td>{{ vpn.f_medic_his }}</td>
            <td>{{ vpn.m_medic_his }}</td>
            <td>{{ vpn.c_medic_his }}</td>
            <td>{{ vpn.stage2_yn }}</td>
            <td>{{ vpn.diabetes_type }}</td>
            <td>{{ vpn.fall_ill_year }}</td>
            <td>{{ vpn.base_chk_date }}</td>
            <td>{{ vpn.base_tall }}</td>
            <td>{{ vpn.base_weight }}</td>
            <td>{{ vpn.base_sbp }}</td>
            <td>{{ vpn.base_ebp }}</td>
            <td>{{ vpn.blood_chk_date }}</td>
            <td>{{ vpn.blood_hba1c }}</td>
            <td>{{ vpn.blood_ldl }}</td>
            <td>{{ vpn.blood_ac }}</td>
            <td>{{ vpn.blood_pc }}</td>
            <td>{{ vpn.blood_tg }}</td>
            <td>{{ vpn.urine_chk_date }}</td>
            <td>{{ vpn.urine_micro }}</td>
            <td>{{ vpn.urine_routine }}</td>
            <td>{{ vpn.year_mark }}</td>
            <td>{{ vpn.foot_chk_left }}</td>
            <td>{{ vpn.foot_chk_right }}</td>
            <td>{{ vpn.blood_creat }}</td>
            <td>{{ vpn.eye_chk8 }}</td>
            <td>{{ vpn.egfr }}</td>
          </tr>
        </table>
        <div>
          <v-paginator :resource.sync="vpnhistory" :resource_url="resource_url" :options="options"></v-paginator>
        </div>
      </div>
      <!-- /.box-body -->
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
      this.vpnhistoryId = this.$route.params.vpnhistoryid
    },
    ready () {
      this.fetchVpnhistory()
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
        pages: [20, 50, 100, 500, 1000, 2500, 5000],
        thispage: 20,
        vpnhistoryId: '',
        hospId: '',
        userowner: {},
        vpnhistory: [],
        sortKey: '',
        reverse: 1,
        faSortsegment: 'fa-sort',
        faSortbranchcode: 'fa-sort',
        faSortpid: 'fa-sort',
        faSortbirthday: 'fa-sort',
        faSortprsnid: 'fa-sort',
        faSortcurestage: 'fa-sort',
        faSortcasedate: 'fa-sort',
        faSortiopdtype: 'fa-sort',
        faSortfamimedichis: 'fa-sort',
        faSortfmedichis: 'fa-sort',
        faSortmmedichis: 'fa-sort',
        faSortcmedichis: 'fa-sort',
        faSortstage2yn: 'fa-sort',
        faSortdiabetestype: 'fa-sort',
        faSortfallillyear: 'fa-sort',
        faSortbasechkdate: 'fa-sort',
        faSortbasetall: 'fa-sort',
        faSortbaseweight: 'fa-sort',
        faSortbasesbp: 'fa-sort',
        faSortbaseebp: 'fa-sort',
        faSortbloodchkdate: 'fa-sort',
        faSortbloodhba1c: 'fa-sort',
        faSortbloodldl: 'fa-sort',
        faSortbloodac: 'fa-sort',
        faSortbloodpc: 'fa-sort',
        faSortbloodtg: 'fa-sort',
        faSorturinechkdate: 'fa-sort',
        faSorturinemicro: 'fa-sort',
        faSorturineroutine: 'fa-sort',
        faSortyearmark: 'fa-sort',
        faSortfootchkleft: 'fa-sort',
        faSortfootchkright: 'fa-sort',
        faSortbloodcreat: 'fa-sort',
        faSorteyechk8: 'fa-sort',
        faSortegfr: 'fa-sort',
        searchKey: ''
      }
    },
    methods: {
      fetchVpnhistory () {
        this.$http({url: '/api/vpnhistory/showpage/' + this.thispage, method: 'GET'})
          .then(function (response) {
//            console.log(response.data)
            this.$set('vpnhistory', response.data.data)
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
          var header = '<header><div align="center"><h3 style="color:#EB5005">列印VPN上傳清單</h3></div><br></header><hr><br>';
          var footer = "";
          var windowUrl = "about:blank";
          var popupWin = window.open(windowUrl, '_blank', 'left=1,top=1,width=1100,height=600,directories=0,titlebar=0,status=0,resizable=1,location=0,personalbar=0,scrollbars=1,statusbar=0,toolbar=0,menubar=0');
          popupWin.document.open();
          popupWin.document.write('<html><head><title>VPN上傳清單</title><style type="text/css">@media print{@page {size:portrait;}} table{border:1px solid black;} th{border:1px solid black;padding:5px;background-color:grey;color:white;} td{border:1px solid black;padding:5px;}</style></head><body onload="window.print()"><p style="font-size: xx-small;">' + divstr + '</p></body></html>' + footer);
          popupWin.document.close();
        }
      },
      sortBy (sortKey) {
        this.reverse = (this.sortKey == sortKey) ? this.reverse * -1 : this.reverse;
        this.sortKey = sortKey;
        if (sortKey == 'segment') {
          (this.reverse == 1) ? this.faSortsegment = "fa-sort-asc" : this.faSortsegment = "fa-sort-desc";
        } else {
          this.faSortsegment = 'fa-sort';
        }
        if (sortKey == 'branch_code') {
          (this.reverse == 1) ? this.faSortbranchcode = "fa-sort-asc" : this.faSortbranchcode = "fa-sort-desc";
        } else {
          this.faSortbranchcode = 'fa-sort';
        }
        if (sortKey == 'hosp_id') {
          (this.reverse == 1) ? this.faSorthospid = "fa-sort-asc" : this.faSorthospid = "fa-sort-desc";
        } else {
          this.faSorthospid = 'fa-sort';
        }
        if (sortKey == 'pid') {
          (this.reverse == 1) ? this.faSortpid = "fa-sort-asc" : this.faSortpid = "fa-sort-desc";
        } else {
          this.faSortpid = 'fa-sort';
        }
        if (sortKey == 'birthday') {
          (this.reverse == 1) ? this.faSortbirthday = "fa-sort-asc" : this.faSortbirthday = "fa-sort-desc";
        } else {
          this.faSortbirthday = 'fa-sort';
        }
        if (sortKey == 'prsn_id') {
          (this.reverse == 1) ? this.faSortprsnid = "fa-sort-asc" : this.faSortprsnid = "fa-sort-desc";
        } else {
          this.faSortprsnid = 'fa-sort';
        }
        if (sortKey == 'cure_stage') {
          (this.reverse == 1) ? this.faSortcurestage = "fa-sort-asc" : this.faSortcurestage = "fa-sort-desc";
        } else {
          this.faSortcurestage = 'fa-sort';
        }
        if (sortKey == 'case_date') {
          (this.reverse == 1) ? this.faSortcasedate = "fa-sort-asc" : this.faSortcasedate = "fa-sort-desc";
        } else {
          this.faSortcasedate = 'fa-sort';
        }
        if (sortKey == 'iopd_type') {
          (this.reverse == 1) ? this.faSortiopdtype = "fa-sort-asc" : this.faSortiopdtype = "fa-sort-desc";
        } else {
          this.faSortiopdtype = 'fa-sort';
        }
        if (sortKey == 'fami_medic_his') {
          (this.reverse == 1) ? this.faSortfamimedichis = "fa-sort-asc" : this.faSortfamimedichis = "fa-sort-desc";
        } else {
          this.faSortfamimedichis = 'fa-sort';
        }
        if (sortKey == 'f_medic_his') {
          (this.reverse == 1) ? this.faSortfmedichis = "fa-sort-asc" : this.faSortfmedichis = "fa-sort-desc";
        } else {
          this.faSortfmedichis = 'fa-sort';
        }
        if (sortKey == 'm_medic_his') {
          (this.reverse == 1) ? this.faSortmmedichis = "fa-sort-asc" : this.faSortmmedichis = "fa-sort-desc";
        } else {
          this.faSortmmedichis = 'fa-sort';
        }
        if (sortKey == 'c_medic_his') {
          (this.reverse == 1) ? this.faSortcmedichis = "fa-sort-asc" : this.faSortcmedichis = "fa-sort-desc";
        } else {
          this.faSortcmedichis = 'fa-sort';
        }
        if (sortKey == 'stage2_yn') {
          (this.reverse == 1) ? this.faSortstage2yn = "fa-sort-asc" : this.faSortstage2yn = "fa-sort-desc";
        } else {
          this.faSortstage2yn = 'fa-sort';
        }
        if (sortKey == 'diabetes_type') {
          (this.reverse == 1) ? this.faSortdiabetestype = "fa-sort-asc" : this.faSortdiabetestype = "fa-sort-desc";
        } else {
          this.faSortdiabetestype = 'fa-sort';
        }
        if (sortKey == 'fall_ill_year') {
          (this.reverse == 1) ? this.faSortfallillyear = "fa-sort-asc" : this.faSortfallillyear = "fa-sort-desc";
        } else {
          this.faSortfallillyear = 'fa-sort';
        }
        if (sortKey == 'base_chk_date') {
          (this.reverse == 1) ? this.faSortbasechkdate = "fa-sort-asc" : this.faSortbasechkdate = "fa-sort-desc";
        } else {
          this.faSortbasechkdate = 'fa-sort';
        }
        if (sortKey == 'base_tall') {
          (this.reverse == 1) ? this.faSortbasetall = "fa-sort-asc" : this.faSortbasetall = "fa-sort-desc";
        } else {
          this.faSortbasetall = 'fa-sort';
        }
        if (sortKey == 'urine_chk_date') {
          (this.reverse == 1) ? this.faSorturinechkdate = "fa-sort-asc" : this.faSorturinechkdate = "fa-sort-desc";
        } else {
          this.faSorturinechkdate = 'fa-sort';
        }
        if (sortKey == 'base_sbp') {
          (this.reverse == 1) ? this.faSortbasesbp = "fa-sort-asc" : this.faSortbasesbp = "fa-sort-desc";
        } else {
          this.faSortbasesbp = 'fa-sort';
        }
        if (sortKey == 'base_ebp') {
          (this.reverse == 1) ? this.faSortbaseebp = "fa-sort-asc" : this.faSortbaseebp = "fa-sort-desc";
        } else {
          this.faSortbaseebp = 'fa-sort';
        }
        if (sortKey == 'blood_chk_date') {
          (this.reverse == 1) ? this.faSortbloodchkdate = "fa-sort-asc" : this.faSortbloodchkdate = "fa-sort-desc";
        } else {
          this.faSortbloodchkdate = 'fa-sort';
        }
        if (sortKey == 'blood_hba1c') {
          (this.reverse == 1) ? this.faSortbloodhba1c = "fa-sort-asc" : this.faSortbloodhba1c = "fa-sort-desc";
        } else {
          this.faSortbloodhba1c = 'fa-sort';
        }
        if (sortKey == 'blood_ldl') {
          (this.reverse == 1) ? this.faSortbloodldl = "fa-sort-asc" : this.faSortbloodldl = "fa-sort-desc";
        } else {
          this.faSortbloodldl = 'fa-sort';
        }
        if (sortKey == 'blood_ac') {
          (this.reverse == 1) ? this.faSortbloodac = "fa-sort-asc" : this.faSortbloodac = "fa-sort-desc";
        } else {
          this.faSortbloodac = 'fa-sort';
        }
        if (sortKey == 'blood_pc') {
          (this.reverse == 1) ? this.faSortbloodpc = "fa-sort-asc" : this.faSortbloodpc = "fa-sort-desc";
        } else {
          this.faSortbloodpc = 'fa-sort';
        }
        if (sortKey == 'blood_tg') {
          (this.reverse == 1) ? this.faSortbloodtg = "fa-sort-asc" : this.faSortbloodtg = "fa-sort-desc";
        } else {
          this.faSortbloodtg = 'fa-sort';
        }
        if (sortKey == 'urine_micro') {
          (this.reverse == 1) ? this.faSorturinemicro = "fa-sort-asc" : this.faSorturinemicro = "fa-sort-desc";
        } else {
          this.faSorturinemicro = 'fa-sort';
        }
        if (sortKey == 'base_weight') {
          (this.reverse == 1) ? this.faSortbaseweight = "fa-sort-asc" : this.faSortbaseweight = "fa-sort-desc";
        } else {
          this.faSortbaseweight = 'fa-sort';
        }
        if (sortKey == 'urine_routine') {
          (this.reverse == 1) ? this.faSorturineroutine = "fa-sort-asc" : this.faSorturineroutine = "fa-sort-desc";
        } else {
          this.faSorturineroutine = 'fa-sort';
        }
        if (sortKey == 'year_mark') {
          (this.reverse == 1) ? this.faSortyearmark = "fa-sort-asc" : this.faSortyearmark = "fa-sort-desc";
        } else {
          this.faSortyearmark = 'fa-sort';
        }
        if (sortKey == 'foot_chk_left') {
          (this.reverse == 1) ? this.faSortfootchkleft = "fa-sort-asc" : this.faSortfootchkleft = "fa-sort-desc";
        } else {
          this.faSortfootchkleft = 'fa-sort';
        }
        if (sortKey == 'foot_chk_right') {
          (this.reverse == 1) ? this.faSortfootchkright = "fa-sort-asc" : this.faSortfootchkright = "fa-sort-desc";
        } else {
          this.faSortfootchkright = 'fa-sort';
        }
        if (sortKey == 'blood_creat') {
          (this.reverse == 1) ? this.faSortbloodcreat = "fa-sort-asc" : this.faSortbloodcreat = "fa-sort-desc";
        } else {
          this.faSortbloodcreat = 'fa-sort';
        }
        if (sortKey == 'eye_chk8') {
          (this.reverse == 1) ? this.faSorteyechk8 = "fa-sort-asc" : this.faSorteyechk8 = "fa-sort-desc";
        } else {
          this.faSorteyechk8 = 'fa-sort';
        }
        if (sortKey == 'egfr') {
          (this.reverse == 1) ? this.faSortegfr = "fa-sort-asc" : this.faSortegfr = "fa-sort-desc";
        } else {
          this.faSortegfr = 'fa-sort';
        }
      },
      showPage (page) {
        this.$http({url: '/api/vpnhistory/showpage/' + page, method: 'GET'}).then(function (response) {
          this.$set('vpnhistory', response.data.data)
          this.$set('thispage', page)
        })
      }
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/vpnhistory/' + this.vpnhistoryId) {
          return '/api/vpnhistory/' + this.vpnhistoryId
        } else {
          return '/api/vpnhistory/showpage/' + this.thispage
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
    overflow-x: auto; /* 顯示水平捲軸 */
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
  .container-fluid0 {
    /* margin: 0px -15px; */
    width: 4000px; /* 加寛 */
    /* height: 200px; */
  }
</style>
