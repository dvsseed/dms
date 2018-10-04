<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>基本資料(GDM)管理</h4>
      <h5><p>
        您可以在此管理::[<b>基本資料(GDM)</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除、清除</b>...等
      </p></h5>
    </div>

    <!-- general form elements -->
    <div class="box box-primary">
      <!-- /.box-header -->
      <div class="bs-example">
        <div class="container-fluid">
        <div class="row">
          <form-group :valid.sync="valid.all">
            <!-- div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" -->
              <tabs :active.sync="active">
                <tab header="基本資料(GDM)-查詢">
                  <form-group :valid.sync="valid.query">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                      <div class="col-xs-7">
                        <bs-input name="pid" :value.sync="basiscache.pid" pattern="^[A-Z][1-2][0-9]+$" maxlength="10" :mask="mask" placeholder="請輸入...身份證" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="birthday"><span class="text-danger">*出生日期</span></label>
                      <div class="col-xs-7">
                        <bs-input name="birthday" :value.sync="basiscache.birthday" pattern="^[0-9]+$" maxlength="7" placeholder="請輸入...生日(民國 3位數, 例: 0501231)" required></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                  </form-group>
                  <div class="col-xs-12 col-xs-offset-3">
                    <button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createBasisgdm()"><i class="fa fa-plus-circle"></i>新增</button>
                    <button type="button" class="btn btn-info" :disabled="!valid.query" @click="clear2(basiscache)"><i class="fa fa-eraser"></i>清除</button>
                  </div>
                </tab>
                <tab header="基本資料(GDM)-新增" :disabled="!disablequery">
                  <form-group :valid.sync="valid.create">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="log_date">登錄日期</label>
                      <div class="col-xs-7">
                        <!-- pre>Selected date is: {{-- new Date($refs.dp.parse()).toString() --}}</pre -->
                        <datepicker v-ref:dp :value.sync="basisgdm.log_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="diagnose">診斷時間</label>
                      <label class="control-label col-xs-1" for="diagnoseyear">西元年</label>
                      <div class="col-xs-3">
                        <multiselect :options="yearoptions" :selected.sync="basisgdm.fall_ill_year" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateYear">  </multiselect>
                        <!-- v-select name="fall_ill_year" :options="yearoptions" :value.sync="basisgdm.fall_ill_year" clear-button></v-select -->
                      </div>
                      <label class="control-label col-xs-1" for="diagnosemonth">月份</label>
                      <div class="col-xs-2">
                        <multiselect :options="monthoptions" :selected.sync="basisgdm.fall_ill_month" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateMonth">  </multiselect>
                        <!-- v-select name="fall_ill_month" :options="['01','02','03','04','05','06','07','08','09','10','11','12','不詳']" :value.sync="basisgdm.fall_ill_month" clear-button></v-select -->
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="currentweeks">目前週數</label>
                      <div class="col-xs-5">
                        <multiselect :options="currentweeksoptions" :selected.sync="basisgdm.currentweeks" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateCurrentweeks">  </multiselect>
                      </div>
                      <div class="col-xs-2 col-xs-offset-2"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="maternitynumber">孕產次數</label>
                      <div class="col-xs-5">
                        <multiselect :options="maternitynumberoptions" :selected.sync="basisgdm.maternitynumber" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateMaternitynumber">  </multiselect>
                      </div>
                      <div class="col-xs-2 col-xs-offset-2"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="gestationaldiabetes">妊娠糖尿病發生次數</label>
                      <div class="col-xs-5">
                        <multiselect :options="gestationaldiabetesoptions" :selected.sync="basisgdm.gestationaldiabetes" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateGestationaldiabetes">  </multiselect>
                      </div>
                      <div class="col-xs-2 col-xs-offset-2"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="symptom">發病症狀</label>
                      <div class="col-xs-7">
                        <p><pre>Values: {{ basisgdm.symptom }}</pre></p>
                        <button-group :value.sync="basisgdm.symptom" buttons="false">
                          <v-checkbox value="無症狀">無症狀</v-checkbox>
                          <v-checkbox value="三多(任一症狀)" type="warning" :disabled="isNone">三多(任一症狀)</v-checkbox>
                          <v-checkbox value="體重減輕" type="warning" :disabled="isNone">體重減輕</v-checkbox>
                          <v-checkbox value="HHS" type="warning" :disabled="isNone">HHS</v-checkbox>
                          <v-checkbox value="DKA" type="warning" :disabled="isNone">DKA</v-checkbox>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="treatment">妊娠糖尿病過去治療方式</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basisgdm.treatment" buttons="false">
                          <p><pre>Values: {{ basisgdm.treatment }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="口服藥" type="warning" :disabled="isNoneT">口服藥</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <div class="row">
                                <div class="col-xs-12">
                                  <multiselect :options="['Metformin','SU','Glinide','DPP4-I','SGLT-2','TZD','Acarbose']" :selected.sync="basisgdm.medication"
                                               :multiple="true" :searchable="false" :close-on-select="false" :show-labels="false" @update="updateMedication"
                                               :disabled="isNoneT">  </multiselect>
                                </div>
                              </div>
                              <div class="row">
                                <label class="control-label col-xs-2" for="medicationyear">西元年</label>
                                <div class="col-xs-4">
                                  <multiselect :options="yearoptions" :selected.sync="basisgdm.medication_year" :multiple="false" :searchable="true" :close-on-select="false"
                                               :show-labels="false" @update="updatemYear" :disabled="isNoneT">  </multiselect>
                                </div>
                                <label class="control-label col-xs-2" for="medicationmonth">月份</label>
                                <div class="col-xs-4">
                                  <multiselect :options="monthoptions" :selected.sync="basisgdm.medication_month" :multiple="false" :searchable="true" :close-on-select="false"
                                               :show-labels="false" @update="updatemMonth" :disabled="isNoneT">  </multiselect>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- div class="row" -->
                            <div class="row">
                              <div class="col-xs-2">
                                <v-checkbox value="胰島素" type="warning" :disabled="isNoneT">胰島素</v-checkbox>
                              </div>
                              <div class="col-xs-10">
                                <label class="control-label col-xs-2" for="injectionyear">西元年</label>
                                <div class="col-xs-3">
                                  <multiselect :options="yearoptions" :selected.sync="basisgdm.injection_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatenYear" :disabled="isNoneT">  </multiselect>
                                </div>
                                <label class="control-label col-xs-2" for="injectionmonth">月份</label>
                                <div class="col-xs-3">
                                  <multiselect :options="monthoptions" :selected.sync="basisgdm.injection_month" :multiple="false" :searchable="true" :close-on-select="false"
                                               :show-labels="false" @update="updatenMonth" :disabled="isNoneT">  </multiselect>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xs-6 col-xs-offset-4">
                                <bs-input name="insulin" :value.sync="basisgdm.insulin" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="insulin">天次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2 col-xs-offset-3" for="longterm">長效</label>
                              <div class="col-xs-5">
                                <bs-input name="longterm" :value.sync="basisgdm.longterm" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="longterm">單位/天</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2 col-xs-offset-3" for="ineffect">中效</label>
                              <div class="col-xs-5">
                                <bs-input name="ineffect" :value.sync="basisgdm.ineffect" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="ineffect">單位/天</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2 col-xs-offset-3" for="shortacting">短效</label>
                              <div class="col-xs-5">
                                <bs-input name="shortacting" :value.sync="basisgdm.shortacting" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="shortacting">單位/天</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2 col-xs-offset-3" for="quickeffect">速效</label>
                              <div class="col-xs-5">
                                <bs-input name="quickeffect" :value.sync="basisgdm.quickeffect" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="quickeffect">單位/天</label>
                            </div>
                          <!-- /div -->
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="GLP-1" type="warning" :disabled="isNoneT">GLP-1</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <label class="control-label col-xs-2" for="injectionyear">西元年</label>
                              <div class="col-xs-3">
                                <multiselect :options="yearoptions" :selected.sync="basisgdm.glp1_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateiYear" :disabled="isNoneT">  </multiselect>
                              </div>
                              <label class="control-label col-xs-1" for="injectionmonth">月份</label>
                              <div class="col-xs-3">
                                <multiselect :options="monthoptions" :selected.sync="basisgdm.glp1_month" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateiMonth" :disabled="isNoneT">  </multiselect>
                              </div>
                              <div class="col-xs-3">
                                <v-select name="glp1" :options="['無','2次/天','1次/天','1次/周','1次/月']" :value.sync="basisgdm.glp1" clear-button :disabled="isNoneT"></v-select>
                              </div>
                            </div>
                          </div>
                          <v-checkbox value="飲食控制" type="warning" :disabled="isNoneT">飲食控制</v-checkbox>
                          <v-checkbox value="中藥" type="warning" :disabled="isNoneT">中藥</v-checkbox>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="comorbidity">共病</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basisgdm.comorbidity" buttons="false">
                          <p><pre>Values: {{ basisgdm.comorbidity }}</pre></p>
                          <v-checkbox value="無共病">無共病</v-checkbox>
                          <v-checkbox value="高血壓" type="warning" :disabled="isNoneC">高血壓</v-checkbox>
                          <v-checkbox value="高血脂" type="warning" :disabled="isNoneC">高血脂</v-checkbox>
                          <v-checkbox value="高尿酸" type="warning" :disabled="isNoneC">高尿酸</v-checkbox>
                          <v-checkbox value="心臟病" type="warning" :disabled="isNoneC">心臟病</v-checkbox>
                          <v-checkbox value="腎臟病" type="warning" :disabled="isNoneC">腎臟病</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="肺臟病" type="warning" :disabled="isNoneC">肺臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['COPD','開放型','非開放性']" :selected.sync="basisgdm.lung"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateLung"
                                           :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="肝臟病" type="warning" :disabled="isNoneC">肝臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['A型肝炎','B型肝炎','C型肝炎','脂肪肝','肝硬化']" :selected.sync="basisgdm.liver"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateLiver"
                                           :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <v-checkbox value="甲狀腺疾病" type="warning" :disabled="isNoneC">甲狀腺疾病</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="精神疾病" type="warning" :disabled="isNoneC">精神疾病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['失眠','焦慮','憂鬱','躁鬱','精神功能症','失智','其他']" :selected.sync="basisgdm.mental"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateMental"
                                           :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="癌症" type="warning" :disabled="isNoneC">癌症</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <bs-input name="cancer" :value.sync="basisgdm.cancer" :disabled="isNoneC"></bs-input>
                            </div>
                          </div>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div v-if="isFemale">
                      <div class="row">
                        <label class="control-label col-xs-2 col-xs-offset-1" for="gestation">妊娠糖尿病發生次數</label>
                        <div class="col-xs-7">
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basisgdm.gestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateGestation"
                                       v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                      <div class="row">
                        <label class="control-label col-xs-2 col-xs-offset-1" for="stopgestation">因糖尿病終止妊娠</label>
                        <div class="col-xs-7">
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basisgdm.stopgestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateStopgestation"
                                       v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="fami_medic_his">家族史</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basisgdm.fami_medic_his" buttons="false">
                          <p><pre>Values: {{ basisgdm.fami_medic_his }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <v-checkbox value="不詳">不詳</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="糖尿病" type="warning" :disabled="isNoneF">糖尿病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisgdm.relatives"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateRelatives"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="高血壓" type="warning" :disabled="isNoneF">高血壓</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisgdm.hypertension"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateHypertension"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="心臟病" type="warning" :disabled="isNoneF">心臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisgdm.cardiovascular"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateCardiovascular"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="中風" type="warning" :disabled="isNoneF">中風</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisgdm.stroke"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateStroke"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="activity">活動量</label>
                      <div class="col-xs-7">
                        <multiselect :options="['無','輕','中','重','臥床']" :selected.sync="basisgdm.activity"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateActivity">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="education">教育程度</label>
                      <div class="col-xs-7">
                        <multiselect :options="['不詳','不識字','識數字','識字','日教','國小','國中','高中','大專','大學','碩士','博士']" :selected.sync="basisgdm.education"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateEducation">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="occupation">職業內容</label>
                      <div class="col-xs-7">
                        <bs-input name="occupation" :value.sync="basisgdm.occupation" placeholder="若無，請忽略此欄；若有，請填寫!"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="worktime">工作時間</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisgdm.worktime | json }}</pre></p>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.worktime" value="固定" type="info">固定</radio>
                          </div>
                          <div class="col-xs-10">
                            <!-- p><pre>Value: {{-- basisgdm.fixedtime_from | json }}</pre></p -->
                            <vue-timepicker :time-value.sync="basisgdm.fixedtime_from" format="HH:mm"></vue-timepicker>～
                            <vue-timepicker :time-value.sync="basisgdm.fixedtime_to" format="HH:mm"></vue-timepicker>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.worktime" value="不固定" type="danger">不固定</radio>
                          </div>
                          <div class="col-xs-10">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.worktime" value="輪班" type="warning">輪班</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="dayshift">日班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisgdm.dayshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisgdm.dayshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="middleshift">中班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisgdm.middleshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisgdm.middleshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="nightshift">晚班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisgdm.nightshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisgdm.nightshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="affectlearning">影響學習之因素</label>
                      <div class="col-xs-7">
                        <multiselect :options="['聽力障礙','視力障礙','手部不靈活','失聰','失明','智力障礙','情緒因素','疾病因素','其他']" :selected.sync="basisgdm.affectlearning"
                                     :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateAffectlearning">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="sport">運動次數</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisgdm.sport | json }}</pre></p>
                        <radio :checked.sync="basisgdm.sport" value="無" type="info">無</radio>
                        <radio :checked.sync="basisgdm.sport" value="不規律" type="info">不規律</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.sport" value="規律" type="danger">規律</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="sporta">A種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta1" :value.sync="basisgdm.sporta1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true" lazy></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta2" :value.sync="basisgdm.sporta2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportb">B種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb1" :value.sync="basisgdm.sportb1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb2" :value.sync="basisgdm.sportb2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportc">C種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc1" :value.sync="basisgdm.sportc1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc2" :value.sync="basisgdm.sportc2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">分鐘/次</label>
                            </div>
                            <div class="row">
                              <div class="col-xs-2 col-xs-offset-1">
                                <!-- button type="button" class="btn btn-danger" @click="calcSport">計算加總</button -->
                              </div>
                              <div class="col-xs-5 text-right">
                                <bs-input name="sportsum" :value.sync="basisgdm.sportsum" pattern="^[0-9]+$" maxlength="5" placeholder="自動計算加總" help="此欄位係自動累加上述數值!" :disabled="isSum" icon></bs-input>
                              </div>
                              <label class="control-label col-xs-4" for="sportsum">分鐘/周</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="glucometer">是否使用血糖機</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisgdm.glucometer | json }}</pre></p>
                        <radio :checked.sync="basisgdm.glucometer" value="無" type="info">無</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.glucometer" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-6">
                            <bs-input name="glucometerfrequency" :value.sync="basisgdm.glucometerfrequency" placeholder="請填寫!"></bs-input>
                          </div>
                          <label class="control-label col-xs-4" for="glucometerfrequency">次/月</label>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="g6pd">G6PD</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisgdm.g6pd | json }}</pre></p>
                        <radio :checked.sync="basisgdm.g6pd" value="無" type="info">無</radio>
                        <radio :checked.sync="basisgdm.g6pd" value="不詳" type="info">不詳</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.g6pd" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-10">
                            <label class="control-label col-xs-4" for="g6pdyear">診斷日期: 西元年</label>
                            <div class="col-xs-3">
                              <multiselect :options="yearoptions" :selected.sync="basisgdm.g6pd_year" :multiple="false" :searchable="true" :close-on-select="false"
                                           :show-labels="false" @update="updategYear">  </multiselect>
                            </div>
                            <label class="control-label col-xs-2" for="g6pdmonth">月份</label>
                            <div class="col-xs-3">
                              <multiselect :options="monthoptions" :selected.sync="basisgdm.g6pd_month" :multiple="false" :searchable="true" :close-on-select="false"
                                           :show-labels="false" @update="updategMonth">  </multiselect>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="closed">結案</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisgdm.closed | json }}</pre></p>
                        <radio :checked.sync="basisgdm.closed" value="無" type="info">無</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.closed" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-5">
                            <datepicker v-ref:dp :value.sync="basisgdm.close_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="300px"></datepicker>
                          </div>
                          <div class="col-xs-1"> &nbsp;</div>
                          <div class="col-xs-4">
                            <multiselect :options="['Select option','正常','IFG/IGT','T2DM']" :selected.sync="basisgdm.closed_do" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateCloseddo">  </multiselect>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisgdm.closed" value="歿" type="warning">歿</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedyear">西元年</label>
                              <div class="col-xs-3">
                                <multiselect :options="yearoptions" :selected.sync="basisgdm.closed_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecYear">  </multiselect>
                              </div>
                              <label class="control-label col-xs-3" for="closedmonth">月份</label>
                              <div class="col-xs-3">
                                <multiselect :options="monthoptions" :selected.sync="basisgdm.closed_month" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecMonth">  </multiselect>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedcause">原因</label>
                              <div class="col-xs-3">
                                <multiselect :options="['Select option','意外','癌症','疾病','不明原因']" :selected.sync="basisgdm.closedcause" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateCause">  </multiselect>
                              </div>
                              <div class="col-xs-6">
                                <bs-input name="closedreason" :value.sync="basisgdm.closedreason" placeholder="請填寫!"></bs-input>
                              </div>
                            </div>
                          </div>
                        </div>
                        <radio :checked.sync="basisgdm.closed" value="其它診斷" type="success">其它診斷</radio>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <br /><br />
                  </form-group>
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-warning" :disabled="!valid.all" @click="updateBasisgdm(basisgdm)"><i class="fa fa-pencil-square-o"></i>修改</button>
                    <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteBasisgdm(basisgdm)"><i class="fa fa-minus-circle"></i>刪除</button>
                  </div>
                </tab>
              </tabs>
            <!-- /div -->
          </form-group>
        </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /.box -->
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  //  import { buttonGroup, bsInput, tab, tabs, vSelect, vCheckbox, vOption, datepicker, radio } from 'vue-strap/dist/vue-strap.min'
  import formGroup from './vue-strap/src/FormGroup.vue'
  import buttonGroup from './vue-strap/src/buttonGroup.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import tab from './vue-strap/src/Tab.vue'
  import tabs from './vue-strap/src/Tabset.vue'
  import vSelect from './vue-strap/src/Select.vue'
  import vCheckbox from './vue-strap/src/Checkbox.vue'
  import vOption from './vue-strap/src/Option.vue'
  import datepicker from './vue-strap/src/Datepicker.vue'
  import radio from './vue-strap/src/Radio.vue'
  import VueTimepicker from './vue-timepicker.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

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
      datepicker,
      Multiselect,
      VueTimepicker,
      radio
    },
    created () {
      this.fetchToday()
      this.fetchYear()
      this.fetchMonth()
      this.fetchWeight()
      this.fetchCurrentweeks()
      this.fetchMaternitynumber()
      this.fetchGestationaldiabetes()
    },
    ready () {
      this.fetchUser()
      // this.fetchCache()
    },
    data () {
      return {
        token: csrf_token,
        disabled: [],
        format: ['yyyy-MM-dd'],
        clear: false,
        active: 0,
        disablequery: 0,
        valid: {},
        userowner: {},
        basiscache: {},
        basisgdm: {},
        cases: {},
        yearoptions: [],
        monthoptions: [],
        weightoptions: [],
        currentweeksoptions: [],
        maternitynumberoptions: [],
        gestationaldiabetesoptions: [],
        today: ''
      }
    },
    methods: {
      mask (value) {
        return value.toUpperCase()
      },
      fetchUser () {
        //登入者
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            this.$set('userowner', response.data)
          })
          .catch(function(response) {
            // console.log(response)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })
      },
      fetchPid (pid) {
        this.$http({url: '/api/basis/showpid1/' + pid, method: 'GET'})
          .then(function (response) {
            this.$set('basis', response.data)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      fetchCache () {
        this.$http({url: '/api/basiscache/', method: 'GET'})
          .then(function (response) {
  //          this.$set('basiscache', response.data)
  //          console.log(response.data)
            this.$set('basiscache.pid', response.data.pid)
            this.$set('basiscache.birthday', response.data.birthday)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      fetchToday () {
        //today
        var date = new Date()
        var thisday = ( date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate())).slice(-2) )
        this.today = thisday
        this.$set('basisgdm.log_date', thisday)
        this.$set('basisgdm.close_date', thisday)
//        this.$set('basisgdm.medicationdate', today)
//        this.$set('basisgdm.injectiondate', today)
//        console.log(this.today)
//        console.log(this.basisgdm.close_date)
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
        var months = ['不詳']
        // range = from 01 ~ 12
        // months.push('不詳')
        for (var m = 1; m <= 12; m++) {
          ms = ('0' + m).substr(-2)
          months.push(ms)
        }
        this.$set('monthoptions', months)
      },
      fetchWeight () {
        var weights = []
        // range = from 1 ~ 30
        for (var w = 1; w <= 30; w++) {
          weights.push(w)
        }
        weights.push('不詳')
        this.$set('weightoptions', weights)
      },
      fetchCurrentweeks () {
        var currentweeks = ['不詳']
        // currentweeks = from 1 ~ 40
        for (var c = 1; c <= 40; c++) {
          currentweeks.push(c)
        }
        this.$set('currentweeksoptions', currentweeks)
      },
      fetchMaternitynumber () {
        var maternitynumber = ['不詳']
        // maternitynumber = from 1 ~ 10
        for (var m = 1; m <= 10; m++) {
          maternitynumber.push(m)
        }
        this.$set('maternitynumberoptions', maternitynumber)
      },
      fetchGestationaldiabetes () {
        var gestationaldiabetes = ['不詳']
        // gestationaldiabetes = from 1 ~ 10
        for (var g = 1; g <= 10; g++) {
          gestationaldiabetes.push(g)
        }
        this.$set('gestationaldiabetesoptions', gestationaldiabetes)
      },
      deleteBasisgdm (basisgdm) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名使用者的基本資料(GDM)!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.basisgdm.$remove(basisgdm)
          self.$http.delete('/api/basisgdm/' + basisgdm.id, basisgdm).then(function (response) {
            self.$router.go('/basisgdm')
            swal(
              '已刪除!!',
              '該名使用者的基本資料(GDM)已經刪除!!',
              'success'
            )
            this.active = 0
            this.disablequery = 0
          }, function (response){
            show_stack_error('刪除使用者的基本資料(GDM)發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名使用者的基本資料(GDM)維持現狀 :)',
              'error'
            )
          }
        })
      },
      createBasisgdm () {
        // check if login is valid
        this.$http({url: '/api/me', method: 'GET'})
          .then(function (response) {
            // this.$set('userowner', response.data)
            // console.log(response.data)
          })
          .catch(function(response) {
            // console.log(response.data)
            if (response.data == "Unauthorized." && response.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
              window.location.assign('/auth/logout')
            }
          })

        if (confirm("確定新增嗎?!")) {
          //身份證檢覈
          var chkpid = $('input[name="pid"]').val()
          if(!this.checkTWID(chkpid)) {
            this.valid.query = false
            return false
          }
          //出生日期檢覈
          if(!this.checkBirthday()) {
            this.valid.query = false
            return false
          }
          this.active = 0
          this.disablequery = 0
          var input = this.basiscache
          // console.log(input.pid)
          this.$http.post('/api/basisgdm', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              // var pid = $('input[name="pid"]').val()
              // alert(pid)
              this.$set('basisgdm', response.data)
              //結案日期
              if(this.basisgdm.closed != "有") {
                this.$set('basisgdm.close_date', this.today)
              }
              // console.log(response.data.message)
              // console.log(this.basisgdm.fixedtime_from)
  //            this.$http({url: '/api/basisgdm/showpid/' + pid, method: 'GET'})
  //              .then(function (response) {
  //                this.$set('basisgdm', response.data)
  //              })
  //              .catch(function(response) {
  //                console.log(response)
  //              })
              this.active = 1
              this.disablequery = 1
            }, function (response) {
              // console.log(response.data.error.message)
              if (response.data.error.message == "The pid has already been taken.") {
                show_stack_info('該基本資料(GDM)已新增, 可修改!!', response)

                var pid = $('input[name="pid"]').val()
                this.$http({url: '/api/basisgdm/showpid1/' + pid, method: 'GET'})
                  .then(function (response) {
                    this.$set('basisgdm', response.data)
                    //結案日期
                    if(this.basisgdm.closed != "有") {
                      this.$set('basisgdm.close_date', this.today)
                    }
                    // for debug...
                    // console.log(response.data.message)
                    // console.log(this.basisgdm.fixedtime_from)
                    // JSON.parse(myJSONString)
                  })
                  .catch(function(response) {
                    // console.log(response)
                  })

                this.active = 1
                this.disablequery = 1
              } else {
                show_stack_error('新增資料(GDM)失敗!!', response)
              }
            })
            .catch(function(response) {
              // console.log(response)
            })
        }
      },
      updateBasisgdm (basisgdm) {
  //      event.preventDefault()
//        alert(basisgdm.closed)
        this.$http.patch('/api/basisgdm/' + basisgdm.id, basisgdm)
          .then(function (response) {
            show_stack_success('使用者的基本資料(GDM)已更新!', response)
  //          alert(JSON.stringify(response))
  //          console.log(response.data.message)
          }, function (response) {
            show_stack_error('使用者的基本資料(GDM)更新失敗!', response)
  //          alert(response.data.error.message)
          })
          .catch(function(response) {
            // console.log(response)
          })
        this.$router.go('/')
      },
      clear2 (basiscache) {
        //刪除暫存
        this.$http.delete('/api/basiscache/1', basiscache)
          .then(function (response) {
            show_stack_success('使用者的輸入資料(暫存)已刪除!', response)
            $('input[name="pid"]').val('')
            $('input[name="birthday"]').val('')
          }, function (response) {
            show_stack_error('使用者的輸入資料(暫存)刪除失敗!', response)
          })
          .catch(function(response) {
            // console.log(response)
          })
      },
      checkTWID (id) {
        // 台灣身份證檢查
        // 建立字母分數陣列(A~Z)
        var city = new Array(
          1,10,19,28,37,46,55,64,39,73,82,2,11,
          20,48,29,38,47,56,65,74,83,21,3,12,30
        )
        id = id.toUpperCase()
        // 使用「正規表達式」檢驗格式
        // if (id.search(/^[A-Z](1|2)\d{8}$/i) == -1) {
        // 身份證的正規表示式
        var reg = /^[A-Z]{1}[1-2]{1}[0-9]{8}$/
        if(reg.test(id)) {
          // 將字串分割為陣列(IE必需這麼做才不會出錯)
          id = id.split('')
          // 計算總分
          var total = city[id[0].charCodeAt(0) - 65]
          for(var i = 1; i <= 8; i++) {
            total += eval(id[i]) * (9 - i)
          }
          // 補上檢查碼(最後一碼)
          total += eval(id[9])
          // 檢查比對碼(餘數應為0)
          if (total % 10 == 0) {
            return true
          } else {
            alert('*  身份證號驗證計算錯誤！')
            return false
          }
        } else {
          alert('*  身份證號基本格式錯誤！')
          return false
        }
      },
      checkBirthday () {
        //檢查出生日期格式是否正確
        var errmsg = ""
        var date = $('input[name="birthday"]').val()
        date = date.replace(/ /g, "")
        date = date.replace(/　/g, "")
        if (date.length < 7)
          errmsg = errmsg + "*  出生日期長度不符\n"
        // returns the current year
        var thisyear = new Date().getFullYear()
        thisyear = thisyear - 1911
        var TodayDate = new Date()
        var thismonth = TodayDate.getMonth() + 1
        if (date.substr(0, 3) > thisyear)
          errmsg = errmsg + "*  出生日期: 年份不可為" + date.substr(0, 3) + "，今年為民國" + thisyear + "年\n"
        if (date.substr(0, 3) == thisyear && date.substr(3, 2) > thismonth)
          errmsg = errmsg + "*  出生日期: 年份為" + date.substr(0, 3) + "，月份不可大於" + date.substr(3, 2) + "\n"
        if (date.substr(3, 2) > '12')
          errmsg = errmsg + "*  出生日期: 月份不可為" + date.substr(3, 2) + "\n"
        if (date.substr(5, 2) > '31')
          errmsg = errmsg + "*  出生日期: 日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) > '30' && (date.substr(3, 2) != '01' && date.substr(3, 2) != '03' && date.substr(3, 2) != '05' && date.substr(3, 2) != '07' && date.substr(3, 2) != '08' && date.substr(3, 2) != '10' && date.substr(3, 2) != '12'))
          errmsg = errmsg + "*  出生日期: 小月日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) > '29' && date.substr(3, 2) == '02')
          errmsg = errmsg + "*  出生日期: 2月份日期不可為" + date.substr(5, 2) + "\n"
        else if (date.substr(5, 2) == '29' && date.substr(3, 2) == '02' && ((parseInt(date.substr(0, 3), 10) + 1911) % 4) != 0)
          errmsg = errmsg + "*  出生日期: 非閏年，日期不可為" + date.substr(5, 2) + "\n"
        if (errmsg != "") {
          errmsg = errmsg + "*  以上欄位錯誤！"
          alert(errmsg)
          return false
        } else {
          return true
        }
      },
      calcSport () {
        var sporta1 = $('input[name="sporta1"]').val()
        var sporta2 = $('input[name="sporta2"]').val()
        var sportb1 = $('input[name="sportb1"]').val()
        var sportb2 = $('input[name="sportb2"]').val()
        var sportc1 = $('input[name="sportc1"]').val()
        var sportc2 = $('input[name="sportc2"]').val()
        var aa, bb, cc
        if (typeof sporta1  !== 'undefined' && typeof sporta2 !== 'undefined') {
          aa = sporta1 * sporta2
        }
        if (typeof sportb1 !== 'undefined' && typeof sportb2 !== 'undefined') {
          bb = sportb1 * sportb2
        }
        if (typeof sportc1 !== 'undefined' && typeof sportc2 !== 'undefined') {
          cc = sportc1 * sportc2
        }
        if (typeof aa !== 'undefined' && typeof bb !== 'undefined' && typeof cc !== 'undefined') {
          $('input[name="sportsum"]').val(aa + bb + cc)
        }
      },
      updateYear (value) {
        this.basisgdm.fall_ill_year = value
      },
      updateMonth (value) {
        this.basisgdm.fall_ill_month = value
      },
      updatemYear (value) {
        this.basisgdm.medication_year = value
      },
      updatemMonth (value) {
        this.basisgdm.medication_month = value
      },
      updatecYear (value) {
        this.basisgdm.closed_year = value
      },
      updatecMonth (value) {
        this.basisgdm.closed_month = value
      },
      updategYear (value) {
        this.basisgdm.g6pd_year = value
      },
      updategMonth (value) {
        this.basisgdm.g6pd_month = value
      },
      updateiYear (value) {
        this.basisgdm.glp1_year = value
      },
      updateiMonth (value) {
        this.basisgdm.glp1_month = value
      },
      updatenYear (value) {
        this.basisgdm.injection_year = value
      },
      updatenMonth (value) {
        this.basisgdm.injection_month = value
      },
      updateMedication (value) {
        this.basisgdm.medication = value
      },
      updateLung (value) {
        this.basisgdm.lung = value
      },
      updateLiver (value) {
        this.basisgdm.liver = value
      },
      updateMental (value) {
        this.basisgdm.mental = value
      },
      updateGestation (value) {
        this.basisgdm.gestation = value
      },
      updateStopgestation (value) {
        this.basisgdm.stopgestation = value
      },
      updateRelatives (value) {
        this.basisgdm.relatives = value
      },
      updateHypertension (value) {
        this.basisgdm.hypertension = value
      },
      updateCardiovascular (value) {
        this.basisgdm.cardiovascular = value
      },
      updateStroke (value) {
        this.basisgdm.stroke = value
      },
      updateActivity (value) {
        this.basisgdm.activity = value
      },
      updateEducation (value) {
        this.basisgdm.education = value
      },
      updateAffectlearning (value) {
        this.basisgdm.affectlearning = value
      },
      updateCause (value) {
        this.basisgdm.closedcause = value
      },
      updateCurrentweeks (value) {
        this.basisgdm.currentweeks = value
      },
      updateMaternitynumber (value) {
        this.basisgdm.maternitynumber = value
      },
      updateGestationaldiabetes (value) {
        this.basisgdm.gestationaldiabetes = value
      },
      updateCloseddo (value) {
        this.basisgdm.closed_do = value
      }
    },
    computed: {
      isSum: function () {
        var ss = 0
        if (this.basisgdm.sporta1 != null && this.basisgdm.sporta2 != null) {
          ss = this.basisgdm.sporta1 * this.basisgdm.sporta2
        }
        if (this.basisgdm.sportb1 != null && this.basisgdm.sportb2 != null) {
          ss = ss + this.basisgdm.sportb1 * this.basisgdm.sportb2
        }
        if (this.basisgdm.sportc1 != null && this.basisgdm.sportc2 != null) {
          ss = ss + this.basisgdm.sportc1 * this.basisgdm.sportc2
        }
        $('input[name="sportsum"]').val(ss)
        return (ss >= 0)
      },
      isNone: function () {
        if (this.basisgdm.symptom != null) {
          var symptoms = this.basisgdm.symptom || ""
          var a = symptoms.indexOf("無症狀")
          if (a >= 0) {
            var items = ['三多(任一症狀)', '體重減輕', 'HHS', 'DKA']
            var ax
            items.forEach(function(item) {
              ax = symptoms.indexOf(item)
              if (ax !== -1) {
                symptoms.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
  //    isHas: function () {
  //      if (this.basisgdm.symptom != null) {
  //        var symptoms = this.basisgdm.symptom || ""
  //        var a = symptoms.indexOf("有")
  //        if (a >= 0) {
  //          var ax
  //          if ((ax = symptoms.indexOf("無")) !== -1) {
  //            symptoms.splice(ax, 1)
  //          }
  //        }
  //        return (a >= 0)
  //      }
  //    },
      isNoneT: function () {
        if (this.basisgdm.treatment != null) {
          var treatments = this.basisgdm.treatment || ""
          var a = treatments.indexOf("無")
          if (a >= 0) {
            this.basisgdm.insulin = ""
            this.basisgdm.longterm = ""
            this.basisgdm.ineffect = ""
            this.basisgdm.shortacting = ""
            this.basisgdm.quickeffect = ""
            var items = ['口服藥','胰島素','GLP-1','飲食控制','中藥']
            var ax
            items.forEach(function(item) {
              ax = treatments.indexOf(item)
              if (ax !== -1) {
                treatments.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneC: function () {
        if (this.basisgdm.comorbidity != null) {
          var comorbiditys = this.basisgdm.comorbidity || ""
          var a = comorbiditys.indexOf("無共病")
          if (a >= 0) {
            this.basisgdm.cancer = ""
            var items = ['高血壓','高血脂','高尿酸','心臟病','腎臟病','肺臟病','肝臟病','甲狀腺疾病','精神疾病','癌症']
            var ax
            items.forEach(function(item) {
              ax = comorbiditys.indexOf(item)
              if (ax !== -1) {
                comorbiditys.splice(ax, 1)
              }
            })
          }
          return (a >= 0)
        }
      },
      isNoneN: function () {
        if (this.basisgdm.complication != null) {
          var complications = this.basisgdm.complication || ""
          var a = complications.indexOf("無")
          var b = complications.indexOf("不詳")
          if (a >= 0 || b >= 0) {
            var items = ['視網膜病變','糖尿病腎病變','糖尿病神經病變','冠心病','腦中風','失明','洗腎','截肢']
            var ax
            items.forEach(function(item) {
              ax = complications.indexOf(item)
              if (ax !== -1) {
                complications.splice(ax, 1)
              }
            })
          }
          return (a >= 0 || b >= 0)
        }
      },
      isNoneF: function () {
        if (this.basisgdm.fami_medic_his != null) {
          var fami_medic_hiss = this.basisgdm.fami_medic_his || ""
          var a = fami_medic_hiss.indexOf("無")
          var b = fami_medic_hiss.indexOf("不詳")
          if (a >= 0 || b >= 0) {
            var items = ['糖尿病','高血壓','心臟病','中風']
            var ax
            items.forEach(function(item) {
              ax = fami_medic_hiss.indexOf(item)
              if (ax !== -1) {
                fami_medic_hiss.splice(ax, 1)
              }
            })
          }
          return (a >= 0 || b >= 0)
        }
      },
      isFemale: function () {
        var bid = this.basisgdm.pid
        if (bid) {
          return (bid.substr(1, 1) == 2)
        }
        return false
      }
    },
    watch: {
      disabled () {
        this.$refs.dp.getDateRange()
      },
      format (newV) {
        this.value = this.$refs.dp.stringify()
      },
  //    sporta1: function (val, oldVal) {
  //      console.log('new: %s, old: %s', val, oldVal)
  //    }
    }
  }
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
  .text-right {
    text-align: right !important;
  }
</style>