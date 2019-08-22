<template>
  <!-- Display Validation Errors -->
  <!-- general form elements -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">編輯健保方案</h3>
    </div>
    <!-- /.box-header -->
      <!-- form start -->
      <!-- form role="form" novalidate -->
      <form role="form">
        <div class="box-body">
          <form-group :valid.sync="valid.update">

            <!-- 1 -->
            <div v-if=" caseStage != '' ">
              <accordion one-at-atime type="success">
                <panel is-open type="success">
                  <strong slot="header"><u>Part-I</u></strong>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="case_date"><span class="text-danger">*收案日期</span></label>
                    <div class="col-xs-7">
                      <datepicker v-ref:dp :value.sync="cases.case_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="prsn_id"><span class="text-danger">*收案醫師</span></label>
                    <div class="col-xs-7">
                      <v-select name="prsn_id" :options="doctors" :value.sync="cases.prsn_id" clear-button></v-select>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="cure_stage"><span class="text-danger">*診療階段</span></label>
                    <div class="col-xs-7">
                      <bs-input name="cure_stage" :value.sync="cases.cure_stage" disabled></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="bp"><span class="text-danger">*血壓</span></label>
                    <div class="col-xs-2">
                      <bs-input name="base_sbp" :value.sync="cases.base_sbp" required></bs-input>
                    </div>
                    <div class="col-xs-1">
                      /
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="base_ebp" :value.sync="cases.base_ebp" required></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mmHg
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="pr"><span class="text-danger">*脈搏</span></label>
                    <div class="col-xs-5">
                      <bs-input name="pulse" :value.sync="cases.pulse" required></bs-input>
                    </div>
                    <div class="col-xs-2">
                      次/分
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="tall"><span class="text-danger">*身高</span></label>
                    <div class="col-xs-5">
                      <bs-input name="base_tall" :value.sync="cases.base_tall" required></bs-input>
                    </div>
                    <div class="col-xs-2">
                      cm
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="weight"><span class="text-danger">*體重</span></label>
                    <div class="col-xs-3">
                      <bs-input name="base_weight" :value.sync="cases.base_weight" required></bs-input>
                    </div>
                    <div class="col-xs-1">
                      kg
                    </div>
                    <div class="col-xs-3">
                      <v-checkbox :checked.sync="cases.noweight" value="無法測量" type="danger">無法測量</v-checkbox>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="ibw">IBW/BMI</label>
                    <div class="col-xs-2">
                      <bs-input name="ibw" :value.sync="cases.ibw" placeholder="自動計算加總" help="此欄位係自動計算!" :disabled="isIbw" icon></bs-input>
                    </div>
                    <div class="col-xs-1">
                      kg/
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="base_bmi" :value.sync="cases.base_bmi" placeholder="自動計算加總" help="此欄位係自動計算!" :disabled="isBmi" icon></bs-input>
                    </div>
                    <div class="col-xs-2">
                      kg/m<sup>2</sup>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="waist">腰/腎圍</label>
                    <div class="col-xs-2">
                      <bs-input name="waist" :value.sync="cases.waist"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      cm/
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="hips" :value.sync="cases.hips"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      cm
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="acpc"><span class="text-danger">*血糖</span></label>
                    <div class="col-xs-2">
                      <radio :checked.sync="cases.blood_mne" value="早" type="info">早</radio>
                      <radio :checked.sync="cases.blood_mne" value="中" type="info">中</radio>
                      <radio :checked.sync="cases.blood_mne" value="晚" type="info">晚</radio>
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="blood_acpc" :value.sync="cases.blood_acpc"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-2 col-xs-offset-3">
                      <radio :checked.sync="cases.blood_ap" value="前" type="warning">前</radio>
                      <radio :checked.sync="cases.blood_ap" value="後" type="warning">後</radio>
                    </div>
                    <div class="col-xs-2">
                      <bs-input name="blood_mins" :value.sync="cases.blood_mins" :disabled="isNoneW"></bs-input>
                    </div>
                    <div class="col-xs-1">
                      分
                    </div>
                    <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="laboratory">檢驗室Lab</label>
                    <div class="col-xs-7">
                      <bs-input name="laboratory" :value.sync="cases.laboratory"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="bgmachine">病患使用血糖機廠牌</label>
                    <div class="col-xs-7">
                      <bs-input name="bgmachine" :value.sync="cases.bgmachine"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="machine_blood">病患血糖機血糖</label>
                    <div class="col-xs-5">
                      <bs-input name="machine_blood" :value.sync="cases.machine_blood"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dl
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="machine_use">血糖機使用</label>
                    <div class="col-xs-7">
                      <p><pre>Radio value: {{ cases.machine_use | json }}</pre></p>
                      <radio :checked.sync="cases.machine_use" value="正常" type="info">正常</radio>
                      <div class="row">
                        <div class="col-xs-2">
                          <radio :checked.sync="cases.machine_use" value="異常" type="danger">異常</radio>
                        </div>
                        <div class="col-xs-7">
                          <multiselect :selected.sync="cases.machine_exception"
                                       :options="['試紙超過三個月', '試紙過期', 'Code不符', '分裝', '比對&#62;&#177;15%', '比對&#62;&#177;15mg/dl', '其他']"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateMachineexception">

                          </multiselect>
                        </div>
                        <div class="col-xs-3">
                          <bs-input name="machine_other" :value.sync="cases.machine_other" placeholder="請填寫!"></bs-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="smoking"><span class="text-danger">*抽菸</span></label>
                    <div class="col-xs-5">
                      <radio :checked.sync="cases.smoking" value="無" type="info">無</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-2 col-xs-offset-3">
                      <radio :checked.sync="cases.smoking" value="有" type="info">有</radio>
                    </div>
                    <div class="col-xs-3">
                      <div class="row">
                        <div class="col-xs-6">
                          <bs-input name="havesmoke" :value.sync="cases.havesmoke" :disabled="isNoneSm"></bs-input>
                        </div>
                        <div class="col-xs-6">
                          支
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-2 col-xs-offset-3">
                      <radio :checked.sync="cases.smoking" value="已戒" type="info">已戒</radio>
                    </div>
                    <div class="col-xs-3">
                      <bs-input name="quitsmoke" :value.sync="cases.quitsmoke" :disabled="isNoneSm"></bs-input>
                    </div>
                    <div class="col-xs-1 col-xs-offset-3"><span> </span></div>
                  </div>
                </panel>
              </accordion>
            </div>

            <!-- 2 -->
            <div v-if=" caseStage != '一般' ">
              <accordion one-at-atime type="warning">
                <panel is-open type="warning">
                  <strong slot="header"><u>Part-II</u></strong>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="ulcers">足部潰瘍/壞疽</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.ulcers" buttons="false">
                        <p><pre>Values: {{ cases.ulcers }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="有" type="warning" :disabled="isNoneU">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="急性期" type="warning" :disabled="isNoneU">急性期</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <v-select name="ulcers_urgent" :options="['右','左','雙腳']" :value.sync="cases.ulcers_urgent" :disabled="isNoneU" clear-button></v-select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="慢性期" type="warning" :disabled="isNoneU">慢性期</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <v-select name="ulcers_slow" :options="['右','左','雙腳']" :value.sync="cases.ulcers_slow" :disabled="isNoneU" clear-button></v-select>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="eye_chk8"><span class="text-danger">*視網膜病變</span></label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.eye_chk8" buttons="false">
                        <p><pre>Values: {{ cases.eye_chk8 }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="正常" type="info" :disabled="isNoneE">正常</v-checkbox>
                        <v-checkbox value="有" type="warning" :disabled="isNoneE">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="右" type="warning" :disabled="isNoneE">右</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <v-select name="eye_chk8_right" :options="['NPDR','PDR','NPDR/PRP','PDR/PRP']" :value.sync="cases.eye_chk8_right" :disabled="isNoneE" clear-button></v-select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="左" type="warning" :disabled="isNoneE">左</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <v-select name="eye_chk8_left" :options="['NPDR','PDR','NPDR/PRP','PDR/PRP']" :value.sync="cases.eye_chk8_left" :disabled="isNoneE" clear-button></v-select>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="kidneydisease"><span class="text-danger">*腎病變</span></label>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.kidneydisease" value="無" type="info">無</radio>
                    </div>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.kidneydisease" value="有" type="info">有</radio>
                    </div>
                    <div class="col-xs-1">
                      <label class="control-label col-xs-2" for="kidneydisease_stage">Stage</label>
                    </div>
                    <div class="col-xs-4">
                      <v-select name="kidneydisease_stage" :options="['1','2','3a','3b','4','5']" :value.sync="cases.kidneydisease_stage" :disabled="isNoneK" clear-button></v-select>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="neuropathy">神經病變</label>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.neuropathy" value="無" type="info">無</radio>
                    </div>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.neuropathy" value="有" type="info">有</radio>
                    </div>
                    <div class="col-xs-5 col-xs-offset-2"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="vascularlesion">周邊血管病變</label>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.vascularlesion" value="無" type="info">無</radio>
                    </div>
                    <div class="col-xs-1">
                      <radio :checked.sync="cases.vascularlesion" value="有" type="info">有</radio>
                    </div>
                    <div class="col-xs-5 col-xs-offset-2"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="intermittentpain">下肢間歇痛</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.intermittentpain" buttons="false">
                        <p><pre>Values: {{ cases.intermittentpain }}</pre></p>
                        <div class="row">
                          <div class="col-xs-1">
                            <v-checkbox value="無">無</v-checkbox>
                          </div>
                          <div class="col-xs-1">
                            <v-checkbox value="有" type="info" :disabled="isNoneI">有</v-checkbox>
                          </div>
                          <div class="col-xs-1">
                            <v-checkbox value="右" type="info" :disabled="isNoneI">右</v-checkbox>
                          </div>
                          <div class="col-xs-1">
                            <v-checkbox value="左" type="info" :disabled="isNoneI">左</v-checkbox>
                          </div>
                          <div class="col-xs-8">
                            <v-checkbox value="未檢查" type="warning" :disabled="isNoneI">未檢查</v-checkbox>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="coronaryheart">冠心病</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.coronaryheart" buttons="false">
                        <p><pre>Values: {{ cases.coronaryheart }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="有" type="info" :disabled="isNoneH">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-11 col-xs-offset-1">
                            <v-checkbox value="血管形成術" type="info" :disabled="isNoneH">血管形成術</v-checkbox>
                            <v-checkbox value="血管擴張術" type="info" :disabled="isNoneH">血管擴張術</v-checkbox>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <v-checkbox value="支架" type="info" :disabled="isNoneH">支架</v-checkbox>
                          </div>
                          <div class="col-xs-2">
                            <bs-input name="coronaryheart_item" :value.sync="cases.coronaryheart_item" :disabled="isNoneH"></bs-input>
                          </div>
                          <div class="col-xs-7">
                            支
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <v-checkbox value="其他" type="warning" :disabled="isNoneH">其他</v-checkbox>
                          </div>
                          <div class="col-xs-9">
                            <bs-input name="coronaryheart_other" :value.sync="cases.coronaryheart_other" :disabled="isNoneH"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-3 col-xs-offset-1" for="chh_year">發生日期: 西元年</label>
                          <div class="col-xs-3">
                            <multiselect :options="yearoptions" :selected.sync="cases.chh_year" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateYear" :disabled="isNoneH">
                            </multiselect>
                          </div>
                          <label class="control-label col-xs-2" for="chh_month">月份</label>
                          <div class="col-xs-3">
                            <multiselect :options="monthoptions" :selected.sync="cases.chh_month" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateMonth" :disabled="isNoneH">
                            </multiselect>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="stroke">腦中風</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.stroke" buttons="false">
                        <p><pre>Values: {{ cases.stroke }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="有" type="info" :disabled="isNoneS">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-11 col-xs-offset-1">
                            <v-checkbox value="梗塞型" type="info" :disabled="isNoneS">梗塞型</v-checkbox>
                            <v-checkbox value="出血型" type="info" :disabled="isNoneS">出血型</v-checkbox>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <v-checkbox value="其他" type="warning" :disabled="isNoneS">其他</v-checkbox>
                          </div>
                          <div class="col-xs-9">
                            <bs-input name="stroke_other" :value.sync="cases.stroke_other" :disabled="isNoneS"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-3 col-xs-offset-1" for="sh_year">發生日期: 西元年</label>
                          <div class="col-xs-3">
                            <multiselect :options="yearoptions" :selected.sync="cases.sh_year" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateshYear" :disabled="isNoneS">
                            </multiselect>
                          </div>
                          <label class="control-label col-xs-2" for="sh_month">月份</label>
                          <div class="col-xs-3">
                            <multiselect :options="monthoptions" :selected.sync="cases.sh_month" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateshMonth" :disabled="isNoneS">
                            </multiselect>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="blindness">失明</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.blindness" buttons="false">
                        <p><pre>Values: {{ cases.blindness }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="有" type="info" :disabled="isNoneB">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <v-checkbox value="右" type="info" :disabled="isNoneB">右</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-select name="blindness_right" :options="['糖尿病','非糖尿病']" :value.sync="cases.blindness_right" :disabled="isNoneB" clear-button></v-select>
                          </div>
                          <div class="col-xs-2">
                            <v-checkbox value="左" type="info" :disabled="isNoneS">左</v-checkbox>
                          </div>
                          <div class="col-xs-4">
                            <v-select name="blindness_left" :options="['糖尿病','非糖尿病']" :value.sync="cases.blindness_left" :disabled="isNoneB" clear-button></v-select>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-3 col-xs-offset-1" for="bh_year">發生日期: 西元年</label>
                          <div class="col-xs-3">
                            <multiselect :options="yearoptions" :selected.sync="cases.bh_year" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updatebhYear" :disabled="isNoneB">
                            </multiselect>
                          </div>
                          <label class="control-label col-xs-2" for="bh_month">月份</label>
                          <div class="col-xs-3">
                            <multiselect :options="monthoptions" :selected.sync="cases.bh_month" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updatebhMonth" :disabled="isNoneB">
                            </multiselect>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="dialysis">洗腎</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.dialysis" buttons="false">
                        <p><pre>Values: {{ cases.dialysis }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="有" type="info" :disabled="isNoneD">有</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <v-select name="dialysis_item" :options="['血液透析','腹膜透析']" :value.sync="cases.dialysis_item" :disabled="isNoneD" clear-button></v-select>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-3 col-xs-offset-1" for="dh_year">發生日期: 西元年</label>
                          <div class="col-xs-3">
                            <multiselect :options="yearoptions" :selected.sync="cases.dh_year" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updatedhYear" :disabled="isNoneD">
                            </multiselect>
                          </div>
                          <label class="control-label col-xs-2" for="dh_month">月份</label>
                          <div class="col-xs-3">
                            <multiselect :options="monthoptions" :selected.sync="cases.dh_month" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updatedhMonth" :disabled="isNoneD">
                            </multiselect>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="amputation">下肢截肢</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.amputation" buttons="false">
                        <p><pre>Values: {{ cases.amputation }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <v-checkbox value="有" type="info" :disabled="isNoneN">有</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <v-checkbox value="右" type="info" :disabled="isNoneN">右</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-select name="amputation_right" :options="['糖尿病','非糖尿病']" :value.sync="cases.amputation_right" :disabled="isNoneN" clear-button></v-select>
                          </div>
                          <div class="col-xs-2">
                            <v-checkbox value="左" type="info" :disabled="isNoneS">左</v-checkbox>
                          </div>
                          <div class="col-xs-4">
                            <v-select name="amputation_left" :options="['糖尿病','非糖尿病']" :value.sync="cases.amputation_left" :disabled="isNoneN" clear-button></v-select>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-3 col-xs-offset-1" for="ah_year">發生日期: 西元年</label>
                          <div class="col-xs-3">
                            <multiselect :options="yearoptions" :selected.sync="cases.ah_year" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateahYear" :disabled="isNoneN">
                            </multiselect>
                          </div>
                          <label class="control-label col-xs-2" for="ah_month">月份</label>
                          <div class="col-xs-3">
                            <multiselect :options="monthoptions" :selected.sync="cases.ah_month" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateahMonth" :disabled="isNoneN">
                            </multiselect>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-11 col-xs-offset-1">
                            <bs-input name="amputation_other" :value.sync="cases.amputation_other" :disabled="isNoneN"></bs-input>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="medicaltreatment">高低血糖就醫</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.medicaltreatment" buttons="false">
                        <p><pre>Values: {{ cases.medicaltreatment }}</pre></p>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="無">無</v-checkbox>
                          </div>
                          <div class="col-xs-2">
                            <v-checkbox value="有" type="info" :disabled="isNoneM">有</v-checkbox>
                          </div>
                          <div class="col-xs-2">
                            <bs-input name="medicaltreatment_times" :value.sync="cases.medicaltreatment_times" :disabled="isNoneM"></bs-input>
                          </div>
                          <div class="col-xs-2">
                            次/月
                          </div>
                          <div class="col-xs-2">
                            <v-checkbox value="急診" type="warning" :disabled="isNoneM">急診</v-checkbox>
                          </div>
                          <div class="col-xs-2">
                            <v-checkbox value="住院" type="warning" :disabled="isNoneM">住院</v-checkbox>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="abi">ABI</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.abi" buttons="false">
                        <p><pre>Values: {{ cases.abi }}</pre></p>
                        <v-checkbox value="正常">正常</v-checkbox>
                        <v-checkbox value="異常" type="warning" :disabled="isNoneA">異常</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="右" type="warning" :disabled="isNoneA">右</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="abi_right" :value.sync="cases.abi_right" :disabled="isNoneA"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="左" type="warning" :disabled="isNoneA">左</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="abi_left" :value.sync="cases.abi_left" :disabled="isNoneA"></bs-input>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="cavi">CAVI</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.cavi" buttons="false">
                        <p><pre>Values: {{ cases.cavi }}</pre></p>
                        <v-checkbox value="正常">正常</v-checkbox>
                        <v-checkbox value="異常" type="warning" :disabled="isNoneC">異常</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="右" type="warning" :disabled="isNoneC">右</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="cavi_right" :value.sync="cases.cavi_right" :disabled="isNoneC"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="左" type="warning" :disabled="isNoneC">左</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="cavi_left" :value.sync="cases.cavi_left" :disabled="isNoneC"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="R-Kcavi" type="warning" :disabled="isNoneC">R-Kcavi</v-checkbox>
                          </div>
                          <div class="col-xs-10">
                            <bs-input name="cavi_rkcavi" :value.sync="cases.cavi_rkcavi" :disabled="isNoneC"></bs-input>
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </panel>
              </accordion>
            </div>

            <!-- 3 -->
            <div v-if=" caseStage == 'DM初診' || caseStage == '非方案DM初診' || caseStage == 'DM年度' || caseStage == '非方案DM年度' || caseStage == 'CKD複診+DM年度' ">
              <accordion one-at-atime type="success">
                <panel is-open type="success">
                  <strong slot="header"><u>Part-III</u></strong>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="foot_chk_right">足部檢查 (右)</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.foot_chk_right" buttons="false">
                        <p><pre>Values: {{ cases.foot_chk_right }}</pre></p>
                        <v-checkbox value="正常">正常</v-checkbox>
                        <div class="row">
                          <div class="col-xs-3">
                            <v-checkbox value="震動" type="info" :disabled="isNoneR">震動</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="針刺" type="info" :disabled="isNoneR">針刺</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="脈搏" type="info" :disabled="isNoneR">脈搏</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="繞道" type="info" :disabled="isNoneR">繞道</v-checkbox>
                          </div>
                        </div>
                        <v-checkbox value="未檢查" type="warning" :disabled="isNoneR">未檢查</v-checkbox>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="foot_chk_left">足部檢查 (左)</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.foot_chk_left" buttons="false">
                        <p><pre>Values: {{ cases.foot_chk_left }}</pre></p>
                        <v-checkbox value="正常">正常</v-checkbox>
                        <div class="row">
                          <div class="col-xs-3">
                            <v-checkbox value="震動" type="info" :disabled="isNoneL">震動</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="針刺" type="info" :disabled="isNoneL">針刺</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="脈搏" type="info" :disabled="isNoneL">脈搏</v-checkbox>
                          </div>
                          <div class="col-xs-3">
                            <v-checkbox value="繞道" type="info" :disabled="isNoneL">繞道</v-checkbox>
                          </div>
                        </div>
                        <v-checkbox value="未檢查" type="warning" :disabled="isNoneL">未檢查</v-checkbox>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="cataract">白內障</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.cataract" buttons="false">
                        <p><pre>Values: {{ cases.cataract }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <div class="row">
                          <div class="col-xs-4">
                            <v-checkbox value="有" type="info" :disabled="isNoneT">有</v-checkbox>
                          </div>
                          <div class="col-xs-4">
                            <v-checkbox value="右" type="info" :disabled="isNoneT">右</v-checkbox>
                          </div>
                          <div class="col-xs-4">
                            <v-checkbox value="左" type="info" :disabled="isNoneT">左</v-checkbox>
                          </div>
                        </div>
                        <v-checkbox value="未檢查" type="warning" :disabled="isNoneT">未檢查</v-checkbox>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="ecg">心電圖</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.ecg" buttons="false">
                        <p><pre>Values: {{ cases.ecg }}</pre></p>
                        <v-checkbox value="正常">正常</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="異常" type="info" :disabled="isNoneG">異常</v-checkbox>
                          </div>
                          <div class="col-xs-5">
                            <v-select name="ecgitem" :options="['PVC','Af','NS-ST change','其他']" :value.sync="cases.ecgitem" :disabled="isNoneG" clear-button></v-select>
                          </div>
                          <div class="col-xs-5">
                            <bs-input name="ecgother" :value.sync="cases.ecgother" :disabled="isNoneG"></bs-input>
                          </div>
                        </div>
                        <v-checkbox value="未檢查" type="warning" :disabled="isNoneG">未檢查</v-checkbox>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="drinking">飲酒</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.drinking" buttons="false">
                        <p><pre>Values: {{ cases.drinking }}</pre></p>
                        <v-checkbox value="無">無</v-checkbox>
                        <div class="row">
                          <div class="col-xs-2">
                            <v-checkbox value="有" type="info" :disabled="isNoneO">有</v-checkbox>
                          </div>
                          <div class="col-xs-8">
                            <bs-input name="drinkingother" :value.sync="cases.drinkingother" :disabled="isNoneO"></bs-input>
                          </div>
                          <div class="col-xs-2">
                            c.c/週
                          </div>
                        </div>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="periodontal">牙周病變</label>
                    <div class="col-xs-7">
                      <radio :checked.sync="cases.periodontal" value="無" type="info">無</radio>
                      <radio :checked.sync="cases.periodontal" value="有" type="info">有</radio>
                      <radio :checked.sync="cases.periodontal" value="不詳" type="info">不詳</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="masticatory">咀嚼功能</label>
                    <div class="col-xs-7">
                        <radio :checked.sync="cases.masticatory" value="正常" type="info">正常</radio>
                        <radio :checked.sync="cases.masticatory" value="異常" type="info">異常</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </panel>
              </accordion>
            </div>

            <!-- 4 -->
            <div v-if=" caseStage == 'CKD初診' || caseStage == 'CKD+DM複診' || caseStage == 'CKD複診+DM年度' ">
              <accordion one-at-atime type="warning">
                <panel is-open type="warning">
                  <strong slot="header"><u>Part-IV</u></strong>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="followdisease">其他疾病</label>
                    <div class="col-xs-7">
                      <button-group :value.sync="cases.followdisease" buttons="false">
                        <p><pre>Values: {{ cases.followdisease }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <v-checkbox value="有" type="info" :disabled="isNoneF">有</v-checkbox>
                          <v-checkbox value="高血壓" type="warning" :disabled="isNoneF">高血壓</v-checkbox>
                          <v-checkbox value="心血管疾病" type="warning" :disabled="isNoneF">心血管疾病</v-checkbox>
                          <v-checkbox value="高血脂症" type="warning" :disabled="isNoneF">高血脂症</v-checkbox>
                          <v-checkbox value="慢性肝病" type="warning" :disabled="isNoneF">慢性肝病</v-checkbox>
                          <v-checkbox value="癌症" type="warning" :disabled="isNoneF">癌症</v-checkbox>
                      </button-group>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </panel>
              </accordion>
            </div>

            <!-- 5 -->
            <div v-if="caseStage != '一般'">
              <accordion one-at-atime type="info">
                <panel is-open type="info">
                  <strong slot="header"><u>檢驗項目</u></strong>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="a1c">A1C</label>
                    <div class="col-xs-5">
                      <bs-input name="blood_hba1c" :value.sync="cases.blood_hba1c"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      %
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="cholesterol">Cholesterol</label>
                    <div class="col-xs-5">
                      <bs-input name="cholesterol" :value.sync="cases.cholesterol"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="triglyceride">Triglyceride</label>
                    <div class="col-xs-5">
                      <bs-input name="blood_tg" :value.sync="cases.blood_tg"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="ldl">LDL</label>
                    <div class="col-xs-5">
                      <bs-input name="blood_ldl" :value.sync="cases.blood_ldl"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="hdl">HDL</label>
                    <div class="col-xs-5">
                      <bs-input name="hdl" :value.sync="cases.hdl"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="gpt">GPT</label>
                    <div class="col-xs-5">
                      <bs-input name="gpt" :value.sync="cases.gpt"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      U/L
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="blood_creat">Creatinine</label>
                    <div class="col-xs-5">
                      <bs-input name="blood_creat" :value.sync="cases.blood_creat"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="uricacid">Uric Acid</label>
                    <div class="col-xs-5">
                      <bs-input name="uricacid" :value.sync="cases.uricacid"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/dL
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="urine_micro">A/C ratio</label>
                    <div class="col-xs-5">
                      <bs-input name="urine_micro" :value.sync="cases.urine_micro"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/g
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="upcr">U<sub>PCR</sub></label>
                    <div class="col-xs-5">
                      <bs-input name="upcr" :value.sync="cases.upcr"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      mg/g
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="urine_routine">Urine protein</label>
                    <div class="col-xs-7">
                      <radio :checked.sync="cases.urine_routine" value="正常" type="success">正常</radio>
                      <radio :checked.sync="cases.urine_routine" value="+" type="warning">+</radio>
                      <radio :checked.sync="cases.urine_routine" value="2+" type="warning">2+</radio>
                      <radio :checked.sync="cases.urine_routine" value="3+" type="warning">3+</radio>
                      <radio :checked.sync="cases.urine_routine" value="4+" type="warning">4+</radio>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                  <div class="row">
                    <label class="control-label col-xs-2 col-xs-offset-1" for="egfr">eGFR</label>
                    <div class="col-xs-5">
                      <bs-input name="egfr" :value.sync="cases.egfr"></bs-input>
                    </div>
                    <div class="col-xs-2">
                      ml/min/1.73m<sup>2</sup>
                    </div>
                    <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                  </div>
                </panel>
              </accordion>
            </div>

          </form-group>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="button" :disabled="!valid.update" @click="updateCase(cases)" class="btn btn-lg btn-primary btn-flat">
            <i class="fa fa-cloud" aria-hidden="true"></i> 存</button>
          <!-- button type="button" @click="deleteCase(cases)" class="btn btn-lg btn-danger btn-flat"><i class="fa fa-trash-o" aria-hidden="true"></i> 刪</button -->
        </div>
      </form>
    <!-- /validator -->
  </div>
  <!-- /.box -->
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import formGroup from './vue-strap/src/FormGroup.vue'
  import buttonGroup from './vue-strap/src/buttonGroup.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import tab from './vue-strap/src/Tab.vue'
  import tabs from './vue-strap/src/Tabset.vue'
  import vSelect from './vue-strap/src/Select.vue'
  import vCheckbox from './vue-strap/src/Checkbox.vue'
  import vOption from './vue-strap/src/Option.vue'
  import accordion from './vue-strap/src/Accordion.vue'
  import panel from './vue-strap/src/Panel.vue'
  import radio from './vue-strap/src/Radio.vue'
  import datepicker from './vue-strap/src/Datepicker.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

  export default {
    components: {
      formGroup,
      buttonGroup,
      bsInput,
      tab,
      tabs,
      vSelect,
      vCheckbox,
      vOption,
      accordion,
      panel,
      radio,
      datepicker,
      Multiselect
    },
    created () {
      this.caseId = this.$route.params.caseId
      this.caseStage = this.$route.params.caseStage
      this.fetchToday()
      this.fetchYear()
      this.fetchMonth()
      this.fetchCase()
    },
    ready () {
      this.fetchOwnUser()
      this.fetchDoctors()
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        active: 0,
        userowner: {},
        caseId: '',
        caseStage: '',
        cases: {},
        patient: '',
        valid: {},
        today: '',
        yearoptions: [],
        monthoptions: [],
        doctors: []
      }
    },
    methods: {
      fetchOwnUser () {
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
      fetchDoctors () {
        this.$http({url: '/api/doctors/alldoctors', method: 'GET'})
          .then(function (response) {
            // console.log(JSON.stringify(response.data.data))
            // console.log(response.data)
            this.$set('doctors', response.data.data)
            // console.log(this.doctors)
          })
          .catch(function(response) {
            console.log(response)
          })
      },
      fetchToday () {
        //today
        var date = new Date()
        var thisday = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
        this.today = thisday
        this.$set('cases.case_date', thisday)
//        console.log(this.basist2dm.close_date)
      },
      fetchYear () {
        var date = new Date()
        var thisyear = date.getFullYear()
        var years = ['不詳']
        // range = from current year ~ 1911
        // years.push('不詳')
        for (var d = thisyear; d >= 1911; d--) {
          years.push(d)
        }
        this.$set('yearoptions', years)
      },
      fetchMonth () {
        // range = from 01 ~ 12
        // months.push('不詳')
        var months = ['不詳']
        var ms = null
        for (var m = 1; m <= 12; m++) {
          ms = ('0' + m).substr(-2)
          months.push(ms)
        }
        this.$set('monthoptions', months)
      },
      updateYear (value) {
        this.cases.chh_year = value
      },
      updateMonth (value) {
        this.cases.chh_month = value
      },
      updateshYear (value) {
        this.cases.sh_year = value
      },
      updateshMonth (value) {
        this.cases.sh_month = value
      },
      updatebhYear (value) {
        this.cases.bh_year = value
      },
      updatebhMonth (value) {
        this.cases.bh_month = value
      },
      updatedhYear (value) {
        this.cases.dh_year = value
      },
      updatedhMonth (value) {
        this.cases.dh_month = value
      },
      updateahYear (value) {
        this.cases.ah_year = value
      },
      updateahMonth (value) {
        this.cases.ah_month = value
      },
      fetchCase () {
        this.$http({url: '/api/cases/' + this.caseId + '/edit', method: 'GET'}).then(function (response) {
          // success callback
          this.$set('cases', response.data)
        }, function (response) {
          // error callback
        })
      },
      updateCase (ucase) {
        //event.preventDefault();
        this.$http.patch('/api/cases/' + ucase.id, ucase).then(function (response) {
          show_stack_success('健保方案已更新!', response)
        }, function (response) {
          show_stack_error('健保方案更新失敗!', response)
        })
        this.$router.go('/')
      },
      updateMachineexception (value) {
        this.cases.machine_exception = value
      },
//      deleteCase (dcase) {
//        let self = this
//        swal({
//          title: '確定嗎?!',
//          text: '您將無法救回此健保方案!!',
//          type: 'warning',
//          showCancelButton: true,
//          confirmButtonText: '是的, 刪除!!',
//          cancelButtonText: '不, 取消!!',
//        }).then(function () {
//          self.$http.delete('/api/cases/' + dcase.id, dcase).then(function (response) {
//            self.$router.go('/cases')
//            swal(
//              '已刪除!!',
//              '健保方案已經刪除!!',
//              'success'
//            );
//          }, function (response) {
//            show_stack_error('刪除健保方案發生錯誤!!', response)
//          })
//        }, function (dismiss) {
//          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
//          if (dismiss === 'cancel') {
//            swal(
//              '已取消',
//              '健保方案維持現狀 :)',
//              'error'
//            );
//          }
//        });
//      },
    },
    computed: {
      isNoneU: function () {
        if (this.cases.ulcers != null) {
          var ulcerss = this.cases.ulcers || ""
          var a = ulcerss.indexOf("無")
          if (a >= 0) {
//            this.cases.quickeffect = ""
            var items = ['有','急性期','慢性期']
            var ax
            items.forEach(function(item) {
              ax = ulcerss.indexOf(item)
              if (ax !== -1) {
                ulcerss.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneE: function () {
        if (this.cases.eye_chk8 != null) {
          var eyechk8s = this.cases.eye_chk8 || ""
          var a = eyechk8s.indexOf("無")
          if (a >= 0) {
      //    this.cases.quickeffect = ""
            var items = ['正常','有','右','左']
            var ax
            items.forEach(function(item) {
              ax = eyechk8s.indexOf(item)
              if (ax !== -1) {
                eyechk8s.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneA: function () {
        if (this.cases.abi != null) {
          var abis = this.cases.abi || ""
          var a = abis.indexOf("正常")
          if (a >= 0) {
            this.cases.abi_right = ""
            this.cases.abi_left = ""
            var items = ['異常','右','左']
            var ax
            items.forEach(function(item) {
              ax = abis.indexOf(item)
              if (ax !== -1) {
                abis.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneC: function () {
        if (this.cases.cavi != null) {
          var cavis = this.cases.cavi || ""
          var a = cavis.indexOf("正常")
          if (a >= 0) {
            this.cases.cavi_right = ""
            this.cases.cavi_left = ""
            this.cases.cavi_rkcavi = ""
            var items = ['異常','右','左','R-Kcavi']
            var ax
            items.forEach(function(item) {
              ax = cavis.indexOf(item)
              if (ax !== -1) {
                cavis.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneK: function () {
        if (this.cases.kidneydisease != null) {
          var kidneydiseases = this.cases.kidneydisease || ""
          var a = kidneydiseases.indexOf("無")
          if (a >= 0) {
            this.cases.kidneydisease_stage = ""
            var items = ['有']
            var ax
            items.forEach(function(item) {
              ax = kidneydiseases.indexOf(item)
              if (ax !== -1) {
                kidneydiseases.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneI: function () {
        if (this.cases.intermittentpain != null) {
          var intermittentpains = this.cases.intermittentpain || ""
          var a = intermittentpains.indexOf("無")
          if (a >= 0) {
            var items = ['有','右','左','未檢查']
            var ax
            items.forEach(function(item) {
              ax = intermittentpains.indexOf(item)
              if (ax !== -1) {
                intermittentpains.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneH: function () {
        if (this.cases.coronaryheart != null) {
          var coronaryhearts = this.cases.coronaryheart || ""
          var a = coronaryhearts.indexOf("無")
          if (a >= 0) {
            this.cases.coronaryheart_item = ""
            this.cases.coronaryheart_other = ""
            this.cases.chh_year = "不詳"
            this.cases.chh_month = "不詳"
            var items = ['有','血管形成術','血管擴張術','支架','其他']
            var ax
            items.forEach(function(item) {
              ax = coronaryhearts.indexOf(item)
              if (ax !== -1) {
                coronaryhearts.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneS: function () {
        if (this.cases.stroke != null) {
          var strokes = this.cases.stroke || ""
          var a = strokes.indexOf("無")
          if (a >= 0) {
            this.cases.stroke_other = ""
            this.cases.sh_year = "不詳"
            this.cases.sh_month = "不詳"
            var items = ['有','梗塞型','出血型','其他']
            var ax
            items.forEach(function(item) {
              ax = strokes.indexOf(item)
              if (ax !== -1) {
                strokes.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneB: function () {
        if (this.cases.blindness != null) {
          var blindnesss = this.cases.blindness || ""
          var a = blindnesss.indexOf("無")
          if (a >= 0) {
            this.cases.stroke_other = ""
            this.cases.blindness_right = ""
            this.cases.blindness_left = ""
            this.cases.bh_year = "不詳"
            this.cases.bh_month = "不詳"
            var items = ['有','右','左']
            var ax
            items.forEach(function(item) {
              ax = blindnesss.indexOf(item)
              if (ax !== -1) {
                blindnesss.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneD: function () {
        if (this.cases.dialysis != null) {
          var dialysiss = this.cases.dialysis || ""
          var a = dialysiss.indexOf("無")
          if (a >= 0) {
            this.cases.dialysis_item = ""
            this.cases.dh_year = "不詳"
            this.cases.dh_month = "不詳"
            var items = ['有']
            var ax
            items.forEach(function(item) {
              ax = dialysiss.indexOf(item)
              if (ax !== -1) {
                dialysiss.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneN: function () {
        if (this.cases.amputation != null) {
          var amputations = this.cases.amputation || ""
          var a = amputations.indexOf("無")
          if (a >= 0) {
            this.cases.amputation_other = ""
            this.cases.amputation_right = ""
            this.cases.amputation_left = ""
            this.cases.ah_year = "不詳"
            this.cases.ah_month = "不詳"
            var items = ['有','右','左']
            var ax
            items.forEach(function(item) {
              ax = amputations.indexOf(item)
              if (ax !== -1) {
                amputations.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneM: function () {
        if (this.cases.medicaltreatment != null) {
          var medicaltreatments = this.cases.medicaltreatment || ""
          var a = medicaltreatments.indexOf("無")
          if (a >= 0) {
            this.cases.medicaltreatment_times = ""
            var items = ['有','急診','住院']
            var ax
            items.forEach(function(item) {
              ax = medicaltreatments.indexOf(item)
              if (ax !== -1) {
                medicaltreatments.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneF: function () {
        if (this.cases.followdisease != null) {
          var followdiseases = this.cases.followdisease || ""
          var a = followdiseases.indexOf("無")
          if (a >= 0) {
            var items = ['有','高血壓','心血管疾病','高血脂症','慢性肝病','癌症']
            var ax
            items.forEach(function(item) {
              ax = followdiseases.indexOf(item)
              if (ax !== -1) {
                followdiseases.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneO: function () {
        if (this.cases.drinking != null) {
          var drinkings = this.cases.drinking || ""
          var a = drinkings.indexOf("無")
          if (a >= 0) {
            this.cases.drinkingother = ""
            var items = ['有']
            var ax
            items.forEach(function(item) {
              ax = drinkings.indexOf(item)
              if (ax !== -1) {
                drinkings.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneG: function () {
        if (this.cases.ecg != null) {
          var ecgs = this.cases.ecg || ""
          var a = ecgs.indexOf("正常")
          if (a >= 0) {
            this.cases.ecgother = ""
            var items = ['異常','未檢查']
            var ax
            items.forEach(function(item) {
              ax = ecgs.indexOf(item)
              if (ax !== -1) {
                ecgs.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneT: function () {
        if (this.cases.cataract != null) {
          var cataracts = this.cases.cataract || ""
          var a = cataracts.indexOf("無")
          if (a >= 0) {
            var items = ['有','右','左','未檢查']
            var ax
            items.forEach(function(item) {
              ax = cataracts.indexOf(item)
              if (ax !== -1) {
                cataracts.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneL: function () {
        if (this.cases.foot_chk_left != null) {
          var footchklefts = this.cases.foot_chk_left || ""
          var a = footchklefts.indexOf("正常")
          if (a >= 0) {
            var items = ['震動','針刺','脈搏','繞道','未檢查']
            var ax
            items.forEach(function(item) {
              ax = footchklefts.indexOf(item)
              if (ax !== -1) {
                footchklefts.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneR: function () {
        if (this.cases.foot_chk_right != null) {
          var footchkrights = this.cases.foot_chk_right || ""
          var a = footchkrights.indexOf("正常")
          if (a >= 0) {
            var items = ['震動','針刺','脈搏','繞道','未檢查']
            var ax
            items.forEach(function(item) {
              ax = footchkrights.indexOf(item)
              if (ax !== -1) {
                footchkrights.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isIbw: function () {
        var ss, tall = 0
        if (this.cases.base_tall != null && this.cases.base_tall != "") {
          tall = this.cases.base_tall
          ss = tall / 100
          ss = (ss * ss * 22).toFixed(1)
          this.cases.ibw = ss
//          $('input[name="ibw"]').val(ss)
        } else {
//          $('input[name="ibw"]').val('')
          this.cases.ibm = ''
        }
        return (ss >= 0)
      },
      isBmi: function () {
        var ss, tall, weight = 0
        if (this.cases.base_weight == null) {
//          $('input[name="base_bmi"]').val('')
          this.cases.base_bmi = ''
        }
        if (this.cases.base_tall != null && this.cases.base_weight != null && this.cases.base_tall != "" && this.cases.base_weight != "") {
          tall = this.cases.base_tall
          tall = tall / 100
          weight = this.cases.base_weight
          ss = (weight / (tall * tall)).toFixed(1)
          this.cases.base_bmi = ss
//          $('input[name="base_bmi"]').val(ss)
        } else {
//          $('input[name="base_bmi"]').val('')
          this.cases.base_bmi = ''
        }
        return (ss >= 0)
      },
      isNoneW: function () {
        if (this.cases.blood_ap == "前") {
          this.cases.blood_mins = ""
        }
        return (this.cases.blood_ap == "前")
      },
      isNoneSm: function () {
        if (this.cases.smoking != null) {
          var smokings = this.cases.smoking || ""
          var a = smokings.indexOf("無")
          if (a >= 0) {
            this.cases.havesmoke = ""
            this.cases.quitsmoke = ""
            var items = ['有','已戒']
            var ax
            items.forEach(function(item) {
              ax = smokings.indexOf(item)
              if (ax !== -1) {
                smokings.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      }
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
