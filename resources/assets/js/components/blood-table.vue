<template>
  <modal title="Blood Modal" :show.sync="bloodModal" large>
    <div slot="modal-header" class="modal-header">
      <div class="col-xs-2 col-xs-offset-5"><h4 class="modal-title"><i>每 日</i> <code>血 糖</code> <b></b></h4></div>
    </div>
    <div slot="modal-body" class="modal-body">
      <div class="row">
        <label class="control-label col-xs-2" for="mr_date">量測日期：</label>
        <div class="col-xs-10">
          <bs-input name="mr_date" :value.sync="bloods.mr_date" disabled></bs-input>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="mr_time">量測時間：</label>
        <div class="col-xs-10">
          <vue-timepicker :time-value.sync="bloods.mr_time" format="HH:mm:ss"></vue-timepicker>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="slotname">量測時段：</label>
        <div class="col-xs-10">
          <bs-input name="slotname" :value.sync="bloods.slotname" disabled></bs-input>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="value1">血糖：</label>
        <div class="col-xs-2">
          <bs-input name="value1" :value.sync="bloods.value1"></bs-input>
        </div>
        <div class="col-xs-2">
          mg/dl
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="stype">運動</label>
        <div class="col-xs-5">
          <multiselect :options="['Select option','無','輕度運動','中度運動','重度運動']" :selected.sync="bloods.stype" :multiple="false"
                       :searchable="false" :close-on-select="false" :show-labels="false" @update="updateSType" label="stype">  </multiselect>
        </div>
        <div class="col-xs-5">
          <multiselect :options="['Select option','0.5小時','1小時','1.5小時','2小時','2.5小時','3小時','3.5小時','4小時','4.5小時']" :selected.sync="bloods.svalue"
                       :multiple="false" :searchable="false" :close-on-select="false" :show-labels="false" @update="updateSValue" label="svalue">  </multiselect>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="btype">低血糖</label>
        <div class="col-xs-3">
          <multiselect :options="['Select option','無','有']" :selected.sync="bloods.btype" :multiple="false" :searchable="false" :close-on-select="false"
                       :show-labels="false" @update="updateBType" label="btype">  </multiselect>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="itype">胰島素</label>
        <div class="col-xs-2 col-xs-offset-1">
          <button type="button" class="btn btn-info" @click='addItype'>增加胰島素</button>
        </div>
      </div>
      <div class="row" v-for="item in itypes">
        <div class="col-xs-2 col-xs-offset-2">
          <select name="itype" v-model="item.itype">
            <option>無</option>
            <option>速效</option>
            <option>短效</option>
            <option>中效</option>
            <option>長效</option>
            <option>混合型70/30</option>
            <option>混合型75/25</option>
            <option>幫浦</option>
            <option>口服藥</option>
          </select>
        </div>
        <div class="col-xs-2">
          <input v-model="item.ivalue" style="width: 120px;" number />
        </div>
        <div class="col-xs-1">
          單位
        </div>
        <div class="col-xs-2">
          <button type="button" class="btn btn-danger" @click='removeItype($event, $index)'>減少胰島素</button>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-xs-2" for="value2">飲食醣份：</label>
        <div class="col-xs-2">
          <bs-input name="value2" :value.sync="bloods.value2"></bs-input>
        </div>
        <div class="col-xs-2">
          克
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <bs-input label="血糖備註：" type="textarea" name="note" :value.sync="bloods.note" @focus="event = 'focused'" @blur="event = 'blured'"></bs-input>
        </div>
      </div>
    </div>
    <div slot="modal-footer" class="modal-footer">
      <button type="button" class="btn btn-default" @click='bloodModal = false'>離開</button>
      <button type="button" class="btn btn-success" @click='updateBlood(bloods, itypes)'>存檔</button>
    </div>
  </modal>

  <modal title="Food Modal" :show.sync="foodModal" large>
    <div slot="modal-header" class="modal-header">
      <div class="col-xs-2 col-xs-offset-5"><h4 class="modal-title"><i>每 日</i> <code>血 糖</code> <b></b></h4></div>
    </div>
    <div slot="modal-body" class="modal-body">

    </div>
    <div slot="modal-footer" class="modal-footer">
      <button type="button" class="btn btn-default" @click='foodModal = false'>離開</button>
      <button type="button" class="btn btn-success" @click='updateFood(foods)'>存檔</button>
    </div>
  </modal>

  <modal title="Memo Modal" :show.sync="memoModal" large>
    <div slot="modal-header" class="modal-header">
      <div class="row">
        <div class="col-md-2 col-md-offset-5"><h4 class="modal-title"><i>每 日</i> <code>備 註</code> <b></b></h4></div>
      </div>
    </div>
    <div slot="modal-body" class="modal-body">
      <div class="row">
        <label class="control-label col-xs-1" for="mr_date">日期：</label>
        <div class="col-xs-11">
          <bs-input name="mr_date" :value.sync="memos.mr_date" disabled></bs-input>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <bs-input label="衛教備註" type="textarea" name="note" :value.sync="memos.note" @focus="event = 'focused'" @blur="event = 'blured'"></bs-input>
        </div>
      </div>
    </div>
    <div slot="modal-footer" class="modal-footer">
      <button type="button" class="btn btn-default" @click='memoModal = false'>離開</button>
      <button type="button" class="btn btn-success" @click='updateMemo(memos)'>存檔</button>
    </div>
  </modal>

  <label class="bg-warning" for="range{{ num + 1 }}">血糖期間:{{ num + 1 }} &gt; {{ fromdate }} to {{ todate }}</label>
  <button type="button" @click="showTR(fromdate, todate, pid)" class="btn btn-md btn-info btn-flat" style="margin-bottom: 10px;">
    <img src="/img/pennote.gif" /> 展開日期
  </button>
  <label class="bg-warning">
    總次數: {{ totalTimes }} | 次/週: {{ totalWeeks }} | 次/日: {{ totalDays }}
  </label>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12">
        <table class="table table-condensed" v-show="tblMR">
          <thead>
            <tr class="bg-warning">
              <th class="col-xs-1" rowspan="2">檢驗日期</th>
              <th class="col-xs-1" rowspan="2">凌晨</th>
              <th class="col-xs-1" rowspan="2">晨起</th>
              <th class="col-xs-1" colspan="2">早餐</th>
              <th class="col-xs-1" colspan="2">中餐</th>
              <th class="col-xs-1" colspan="2">晚餐</th>
              <th class="col-xs-1" rowspan="2">睡前</th>
              <th class="col-xs-2" rowspan="2">衛教備註</th>
            </tr>
            <tr class="bg-warning">
              <th class="col-xs-1">飯前</th>
              <th class="col-xs-1 text-danger">飯後</th>
              <th class="col-xs-1">飯前</th>
              <th class="col-xs-1 text-danger">飯後</th>
              <th class="col-xs-1">飯前</th>
              <th class="col-xs-1 text-danger">飯後</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="mdw in mdweek">
              <td class="{{ (mdw.mdate.indexOf('日') >= 0) ? 'text-danger' : (mdw.mdate.indexOf('六') >= 0) ? 'text-danger' : 'text-muted' }}">{{ mdw.mdate.substr(5) }}</td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d1" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d1_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '凌晨')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d1_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d1_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 9">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '凌晨', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <!-- button class="btn btn-link" @click="foodEdit($event, $index)" alt="修改飲食"></button -->
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d2" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d2_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '晨起')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d2_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d2_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 1">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '晨起', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d3" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d3_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '早餐前')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d3_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d3_fd" alt="新增飲食" src="/img/rice.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 2">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '早餐前', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td class="bg-danger" @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d4" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d4_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '早餐後')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d4_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d4_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 3">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '早餐後', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d5" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d5_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '中餐前')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d5_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d5_fd" alt="新增飲食" src="/img/rice.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 4">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '中餐前', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td class="bg-danger" @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d6" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d6_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '中餐後')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d6_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d6_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 5">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '中餐後', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d7" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d7_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '晚餐前')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d7_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d7_fd" alt="新增飲食" src="/img/rice.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 6">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '晚餐前', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td class="bg-danger" @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d8" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d8_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '晚餐後')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d8_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d8_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 7">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '晚餐後', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d9" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d9_div_add" style="display: none">
                  <img @click="bloodAdd($event, $index, '睡前')" title="新增" name="{{ mdw.mdate.substr(0,10) }}_d9_bd" alt="新增" src="/img/cross.gif" />&nbsp;
                  <!-- img @click="foodAdd($event, $index)" title="新增飲食" name="{{-- mdw.mdate.substr(0,10) --}}_d9_fd" alt="新增飲食" src="/img/hotcafe.gif" / -->
                </div>
                <div v-for="mval in mvalue">
                  <div v-if="mval.mr_date.substr(0,10) == mdw.mdate.substr(0,10) && mval.slot == 8">
                    <button class="btn btn-link" @click="bloodEdit($event, $index, '睡前', mval.id, mval.mr_date)" title="修改血糖"><label class="text-danger">{{ mval.value1 }}</label></button>
                    <popover v-if="mval.note != null" effect="scale" title="血糖備註" :content="mval.note" placement="blood" trigger="hover">
                      <button class="btn btn-link">*</button>
                    </popover>
                  </div>
                </div>
              </td>
              <td class="tdtiny" @mouseenter="shNB($event, $index)" @mouseleave="hdNB($event, $index)" id="{{ mdw.mdate.substr(0,10) }}_d10" valign="top">
                <div id="{{ mdw.mdate.substr(0,10) }}_d10_div_add" style="display: none">
                  <img @click="memoAdd($event, $index)" title="新增衛教備註" name="{{ mdw.mdate.substr(0,10) }}_d10_mo" alt="新增衛教備註" src="/img/cross.gif" />
                </div>
                <div v-for="nvl in nvalue">
                  <div v-if="nvl.mr_date == mdw.mdate.substr(0,10)">
                    <!-- button class="btn btn-link" @click="memoEdit($event, $index)" name="{{ mdw.mdate.substr(0,10) }}_d10_btn" id="{{ mdw.mdate.substr(0,10) }}_d10_btn" alt="修改衛教備註">{{ nvl.note.substr(0,20) }}<br>{{ nvl.note.substr(20,20) }}<br>{{ nvl.note.substr(40) }}</button -->
                    <button class="btn btn-link" @click="memoEdit($event, $index)" name="{{ mdw.mdate.substr(0,10) }}_d10_btn" id="{{ mdw.mdate.substr(0,10) }}_d10_btn" alt="修改衛教備註">{{{ getNoteBreak(nvl.note) }}}</button>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="bg-info">
              <td>平均</td>
              <td>{{ averaged1 }}</td>
              <td>{{ averaged2 }}</td>
              <td>{{ averaged3 }}</td>
              <td>{{ averaged4 }}</td>
              <td>{{ averaged5 }}</td>
              <td>{{ averaged6 }}</td>
              <td>{{ averaged7 }}</td>
              <td>{{ averaged8 }}</td>
              <td>{{ averaged9 }}</td>
              <td> </td>
            </tr>
            <tr class="bg-info">
              <td>SD(&sigma;)</td>
              <td>{{ sd1 }}</td>
              <td>{{ sd2 }}</td>
              <td>{{ sd3 }}</td>
              <td>{{ sd4 }}</td>
              <td>{{ sd5 }}</td>
              <td>{{ sd6 }}</td>
              <td>{{ sd7 }}</td>
              <td>{{ sd8 }}</td>
              <td>{{ sd9 }}</td>
              <td> </td>
            </tr>
            <tr class="bg-info">
              <td>理想範圍</td>
              <td>{{ idealranged1 }} %</td>
              <td>{{ idealranged2 }} %</td>
              <td>{{ idealranged3 }} %</td>
              <td>{{ idealranged4 }} %</td>
              <td>{{ idealranged5 }} %</td>
              <td>{{ idealranged6 }} %</td>
              <td>{{ idealranged7 }} %</td>
              <td>{{ idealranged8 }} %</td>
              <td>{{ idealranged9 }} %</td>
              <td> </td>
            </tr>
            <tr class="bg-info">
              <td>結果數</td>
              <td>{{ resultd1 }}</td>
              <td>{{ resultd2 }}</td>
              <td>{{ resultd3 }}</td>
              <td>{{ resultd4 }}</td>
              <td>{{ resultd5 }}</td>
              <td>{{ resultd6 }}</td>
              <td>{{ resultd7 }}</td>
              <td>{{ resultd8 }}</td>
              <td>{{ resultd9 }}</td>
              <td> </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import popover from './vue-strap/src/Popover.vue'
  import modal from './vue-strap/src/Modal.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import VueTimepicker from './vue-timepicker.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
