<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>基本資料(IGT/IFG)管理</h4>
      <h5><p>
        您可以在此管理::[<b>基本資料(IGT/IFG)</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除、清除</b>...等
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
                <tab header="基本資料(IGT/IFG)-查詢">
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
                    <button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createBasisigtifg()"><i class="fa fa-plus-circle"></i>新增</button>
                    <button type="button" class="btn btn-info" :disabled="!valid.query" @click="clear2(basiscache)"><i class="fa fa-eraser"></i>清除</button>
                  </div>
                </tab>
                <tab header="基本資料(IGT/IFG)-新增" :disabled="!disablequery">
                  <form-group :valid.sync="valid.create">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="log_date">登錄日期</label>
                      <div class="col-xs-7">
                        <!-- pre>Selected date is: {{-- new Date($refs.dp.parse()).toString() --}}</pre -->
                        <datepicker v-ref:dp :value.sync="basisigtifg.log_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="diagnose">診斷時間</label>
                      <label class="control-label col-xs-1" for="diagnoseyear">西元年</label>
                      <div class="col-xs-3">
                        <multiselect :options="yearoptions" :selected.sync="basisigtifg.fall_ill_year" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateYear" label="fall_ill_year">  </multiselect>
                        <!-- v-select name="fall_ill_year" :options="yearoptions" :value.sync="basisigtifg.fall_ill_year" clear-button></v-select -->
                      </div>
                      <label class="control-label col-xs-1" for="diagnosemonth">月份</label>
                      <div class="col-xs-2">
                        <multiselect :options="monthoptions" :selected.sync="basisigtifg.fall_ill_month" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateMonth" label="fall_ill_month">  </multiselect>
                        <!-- v-select name="fall_ill_month" :options="['01','02','03','04','05','06','07','08','09','10','11','12','不詳']" :value.sync="basisigtifg.fall_ill_month" clear-button></v-select -->
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="fall_ill_ii">&nbsp;</label>
                      <div class="col-xs-4">
                        <multiselect :options="['無','IGT','IFG']" :selected.sync="basisigtifg.fall_ill_ii" :multiple="true" :searchable="true" :close-on-select="false" :clear-on-select="false"
                                     :hide-selected="true" placeholder="可複選" :show-labels="false" @update="updateII" label="fall_ill_ii">  </multiselect>
                      </div>
                      <div class="col-xs-4 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="comorbidity">共病</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basisigtifg.comorbidity" buttons="false">
                          <p><pre>Values: {{ basisigtifg.comorbidity }}</pre></p>
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
                              <multiselect :options="['COPD','開放型','非開放性']" :selected.sync="basisigtifg.lung"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateLung"
                                           label="lung" :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="肝臟病" type="warning" :disabled="isNoneC">肝臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['A型肝炎','B型肝炎','C型肝炎','脂肪肝','肝硬化']" :selected.sync="basisigtifg.liver"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateLiver"
                                           label="liver" :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <v-checkbox value="甲狀腺疾病" type="warning" :disabled="isNoneC">甲狀腺疾病</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="精神疾病" type="warning" :disabled="isNoneC">精神疾病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['失眠','焦慮','憂鬱','躁鬱','精神功能症','失智','其他']" :selected.sync="basisigtifg.mental"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateMental"
                                           label="mental" :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="癌症" type="warning" :disabled="isNoneC">癌症</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <bs-input name="cancer" :value.sync="basisigtifg.cancer" :disabled="isNoneC"></bs-input>
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
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basisigtifg.gestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateGestation"
                                       label="gestation" v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                      <div class="row">
                        <label class="control-label col-xs-2 col-xs-offset-1" for="stopgestation">因糖尿病終止妊娠</label>
                        <div class="col-xs-7">
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basisigtifg.stopgestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateStopgestation"
                                       label="stopgestation" v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="fami_medic_his">家族史</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basisigtifg.fami_medic_his" buttons="false">
                          <p><pre>Values: {{ basisigtifg.fami_medic_his }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <v-checkbox value="不詳">不詳</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="糖尿病" type="warning" :disabled="isNoneF">糖尿病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisigtifg.relatives"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateRelatives"
                                           label="relatives" :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="高血壓" type="warning" :disabled="isNoneF">高血壓</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisigtifg.hypertension"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateHypertension"
                                           label="hypertension" :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="心臟病" type="warning" :disabled="isNoneF">心臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisigtifg.cardiovascular"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateCardiovascular"
                                           label="cardiovascular" :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="中風" type="warning" :disabled="isNoneF">中風</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basisigtifg.stroke"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateStroke"
                                           label="stroke" :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="activity">活動量</label>
                      <div class="col-xs-7">
                        <multiselect :options="['無','輕','中','重','臥床']" :selected.sync="basisigtifg.activity"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateActivity"
                                     label="activity">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="education">教育程度</label>
                      <div class="col-xs-7">
                        <multiselect :options="['不詳','不識字','識數字','識字','日教','國小','國中','高中','大專','大學','碩士','博士']" :selected.sync="basisigtifg.education"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateEducation"
                                     label="education">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="occupation">職業內容</label>
                      <div class="col-xs-7">
                        <bs-input name="occupation" :value.sync="basisigtifg.occupation" placeholder="若無，請忽略此欄；若有，請填寫!"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="worktime">工作時間</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisigtifg.worktime | json }}</pre></p>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.worktime" value="固定" type="info">固定</radio>
                          </div>
                          <div class="col-xs-10">
                            <!-- p><pre>Value: {{-- basisigtifg.fixedtime_from | json }}</pre></p -->
                            <vue-timepicker :time-value.sync="basisigtifg.fixedtime_from" format="HH:mm"></vue-timepicker>～
                            <vue-timepicker :time-value.sync="basisigtifg.fixedtime_to" format="HH:mm"></vue-timepicker>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.worktime" value="不固定" type="danger">不固定</radio>
                          </div>
                          <div class="col-xs-10">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.worktime" value="輪班" type="warning">輪班</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="dayshift">日班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisigtifg.dayshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisigtifg.dayshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="middleshift">中班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisigtifg.middleshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisigtifg.middleshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="nightshift">晚班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basisigtifg.nightshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basisigtifg.nightshift_to" format="HH:mm"></vue-timepicker>
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
                        <multiselect :options="['聽力障礙','視力障礙','手部不靈活','失聰','失明','智力障礙','情緒因素','疾病因素','其他']" :selected.sync="basisigtifg.affectlearning"
                                     :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateAffectlearning"
                                     label="affectlearning">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="livingcondition">居住狀況</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisigtifg.livingcondition | json }}</pre></p>
                        <radio :checked.sync="basisigtifg.livingcondition" value="獨居" type="info">獨居</radio>
                        <radio :checked.sync="basisigtifg.livingcondition" value="與家人同住" type="info">與家人同住</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.livingcondition" value="安養中心" type="danger">安養中心</radio>
                          </div>
                          <div class="col-xs-10">
                            <multiselect :options="['24小時','日托']" :selected.sync="basisigtifg.nursinghome"
                                         :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateNursinghome"
                                         label="nursinghome">  </multiselect>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="careunit">照顧單位</label>
                          <div class="col-xs-9">
                            <bs-input name="careunit" :value.sync="basisigtifg.careunit" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="livingtel">聯絡電話</label>
                          <div class="col-xs-9">
                            <bs-input name="livingtel" :value.sync="basisigtifg.livingtel" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="selfcare">自我照顧</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisigtifg.selfcare | json }}</pre></p>
                        <radio :checked.sync="basisigtifg.selfcare" value="完全獨立" type="info">完全獨立</radio>
                        <radio :checked.sync="basisigtifg.selfcare" value="需部分協助照顧" type="info">需部分協助照顧</radio>
                        <radio :checked.sync="basisigtifg.selfcare" value="完全由旁人照顧" type="danger">完全由旁人照顧</radio>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="caregiver">照顧者</label>
                          <div class="col-xs-9">
                            <bs-input name="caregiver" :value.sync="basisigtifg.caregiver" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="caretel">聯絡電話</label>
                          <div class="col-xs-9">
                            <bs-input name="caretel" :value.sync="basisigtifg.caretel" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="sport">運動次數</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisigtifg.sport | json }}</pre></p>
                        <radio :checked.sync="basisigtifg.sport" value="無" type="info">無</radio>
                        <radio :checked.sync="basisigtifg.sport" value="不規律" type="info">不規律</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.sport" value="規律" type="danger">規律</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="sporta">A種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta1" :value.sync="basisigtifg.sporta1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true" lazy></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta2" :value.sync="basisigtifg.sporta2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportb">B種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb1" :value.sync="basisigtifg.sportb1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb2" :value.sync="basisigtifg.sportb2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportc">C種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc1" :value.sync="basisigtifg.sportc1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc2" :value.sync="basisigtifg.sportc2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">分鐘/次</label>
                            </div>
                            <div class="row">
                              <div class="col-xs-2 col-xs-offset-1">
                                <!-- button type="button" class="btn btn-danger" @click="calcSport">計算加總</button -->
                              </div>
                              <div class="col-xs-5 text-right">
                                <bs-input name="sportsum" :value.sync="basisigtifg.sportsum" pattern="^[0-9]+$" maxlength="5" placeholder="自動計算加總" help="此欄位係自動累加上述數值!" :disabled="isSum" icon></bs-input>
                              </div>
                              <label class="control-label col-xs-4" for="sportsum">分鐘/周</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="closed">結案</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basisigtifg.closed | json }}</pre></p>
                        <radio :checked.sync="basisigtifg.closed" value="無" type="info">無</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.closed" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-5">
                            <datepicker v-ref:dp :value.sync="basisigtifg.close_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="300px"></datepicker>
                          </div>
                          <div class="col-xs-1"> &nbsp;</div>
                          <div class="col-xs-4">
                            <multiselect :options="['Select option','正常','T2DM']" :selected.sync="basisigtifg.closed_do" :multiple="false" :searchable="true" :close-on-select="false"
                                         :show-labels="false" @update="updateCloseddo" label="closed_do">  </multiselect>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basisigtifg.closed" value="歿" type="warning">歿</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedyear">西元年</label>
                              <div class="col-xs-3">
                                <multiselect :options="yearoptions" :selected.sync="basisigtifg.closed_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecYear" label="closed_year">  </multiselect>
                              </div>
                              <label class="control-label col-xs-3" for="closedmonth">月份</label>
                              <div class="col-xs-3">
                                <multiselect :options="monthoptions" :selected.sync="basisigtifg.closed_month" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecMonth" label="closed_month">  </multiselect>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedcause">原因</label>
                              <div class="col-xs-3">
                                <multiselect :options="['Select option','意外','癌症','疾病','不明原因']" :selected.sync="basisigtifg.closedcause" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateCause" label="closedcause">  </multiselect>
                              </div>
                              <div class="col-xs-6">
                                <bs-input name="closedreason" :value.sync="basisigtifg.closedreason" placeholder="請填寫!"></bs-input>
                              </div>
                            </div>
                          </div>
                        </div>
                        <radio :checked.sync="basisigtifg.closed" value="其它診斷" type="success">其它診斷</radio>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <br /><br />
                  </form-group>
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-warning" :disabled="!valid.all" @click="updateBasisigtifg(basisigtifg)"><i class="fa fa-pencil-square-o"></i>修改</button>
                    <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteBasisigtifg(basisigtifg)"><i class="fa fa-minus-circle"></i>刪除</button>
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
        basisigtifg: {},
        cases: {},
        yearoptions: [],
        monthoptions: [],
        weightoptions: [],
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
            console.log(response)
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
            console.log(response)
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
        this.$set('basisigtifg.log_date', thisday)
        this.$set('basisigtifg.close_date', thisday)