//  import tooltip from './vue-strap/src/Tooltip.vue'
//  import vSelect from './vue-strap/src/Select.vue'
//  import vOption from './vue-strap/src/Option.vue'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    name: 'BloodTable',

    components: {
      popover,
      modal,
      bsInput,
      VueTimepicker,
      Multiselect,
//      tooltip
//      vSelect,
//      vOption,
    },

    props: {
      num: {type: Number, default: 0},
      fromdate: {type: String, default: null},
      todate: {type: String, default: null},
      pid: {type: String, default: null},
      ivalue: {type: Number, default: 0},
      //timeValue: {type: Object, twoWay: true},
      //hideClearButton: {type: Boolean},
      //format: {type: String},
      //secondInterval: {type: Number},
    },

    created () {
//      this.pid = this.$route.params.basePid
    },

    ready () {
//      this.doReady()
      this.showTRFirst(this.fromdate, this.todate, this.pid, this.ivalue)
    },

    data () {
      return {
        token: csrf_token,
        bloodModal: false,
        foodModal: false,
        memoModal: false,
        tblMR: false,
        mdweek: [],
        mdweek1: [],
        mvalue: {},
        mvalue1: {},
        nvalue: {},
        bloods: {},
        foods: {},
        memos: {},
        totalTimes: 0,
        totalWeeks: 0,
        totalDays: 0,
        averaged1: 0,
        averaged2: 0,
        averaged3: 0,
        averaged4: 0,
        averaged5: 0,
        averaged6: 0,
        averaged7: 0,
        averaged8: 0,
        averaged9: 0,
        idealranged1: 0,
        idealranged2: 0,
        idealranged3: 0,
        idealranged4: 0,
        idealranged5: 0,
        idealranged6: 0,
        idealranged7: 0,
        idealranged8: 0,
        idealranged9: 0,
        sd1: 0,
        sd2: 0,
        sd3: 0,
        sd4: 0,
        sd5: 0,
        sd6: 0,
        sd7: 0,
        sd8: 0,
        sd9: 0,
        resultd1: 0,
        resultd2: 0,
        resultd3: 0,
        resultd4: 0,
        resultd5: 0,
        resultd6: 0,
        resultd7: 0,
        resultd8: 0,
        resultd9: 0,
        itypes: [],
      }
    },

    watch: {
      //'format': 'renderFormat',
//            minuteInterval (newInteval) {
//                this.renderList('minute', newInteval)
//            },
//            secondInterval (newInteval) {
//                this.renderList('second', newInteval)
//            },
//            'timeValue': 'readValues',
//            'displayTime': 'fillValues',
      //'num': 'readNum',
    },

    methods: {
      doReady () {
//        $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
//        });
      },
      showTR (fdate, tdate, pid) {
        mwl = this.mdweek.length
        if (0 == mwl) {
          this.tblMR = true
          var trweek = null
          var i = 0
          while (i > -14) {
            trweek = this.DateWeek(fdate, i)
            // 晨起=1,早餐前=2,早餐後=3,中餐前=4,中餐後=5,晚餐前=6,晚餐後=7,睡前=8,凌晨=9
            // wakeup=1, before_breakfast=2, after_breakfast=3, before_lunch= 4, after_lunch=5, before_dinner=6, after_dinner=7, bedtime=8, midnight=9
            this.mdweek.push({'mdate': trweek})
            i = i - 1
          }
          this.fetchMResults(pid, fdate, tdate)  // 取14天血糖值
          this.fetchMRNotes(pid, fdate, tdate)  // 取14天衛教備註
        } else {
          this.tblMR = false
          this.mdweek = []
        }
      },
      DateWeek (date, days) {
        // 日期+周
        var d = new Date(date)
        d.setDate(d.getDate() + days)
        var m = d.getMonth() + 1
        var day_list = ['日', '一', '二', '三', '四', '五', '六']
        var day  = d.getDay()
        return d.getFullYear() + '-' + ('0' + m).slice(-2) + '-' + ('0' + d.getDate()).slice(-2) + ' ' + day_list[day]
      },
      fetchMResults (pid, fdate, tdate) {
        this.$http({url: '/api/mresults/showmr/' + pid + '/' + fdate + '/' + tdate, method: 'GET'})
          .then(function (response) {
//            console.log(response.data)
            this.$set('mvalue', response.data)
            // this.$set('mvalue1', response.data)
            // console.log(this.mvalue)
            // 計算:各項
            var array = this.mvalue
            var s1 = 0 // 總次數
            var ad1 = 0, ad2 = 0, ad3 = 0, ad4 = 0, ad5 = 0, ad6 = 0, ad7 = 0, ad8 = 0, ad9 = 0 // 平均
            var sdd1 = [], sdd2 = [], sdd3 = [], sdd4 = [], sdd5 = [], sdd6 = [], sdd7 = [], sdd8 = [], sdd9 = [] // SD
            var ir1 = 0, ir2 = 0, ir3 = 0, ir4 = 0, ir5 = 0, ir6 = 0, ir7 = 0, ir8 = 0, ir9 = 0 // 理想範圍
            // 查血糖區間值
            var deepnightlow = 0, deepnighthigh = 0, weekuplow = 0, weekuphigh = 0, beforebreaklow = 0, beforebreakhigh = 0, afterbreaklow = 0, afterbreakhigh = 0, beforenoonlow = 0, beforenoonhigh = 0, afternoonlow = 0, afternoonhigh = 0, beforedinnerlow = 0, beforedinnerhigh = 0, afterdinnerlow = 0, afterdinnerhigh = 0, sleeplow = 0, sleephigh = 0

            this.$http({url: '/api/mresults/showbsrange/' + pid, method: 'GET'})
              .then(function (response) {
                // console.log(response.data.id)
                if (response.data != '') {
                  deepnightlow = response.data.deepnightlow
                  deepnighthigh = response.data.deepnighthigh
                  weekuplow = response.data.weekuplow
                  weekuphigh = response.data.weekuphigh
                  beforebreaklow = response.data.beforebreaklow
                  beforebreakhigh = response.data.beforebreakhigh
                  afterbreaklow = response.data.afterbreaklow
                  afterbreakhigh = response.data.afterbreakhigh
                  beforenoonlow = response.data.beforenoonlow
                  beforenoonhigh = response.data.beforenoonhigh
                  afternoonlow = response.data.afternoonlow
                  afternoonhigh = response.data.afternoonhigh
                  beforedinnerlow = response.data.beforedinnerlow
                  beforedinnerhigh = response.data.beforedinnerhigh
                  afterdinnerlow = response.data.afterdinnerlow
                  afterdinnerhigh = response.data.afterdinnerhigh
                  sleeplow = response.data.sleeplow
                  sleephigh = response.data.sleephigh
                } else {
                  deepnightlow = 100
                  deepnighthigh = 150
                  weekuplow = 90
                  weekuphigh = 130
                  beforebreaklow = 90
                  beforebreakhigh = 130
                  afterbreaklow = 100
                  afterbreakhigh = 190
                  beforenoonlow = 90
                  beforenoonhigh = 130
                  afternoonlow = 100
                  afternoonhigh = 190
                  beforedinnerlow = 90
                  beforedinnerhigh = 130
                  afterdinnerlow = 100
                  afterdinnerhigh = 190
                  sleeplow = 100
                  sleephigh = 150
                }

                var rd1 = 0, rd2 = 0, rd3 = 0, rd4 = 0, rd5 = 0, rd6 = 0, rd7 = 0, rd8 = 0, rd9 = 0 // 結果數
                for (var i = 0; i < array.length; i++) {
                  if ( array[i]['value1'] > 0 ) {
                    s1 = s1 + 1
                    switch (array[i]['slot']) {
                      case 9:
                        rd1 = rd1 + 1
                        ad1 = ad1 + array[i]['value1']
                        if (array[i]['value1'] >= deepnightlow && array[i]['value1'] < deepnighthigh) {
                          ir1 = ir1 + 1
                        }
                        sdd1.push(array[i]['value1'])
                        break
                      case 1:
                        rd2 = rd2 + 1
                        ad2 = ad2 + array[i]['value1']
                        if (array[i]['value1'] >= weekuplow && array[i]['value1'] < weekuphigh) {
                          ir2 = ir2 + 1
                        }
                        sdd2.push(array[i]['value1'])
                        break
                      case 2:
                        rd3 = rd3 + 1
                        ad3 = ad3 + array[i]['value1']
                        if (array[i]['value1'] >= beforebreaklow && array[i]['value1'] < beforebreakhigh) {
                          ir3 = ir3 + 1
                        }
                        sdd3.push(array[i]['value1'])
                        break
                      case 3:
                        rd4 = rd4 + 1
                        ad4 = ad4 + array[i]['value1']
                        if (array[i]['value1'] >= afterbreaklow && array[i]['value1'] < afterbreakhigh) {
                          ir4 = ir4 + 1
                        }
                        sdd4.push(array[i]['value1'])
                        break
                      case 4:
                        rd5 = rd5 + 1
                        ad5 = ad5 + array[i]['value1']
                        if (array[i]['value1'] >= beforenoonlow && array[i]['value1'] < beforenoonhigh) {
                          ir5 = ir5 + 1
                        }
                        sdd5.push(array[i]['value1'])
                        break
                      case 5:
                        rd6 = rd6 + 1
                        ad6 = ad6 + array[i]['value1']
                        if (array[i]['value1'] >= afternoonlow && array[i]['value1'] < afternoonhigh) {
                          ir6 = ir6 + 1
                        }
                        sdd6.push(array[i]['value1'])
                        break
                      case 6:
                        rd7 = rd7 + 1
                        ad7 = ad7 + array[i]['value1']
                        if (array[i]['value1'] >= beforedinnerlow && array[i]['value1'] < beforedinnerhigh) {
                          ir7 = ir7 + 1
                        }
                        sdd7.push(array[i]['value1'])
                        break
                      case 7:
                        rd8 = rd8 + 1
                        ad8 = ad8 + array[i]['value1']
                        if (array[i]['value1'] >= afterdinnerlow && array[i]['value1'] < afterdinnerhigh) {
                          ir8 = ir8 + 1
                        }
                        sdd8.push(array[i]['value1'])
                        break
                      case 8:
                        rd9 = rd9 + 1
                        ad9 = ad9 + array[i]['value1']
                        if (array[i]['value1'] >= sleeplow && array[i]['value1'] < sleephigh) {
                          ir9 = ir9 + 1
                        }
                        sdd9.push(array[i]['value1'])
                        break
                    }
                  }
                }

                // 計算:總次數
                this.$set('totalTimes', s1)
                // 計算:次/週
                this.$set('totalWeeks', this.round(s1 / 2, 1))
                // 計算:次/日
                this.$set('totalDays', this.round(s1 / 14, 1))
                // 平均:凌晨/d1
                if(ad1 != 0 && rd1 != 0) this.$set('averaged1', this.round(ad1 / rd1, 0))
                if(ad2 != 0 && rd2 != 0) this.$set('averaged2', this.round(ad2 / rd2, 0))
                if(ad3 != 0 && rd3 != 0) this.$set('averaged3', this.round(ad3 / rd3, 0))
                if(ad4 != 0 && rd4 != 0) this.$set('averaged4', this.round(ad4 / rd4, 0))
                if(ad5 != 0 && rd5 != 0) this.$set('averaged5', this.round(ad5 / rd5, 0))
                if(ad6 != 0 && rd6 != 0) this.$set('averaged6', this.round(ad6 / rd6, 0))
                if(ad7 != 0 && rd7 != 0) this.$set('averaged7', this.round(ad7 / rd7, 0))
                if(ad8 != 0 && rd8 != 0) this.$set('averaged8', this.round(ad8 / rd8, 0))
                if(ad9 != 0 && rd9 != 0) this.$set('averaged9', this.round(ad9 / rd9, 0))
                // SD
                this.$set('sd1', this.stdev(sdd1))
                this.$set('sd2', this.stdev(sdd2))
                this.$set('sd3', this.stdev(sdd3))
                this.$set('sd4', this.stdev(sdd4))
                this.$set('sd5', this.stdev(sdd5))
                this.$set('sd6', this.stdev(sdd6))
                this.$set('sd7', this.stdev(sdd7))
                this.$set('sd8', this.stdev(sdd8))
                this.$set('sd9', this.stdev(sdd9))
                // 理想範圍
                if(ir1 != 0 && rd1 != 0) this.$set('idealranged1', this.round(ir1 / rd1 * 100, 0))
                if(ir2 != 0 && rd2 != 0) this.$set('idealranged2', this.round(ir2 / rd2 * 100, 0))
                if(ir3 != 0 && rd3 != 0) this.$set('idealranged3', this.round(ir3 / rd3 * 100, 0))
                if(ir4 != 0 && rd4 != 0) this.$set('idealranged4', this.round(ir4 / rd4 * 100, 0))
                if(ir5 != 0 && rd5 != 0) this.$set('idealranged5', this.round(ir5 / rd5 * 100, 0))
                if(ir6 != 0 && rd6 != 0) this.$set('idealranged6', this.round(ir6 / rd6 * 100, 0))
                if(ir7 != 0 && rd7 != 0) this.$set('idealranged7', this.round(ir7 / rd7 * 100, 0))
                if(ir8 != 0 && rd8 != 0) this.$set('idealranged8', this.round(ir8 / rd8 * 100, 0))
                if(ir9 != 0 && rd9 != 0) this.$set('idealranged9', this.round(ir9 / rd9 * 100, 0))
                // 結果數:凌晨/d1
                this.$set('resultd1', rd1)
                this.$set('resultd2', rd2)
                this.$set('resultd3', rd3)
                this.$set('resultd4', rd4)
                this.$set('resultd5', rd5)
                this.$set('resultd6', rd6)
                this.$set('resultd7', rd7)
                this.$set('resultd8', rd8)
                this.$set('resultd9', rd9)
                // console.log(this.mvalue)
              })
              .catch(function(response) {
                // console.log(response)
              })
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchMRNotes (pid, fdate, tdate) {
        this.$http({url: '/api/mresults/shownote/' + pid + '/' + fdate + '/' + tdate, method: 'GET'})
          .then(function (response) {
            this.$set('nvalue', response.data)
            //console.log(this.nvalue)
          })
          .catch(function(response) {
            //console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      bloodAdd (event, index, slotname) {
        var bd = event.currentTarget.name
        this.$set('bloods.mr_date', bd.substr(0, 10))
        this.$set('bloods.mr_time',  { HH: "00", mm: "00", ss: "00" })
        this.$set('bloods.slotname', slotname)
        this.$set('bloods.value1', '')
        this.$set('bloods.value2', '')
        this.$set('bloods.note', '')
        this.$set('bloods.autonote', '')
        this.$set('itypes', [])
        this.bloodModal = true
      },
      bloodEdit (event, index, slotname, mid, mdate) {
        // var bd = event.currentTarget.name
        // var nbarray = bd.split("_")
        // var assigndate = nbarray[0]
        // 找血糖
        this.$http({url: '/api/mresults/showblood/' + this.pid + '/' + mdate + '/' + mid, method: 'GET'})
          .then(function (response) {
            this.$set('bloods', response.data)
            this.$set('bloods.slotname', slotname)
            // console.log(this.bloods.autonote)
            // 還原胰島素物件
            if (this.bloods.autonote == null) {
              this.$set('itypes', [])
            } else {
              this.$set('itypes', JSON.parse(this.bloods.autonote))
            }
            // 更新note content
            // var mylbl = mo + '_lbl'
            // document.getElementById(mylbl).innerHTML = this.memos.note
          })
          .catch(function (response) {
          })
        this.bloodModal = true
      },
      foodAdd (event, index) {
        var fd = event.currentTarget.name
        var myfd = fd.substr(0, 10)
        this.$set('foods.mr_date', myfd)
        // this.$set('foods.note', '')
        this.foodModal = true
      },
      foodEdit (event, index) {

      },
      memoAdd (event, index) {
        // 衛教備註只能新增一筆
        var mo = event.currentTarget.name
        this.$set('memos.mr_date', mo.substr(0, 10))
        this.$set('memos.note', '')
        this.memoModal = true
      },
      memoEdit (event, index) {
        var mo = event.currentTarget.name
        // var motle = document.getElementById(mo).title
        var nbarray = mo.split("_")
        var assigndate = nbarray[0]
        // 找衛教備註
        this.$http({url: '/api/mresults/showpid/' + this.pid + '/' + assigndate, method: 'GET'})
          .then(function (response) {
            this.$set('memos', response.data)
            // 更新note content
            var mylbl = mo + '_lbl'
            document.getElementById(mylbl).innerHTML = this.memos.note
          })
          .catch(function (response) {
          })
        this.memoModal = true
      },
      updateBlood (bloods, itypes) {
        // console.log(JSON.stringify(bloods))
        // console.log(JSON.stringify(itypes))
//        console.log(typeof bloods)
//        console.log(typeof itypes)
        // console.log(bloods["autonote"])
        // 動態新刪胰島素
        bloods["autonote"] = itypes
        // console.log(JSON.stringify(bloods))
        this.$http.patch('/api/mresults/saveblood/' + this.pid, bloods).then(function (response) {
          // console.log(response.data.message)
          show_stack_success('血糖已更新!', response)
        }, function (response) {
          show_stack_error('血糖更新失敗!', response)
        })
        // this.$set('bloods', {})
        // this.bloods = {}
        this.bloodModal = false
        // refresh blood glucose
        // this.$router.go('/listmresults/' + this.pid) // invalid
        // console.log(this.fromdate)
        this.showTR(this.fromdate, this.todate, this.pid)
      },
      updateFood (food) {
        this.foodModal = false
        // refresh blood glucose
        this.showTR(this.fromdate, this.todate, this.pid)
      },
      updateMemo (umemo) {
        // event.preventDefault();
        this.$http.patch('/api/mresults/savenote/' + this.pid, umemo).then(function (response) {
          // console.log(response.data.message)
          show_stack_success('衛教備註已更新!', response)
        }, function (response) {
          // console.log(response.data.message)
          show_stack_error('衛教備註更新失敗!', response)
        })
        // this.memos = {}
        this.memoModal = false
        // refresh blood glucose
        this.showTR(this.fromdate, this.todate, this.pid)
      },
      shNB (event, index) {
        var mo = event.currentTarget.id
        var nbarray = mo.split("_")
        if (nbarray[1] == "d1" || nbarray[1] == "d2" || nbarray[1] == "d3" || nbarray[1] == "d4" || nbarray[1] == "d5" || nbarray[1] == "d6" || nbarray[1] == "d7" || nbarray[1] == "d8" || nbarray[1] == "d9") {
          var mynb = mo + '_div_add'
          document.getElementById(mynb).style.display = "block"
        } else if (nbarray[1] == "d10") {
          // 衛教備註
          var mynb = mo + '_div_add'
          document.getElementById(mynb).style.display = "block"
          var mybtn = mo + '_btn'
          if (document.getElementById(mybtn) !== null) {
            var mb = document.getElementById(mybtn).innerText
            // 衛教備註只能新增一筆
            if (mb.length != 0) {
              document.getElementById(mynb).style.display = "none"
            }
          }
        } else {
          var mynb = mo + '_div'
          document.getElementById(mynb).style.display = "block"
        }
      },
      hdNB (event, index) {
        var mo = event.currentTarget.id
        var nbarray = mo.split("_")
        if (nbarray[1] == "d1" || nbarray[1] == "d2" || nbarray[1] == "d3" || nbarray[1] == "d4" || nbarray[1] == "d5" || nbarray[1] == "d6" || nbarray[1] == "d7" || nbarray[1] == "d8" || nbarray[1] == "d9") {
          var mynb = mo + '_div_add'
          document.getElementById(mynb).style.display = "none"
        } else if (nbarray[1] == "d10") {
          // 衛教備註
          var mynb = mo + '_div_add'
          // 衛教備註只能新增一筆
          document.getElementById(mynb).style.display = "none"
        } else {
          var mynb = mo + '_div'
          document.getElementById(mynb).style.display = "none"
        }
      },
      updateSType (value) {
        this.bloods.stype = value
      },
      updateSValue (value) {
        this.bloods.svalue = value
      },
      updateBType (value) {
        this.bloods.btype = value
      },
      round (num, pos) {
        // 四捨五入
        return ( Math.round( num * Math.pow(10, pos) ) / Math.pow(10, pos) ).toFixed(pos)
      },
      stdev (arr) {
        //標準差=stdev
        var n = arr.length
        if (n == 0) {
          return 0
        }
        var sum = 0

        arr.map(function(data) {
          sum += data
        })

        var mean = sum / n
        var variance = 0.0
        var v1 = 0.0
        var v2 = 0.0
        var stddev = 0

        if (n != 1) {
          for (var i = 0; i < n; i++) {
            v1 = v1 + (arr[i] - mean) * (arr[i] - mean)
            v2 = v2 + (arr[i] - mean)
          }
          v2 = v2 * v2 / n
          variance = (v1 - v2) / (n - 1)
          if (variance < 0) {
            variance = 0
          }
          stddev = Math.sqrt(variance)
        }
        var stdev = Math.round(stddev * 100) / 100

//        return {
//          mean: Math.round(mean * 100) / 100,
//          variance: variance,
//          deviation: Math.round(stddev * 100) / 100
//        }
//      call it like:
//      var test = this.stdev(array)
//      var mean = test[0]
//      var variance = test[1]
//      var deviation = test[2]

        return stdev
      },
      sd (a) {
        //標準差=stdevp
        var sdn = 0
        for (var i in a) {
          sdn += Math.pow(( Math.abs( a[i] - this.mean(a) ) ), 2)
        }
        return this.sqrt(sdn / a.length)
      },
      sqrt (n) {
        // 平方根
        var i = 1
        var answer = 1
        while (i < n) {
          // console.log('i= '+ i + ' answer= '+answer)
          if ( i * i <= n && (i + 0.0001) * (i + 0.0001) > n ) {
            answer = Math.round(i * 100) / 100
            return answer
          }
          i = i + 0.0001
        }
        return 0
      },
      mean (a) {
        // 平均數
        var total = 0
        for (var i in a) {
          total += a[i]
        }
        return total / a.length
      },
      addItype () {
        this.itypes.push({ itype: '無', ivalue: '' })
      },
      removeItype (event, index) {
        if (index !== -1) {
          this.itypes.splice(index, 1)
        }
      },
      getNoteBreak (note) {
        var snote = ''
        if (note.length > 20) {
          var n = note.length / 20
          for (var i = 0; i <= n; i++) {
            snote = snote + note.substr(20 * i, 20) + '<br>'
          }
        } else {
          snote = note.substr(0, 20) + '<br>'
        }
        return snote
      },
      showTRFirst (fdate, tdate, pid, ivalue) {
        this.mdweek1 = []
        this.tblMR = true
        var trweek = null
        var i = 0
        while (i > -14) {
          trweek = this.DateWeek(fdate, i)
          // 晨起=1,早餐前=2,早餐後=3,中餐前=4,中餐後=5,晚餐前=6,晚餐後=7,睡前=8,凌晨=9
          // wakeup=1, before_breakfast=2, after_breakfast=3, before_lunch= 4, after_lunch=5, before_dinner=6, after_dinner=7, bedtime=8, midnight=9
          this.mdweek1.push({'mdate': trweek})
          i = i - 1
        }

//        this.fetchMResults(pid, fdate, tdate)  // 取14天血糖值, mvalue
        this.$http({url: '/api/mresults/showmr/' + pid + '/' + fdate + '/' + tdate, method: 'GET'})
          .then(function (response) {
            this.$set('mvalue', response.data)
            // 計算:各項
            var array = this.mvalue
            var s1 = 0 // 總次數
            var ad1 = 0, ad2 = 0, ad3 = 0, ad4 = 0, ad5 = 0, ad6 = 0, ad7 = 0, ad8 = 0, ad9 = 0 // 平均
            var sdd1 = [], sdd2 = [], sdd3 = [], sdd4 = [], sdd5 = [], sdd6 = [], sdd7 = [], sdd8 = [], sdd9 = [] // SD
            var ir1 = 0, ir2 = 0, ir3 = 0, ir4 = 0, ir5 = 0, ir6 = 0, ir7 = 0, ir8 = 0, ir9 = 0 // 理想範圍
            // 查血糖區間值
            var deepnightlow = 0, deepnighthigh = 0, weekuplow = 0, weekuphigh = 0, beforebreaklow = 0, beforebreakhigh = 0, afterbreaklow = 0, afterbreakhigh = 0, beforenoonlow = 0, beforenoonhigh = 0, afternoonlow = 0, afternoonhigh = 0, beforedinnerlow = 0, beforedinnerhigh = 0, afterdinnerlow = 0, afterdinnerhigh = 0, sleeplow = 0, sleephigh = 0
            this.$http({url: '/api/mresults/showbsrange/' + pid, method: 'GET'})
              .then(function (response) {
                if (response.data != '') {
                  deepnightlow = response.data.deepnightlow
                  deepnighthigh = response.data.deepnighthigh
                  weekuplow = response.data.weekuplow
                  weekuphigh = response.data.weekuphigh
                  beforebreaklow = response.data.beforebreaklow
                  beforebreakhigh = response.data.beforebreakhigh
                  afterbreaklow = response.data.afterbreaklow
                  afterbreakhigh = response.data.afterbreakhigh
                  beforenoonlow = response.data.beforenoonlow
                  beforenoonhigh = response.data.beforenoonhigh
                  afternoonlow = response.data.afternoonlow
                  afternoonhigh = response.data.afternoonhigh
                  beforedinnerlow = response.data.beforedinnerlow
                  beforedinnerhigh = response.data.beforedinnerhigh
                  afterdinnerlow = response.data.afterdinnerlow
                  afterdinnerhigh = response.data.afterdinnerhigh
                  sleeplow = response.data.sleeplow
                  sleephigh = response.data.sleephigh
                } else {
                  deepnightlow = 100
                  deepnighthigh = 150
                  weekuplow = 90
                  weekuphigh = 130
                  beforebreaklow = 90
                  beforebreakhigh = 130
                  afterbreaklow = 100
                  afterbreakhigh = 190
                  beforenoonlow = 90
                  beforenoonhigh = 130
                  afternoonlow = 100
                  afternoonhigh = 190
                  beforedinnerlow = 90
                  beforedinnerhigh = 130
                  afterdinnerlow = 100
                  afterdinnerhigh = 190
                  sleeplow = 100
                  sleephigh = 150
                }
                var rd1 = 0, rd2 = 0, rd3 = 0, rd4 = 0, rd5 = 0, rd6 = 0, rd7 = 0, rd8 = 0, rd9 = 0 // 結果數
                for (var i = 0; i < array.length; i++) {
                  if ( array[i]['value1'] > 0 ) {
                    s1 = s1 + 1
                    switch (array[i]['slot']) {
                      case 9:
                        rd1 = rd1 + 1
                        ad1 = ad1 + array[i]['value1']
                        if (array[i]['value1'] >= deepnightlow && array[i]['value1'] < deepnighthigh) {
                          ir1 = ir1 + 1
                        }
                        sdd1.push(array[i]['value1'])
                        break
                      case 1:
                        rd2 = rd2 + 1
                        ad2 = ad2 + array[i]['value1']
                        if (array[i]['value1'] >= weekuplow && array[i]['value1'] < weekuphigh) {
                          ir2 = ir2 + 1
                        }
                        sdd2.push(array[i]['value1'])
                        break
                      case 2:
                        rd3 = rd3 + 1
                        ad3 = ad3 + array[i]['value1']
                        if (array[i]['value1'] >= beforebreaklow && array[i]['value1'] < beforebreakhigh) {
                          ir3 = ir3 + 1
                        }
                        sdd3.push(array[i]['value1'])
                        break
                      case 3:
                        rd4 = rd4 + 1
                        ad4 = ad4 + array[i]['value1']
                        if (array[i]['value1'] >= afterbreaklow && array[i]['value1'] < afterbreakhigh) {
                          ir4 = ir4 + 1
                        }
                        sdd4.push(array[i]['value1'])
                        break
                      case 4:
                        rd5 = rd5 + 1
                        ad5 = ad5 + array[i]['value1']
                        if (array[i]['value1'] >= beforenoonlow && array[i]['value1'] < beforenoonhigh) {
                          ir5 = ir5 + 1
                        }
                        sdd5.push(array[i]['value1'])
                        break
                      case 5:
                        rd6 = rd6 + 1
                        ad6 = ad6 + array[i]['value1']
                        if (array[i]['value1'] >= afternoonlow && array[i]['value1'] < afternoonhigh) {
                          ir6 = ir6 + 1
                        }
                        sdd6.push(array[i]['value1'])
                        break
                      case 6:
                        rd7 = rd7 + 1
                        ad7 = ad7 + array[i]['value1']
                        if (array[i]['value1'] >= beforedinnerlow && array[i]['value1'] < beforedinnerhigh) {
                          ir7 = ir7 + 1
                        }
                        sdd7.push(array[i]['value1'])
                        break
                      case 7:
                        rd8 = rd8 + 1
                        ad8 = ad8 + array[i]['value1']
                        if (array[i]['value1'] >= afterdinnerlow && array[i]['value1'] < afterdinnerhigh) {
                          ir8 = ir8 + 1
                        }
                        sdd8.push(array[i]['value1'])
                        break
                      case 8:
                        rd9 = rd9 + 1
                        ad9 = ad9 + array[i]['value1']
                        if (array[i]['value1'] >= sleeplow && array[i]['value1'] < sleephigh) {
                          ir9 = ir9 + 1
                        }
                        sdd9.push(array[i]['value1'])
                        break
                    }
                  }
                }
                // 計算:總次數
                this.$set('totalTimes', s1)
                // 計算:次/週
                this.$set('totalWeeks', this.round(s1 / 2, 1))
                // 計算:次/日
                this.$set('totalDays', this.round(s1 / 14, 1))
                // 平均:凌晨/d1
                if(ad1 != 0 && rd1 != 0) this.$set('averaged1', this.round(ad1 / rd1, 0))
                if(ad2 != 0 && rd2 != 0) this.$set('averaged2', this.round(ad2 / rd2, 0))
                if(ad3 != 0 && rd3 != 0) this.$set('averaged3', this.round(ad3 / rd3, 0))
                if(ad4 != 0 && rd4 != 0) this.$set('averaged4', this.round(ad4 / rd4, 0))
                if(ad5 != 0 && rd5 != 0) this.$set('averaged5', this.round(ad5 / rd5, 0))
                if(ad6 != 0 && rd6 != 0) this.$set('averaged6', this.round(ad6 / rd6, 0))
                if(ad7 != 0 && rd7 != 0) this.$set('averaged7', this.round(ad7 / rd7, 0))
                if(ad8 != 0 && rd8 != 0) this.$set('averaged8', this.round(ad8 / rd8, 0))
                if(ad9 != 0 && rd9 != 0) this.$set('averaged9', this.round(ad9 / rd9, 0))
                // SD
                this.$set('sd1', this.stdev(sdd1))
                this.$set('sd2', this.stdev(sdd2))
                this.$set('sd3', this.stdev(sdd3))
                this.$set('sd4', this.stdev(sdd4))
                this.$set('sd5', this.stdev(sdd5))
                this.$set('sd6', this.stdev(sdd6))
                this.$set('sd7', this.stdev(sdd7))
                this.$set('sd8', this.stdev(sdd8))
                this.$set('sd9', this.stdev(sdd9))
                // 理想範圍
                if(ir1 != 0 && rd1 != 0) this.$set('idealranged1', this.round(ir1 / rd1 * 100, 0))
                if(ir2 != 0 && rd2 != 0) this.$set('idealranged2', this.round(ir2 / rd2 * 100, 0))
                if(ir3 != 0 && rd3 != 0) this.$set('idealranged3', this.round(ir3 / rd3 * 100, 0))
                if(ir4 != 0 && rd4 != 0) this.$set('idealranged4', this.round(ir4 / rd4 * 100, 0))
                if(ir5 != 0 && rd5 != 0) this.$set('idealranged5', this.round(ir5 / rd5 * 100, 0))
                if(ir6 != 0 && rd6 != 0) this.$set('idealranged6', this.round(ir6 / rd6 * 100, 0))
                if(ir7 != 0 && rd7 != 0) this.$set('idealranged7', this.round(ir7 / rd7 * 100, 0))
                if(ir8 != 0 && rd8 != 0) this.$set('idealranged8', this.round(ir8 / rd8 * 100, 0))
                if(ir9 != 0 && rd9 != 0) this.$set('idealranged9', this.round(ir9 / rd9 * 100, 0))
                // 結果數:凌晨/d1
                this.$set('resultd1', rd1)
                this.$set('resultd2', rd2)
                this.$set('resultd3', rd3)
                this.$set('resultd4', rd4)
                this.$set('resultd5', rd5)
                this.$set('resultd6', rd6)
                this.$set('resultd7', rd7)
                this.$set('resultd8', rd8)
                this.$set('resultd9', rd9)
              })
              .catch(function(response) {
              })

//        this.fetchMRNotes(pid, fdate, tdate)  // 取14天衛教備註, nvalue
            this.$http({url: '/api/mresults/shownote/' + pid + '/' + fdate + '/' + tdate, method: 'GET'})
              .then(function (response) {
                this.$set('nvalue', response.data)
              })
              .catch(function(response) {
                if (response.data == "Unauthorized." && response.status == 401) {
                  alert('Auto Logout after idle for 20 mins!!')
                  window.location.assign('/auth/logout')
                }
              })

//        this.filterMR() // 篩選沒有資料的日子
            // 篩選掉沒有資料的日子, mdweek1 vs. mvalue
            // console.log(this.mdweek1.length)
            // var mv = this.mvalue
            // console.log(Object.keys(mv).length)
            // console.log(array.length)
            // console.log(mv.length)
            // console.log(this.mdweek1[0]['mdate'])
            // console.log(this.mvalue[0]['mr_date'])
            var mi
            var mj
            for(var i = 0; i < this.mdweek1.length; i++) {
              // search match
              mi = this.mdweek1[i]['mdate']
              for(var j = 0; j < array.length; j++) {
                mj = array[j]['mr_date']
                // console.log(mj)
                if ( mi.substr(0, 10) == mj.substr(0, 10) ) {
                  this.mdweek.push({ 'mdate': mi })
                  break
                }
              }
            }
            this.mdweek1 = []  // 清空
          })
          .catch(function(response) {
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      }
    },

    computed: {
    }
  }
</script>

<style>
  /*
      body {
          background-color: #ff0000;
      }
  */
  .table {
    table-layout: fixed;
    /* text-align: left; */
    /* vertical-align: middle; */
    /* word-wrap: break-word; */
    /* white-space: -moz-pre-wrap; */
  }

  .tdtiny {
    background-color: #eee;
    word-break: break-all !important;
    /* overflow: hidden; */
    /* width: 100px; */
    /* display: inline-block; */
    /* word-wrap: break-word; */
    /* white-space: pre-wrap; */
  }
</style>