//        this.$set('basisigtifg.medicationdate', today)
//        this.$set('basisigtifg.injectiondate', today)
//        console.log(this.today)
//        console.log(this.basisigtifg.close_date)
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
      deleteBasisigtifg (basisigtifg) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名使用者的基本資料(IGT/IFG)!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.basisigtifg.$remove(basisigtifg)
          self.$http.delete('/api/basisigtifg/' + basisigtifg.id, basisigtifg).then(function (response) {
            self.$router.go('/basisigtifg')
            swal(
              '已刪除!!',
              '該名使用者的基本資料(IGT/IFG)已經刪除!!',
              'success'
            )
            this.active = 0
            this.disablequery = 0
          }, function (response){
            show_stack_error('刪除使用者的基本資料(IGT/IFG)發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名使用者的基本資料(IGT/IFG)維持現狀 :)',
              'error'
            )
          }
        })
      },
      createBasisigtifg () {
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
          this.$http.post('/api/basisigtifg', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              // var pid = $('input[name="pid"]').val()
              // alert(response.data.message)
              this.$set('basisigtifg', response.data)
              //結案日期
              if(this.basisigtifg.closed != "有") {
                this.$set('basisigtifg.close_date', this.today)
              }
              // console.log(response.data.message)
              // console.log(this.basisigtifg.fixedtime_from)
  //            this.$http({url: '/api/basisigtifg/showpid/' + pid, method: 'GET'})
  //              .then(function (response) {
  //                this.$set('basisigtifg', response.data)
  //              })
  //              .catch(function(response) {
  //                console.log(response)
  //              })
              this.active = 1
              this.disablequery = 1
            }, function (response) {
              // console.log(response.data.error.message)
              if (response.data.error.message == "The pid has already been taken.") {
                show_stack_info('該基本資料(IGT/IFG)已新增, 可修改!!', response)

                var pid = $('input[name="pid"]').val()
                this.$http({url: '/api/basisigtifg/showpid1/' + pid, method: 'GET'})
                  .then(function (response) {
                    this.$set('basisigtifg', response.data)
                    //結案日期
                    if(this.basisigtifg.closed != "有") {
                      this.$set('basisigtifg.close_date', this.today)
                    }
                    // for debug...
                    // console.log(response.data.message)
                    // console.log(this.basisigtifg.fixedtime_from)
                    // JSON.parse(myJSONString)
                  })
                  .catch(function(response) {
                    console.log(response)
                  })

                this.active = 1
                this.disablequery = 1
              } else {
                show_stack_error('新增資料(IGT/IFG)失敗!!', response)
              }
            })
            .catch(function(response) {
              console.log(response)
            })
        }
      },
      updateBasisigtifg (basisigtifg) {
  //      event.preventDefault()
//        alert(basisigtifg.closed)
        this.$http.patch('/api/basisigtifg/' + basisigtifg.id, basisigtifg)
          .then(function (response) {
            show_stack_success('使用者的基本資料(IGT/IFG)已更新!', response)
  //          alert(JSON.stringify(response))
  //          console.log(response.data.message)
          }, function (response) {
            show_stack_error('使用者的基本資料(IGT/IFG)更新失敗!', response)
  //          alert(response.data.error.message)
          })
          .catch(function(response) {
            console.log(response)
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
            console.log(response)
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
        this.basisigtifg.fall_ill_year = value
      },
      updateMonth (value) {
        this.basisigtifg.fall_ill_month = value
      },
      updateII (value) {
        this.basisigtifg.fall_ill_ii = value
      },
      updatecYear (value) {
        this.basisigtifg.closed_year = value
      },
      updatecMonth (value) {
        this.basisigtifg.closed_month = value
      },
      updategYear (value) {
        this.basisigtifg.g6pd_year = value
      },
      updategMonth (value) {
        this.basisigtifg.g6pd_month = value
      },
      updateLung (value) {
        this.basisigtifg.lung = value
      },
      updateLiver (value) {
        this.basisigtifg.liver = value
      },
      updateMental (value) {
        this.basisigtifg.mental = value
      },
      updateGestation (value) {
        this.basisigtifg.gestation = value
      },
      updateStopgestation (value) {
        this.basisigtifg.stopgestation = value
      },
      updateHeartdisease (value) {
        this.basisigtifg.heartdisease = value
      },
      updateBlindness (value) {
        this.basisigtifg.blindness = value
      },
      updateDialysis (value) {
        this.basisigtifg.dialysis = value
      },
      updateAmputation (value) {
        this.basisigtifg.amputation = value
      },
      updateRelatives (value) {
        this.basisigtifg.relatives = value
      },
      updateHypertension (value) {
        this.basisigtifg.hypertension = value
      },
      updateCardiovascular (value) {
        this.basisigtifg.cardiovascular = value
      },
      updateStroke (value) {
        this.basisigtifg.stroke = value
      },
      updateActivity (value) {
        this.basisigtifg.activity = value
      },
      updateEducation (value) {
        this.basisigtifg.education = value
      },
      updateAffectlearning (value) {
        this.basisigtifg.affectlearning = value
      },
      updateNursinghome (value) {
        this.basisigtifg.nursinghome = value
      },
      updateCause (value) {
        this.basisigtifg.closedcause = value
      },
      updateCloseddo (value) {
        this.basisigtifg.closed_do = value
      }
    },
    computed: {
      isSum: function () {
        var ss = 0
        if (this.basisigtifg.sporta1 != null && this.basisigtifg.sporta2 != null) {
          ss = this.basisigtifg.sporta1 * this.basisigtifg.sporta2
        }
        if (this.basisigtifg.sportb1 != null && this.basisigtifg.sportb2 != null) {
          ss = ss + this.basisigtifg.sportb1 * this.basisigtifg.sportb2
        }
        if (this.basisigtifg.sportc1 != null && this.basisigtifg.sportc2 != null) {
          ss = ss + this.basisigtifg.sportc1 * this.basisigtifg.sportc2
        }
        $('input[name="sportsum"]').val(ss)
        return (ss >= 0)
      },
      isNoneC: function () {
        if (this.basisigtifg.comorbidity != null) {
          var comorbiditys = this.basisigtifg.comorbidity || ""
          var a = comorbiditys.indexOf("無共病")
          if (a >= 0) {
            this.basisigtifg.cancer = ""
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
        if (this.basisigtifg.complication != null) {
          var complications = this.basisigtifg.complication || ""
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
        if (this.basisigtifg.fami_medic_his != null) {
          var fami_medic_hiss = this.basisigtifg.fami_medic_his || ""
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
        var bid = this.basisigtifg.pid
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