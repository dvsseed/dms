<template>
  <section class="content">
    <!-- info -->
    <div class="callout callout-info lead">
      <h4>基本資料(T2DM)管理</h4>
      <h5><p>
        您可以在此管理::[<b>基本資料(T2DM)</b>]--(<b>本共照平台的病患</b>)，功能有：<b>新增、修改、刪除、清除</b>...等
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
                <tab header="基本資料(T2DM)-查詢">
                  <form-group :valid.sync="valid.query">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="pid"><span class="text-danger">*病患身份證號</span></label>
                      <div class="col-xs-7">
                        <bs-input name="pid" :value.sync="basiscache.pid" maxlength="18" :mask="mask" placeholder="請輸入...身份證" required></bs-input>
                        <!-- pattern="^[A-Z][1-2][0-9]+$" -->
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
                    <button type="button" class="btn btn-primary" :disabled="!valid.query" @click="createBasist2dm()"><i class="fa fa-plus-circle"></i>新增</button>
                    <button type="button" class="btn btn-info" :disabled="!valid.query" @click="clear2(basiscache)"><i class="fa fa-eraser"></i>清除</button>
                  </div>
                </tab>
                <tab header="基本資料(T2DM)-新增" :disabled="!disablequery">
                  <form-group :valid.sync="valid.create">
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="log_date">登錄日期</label>
                      <div class="col-xs-7">
                        <!-- pre>Selected date is: {{-- new Date($refs.dp.parse()).toString() --}}</pre -->
                        <datepicker v-ref:dp :value.sync="basist2dm.log_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="diagnose">診斷時間</label>
                      <label class="control-label col-xs-1" for="diagnoseyear">西元年</label>
                      <div class="col-xs-3">
                        <multiselect :options="yearoptions" :selected.sync="basist2dm.fall_ill_year" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateYear">  </multiselect>
                        <!-- v-select name="fall_ill_year" :options="yearoptions" :value.sync="basist2dm.fall_ill_year" clear-button></v-select -->
                      </div>
                      <label class="control-label col-xs-1" for="diagnosemonth">月份</label>
                      <div class="col-xs-2">
                        <multiselect :options="monthoptions" :selected.sync="basist2dm.fall_ill_month" :multiple="false" :searchable="true" :close-on-select="false"
                                     :show-labels="false" @update="updateMonth">  </multiselect>
                        <!-- v-select name="fall_ill_month" :options="['01','02','03','04','05','06','07','08','09','10','11','12','不詳']" :value.sync="basist2dm.fall_ill_month" clear-button></v-select -->
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="symptom">發病症狀</label>
                      <div class="col-xs-7">
                        <p><pre>Values: {{ basist2dm.symptom }}</pre></p>
                        <button-group :value.sync="basist2dm.symptom" buttons="false">
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
                      <label class="control-label col-xs-2 col-xs-offset-1" for="treatment">糖尿病過去治療方式</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basist2dm.treatment" buttons="false">
                          <p><pre>Values: {{ basist2dm.treatment }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="口服藥" type="warning" :disabled="isNoneT">口服藥</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <div class="row">
                                <div class="col-xs-12">
                                  <multiselect :options="['Metformin','SU','Glinide','DPP4-I','SGLT-2','TZD','Acarbose']" :selected.sync="basist2dm.medication"
                                               :multiple="true" :searchable="false" :close-on-select="false" :show-labels="false" @update="updateMedication"
                                               :disabled="isNoneT">  </multiselect>
                                </div>
                              </div>
                              <div class="row">
                                <label class="control-label col-xs-2" for="medicationyear">西元年</label>
                                <div class="col-xs-4">
                                  <multiselect :options="yearoptions" :selected.sync="basist2dm.medication_year" :multiple="false" :searchable="true" :close-on-select="false"
                                               :show-labels="false" @update="updatemYear" :disabled="isNoneT">  </multiselect>
                                </div>
                                <label class="control-label col-xs-2" for="medicationmonth">月份</label>
                                <div class="col-xs-4">
                                  <multiselect :options="monthoptions" :selected.sync="basist2dm.medication_month" :multiple="false" :searchable="true" :close-on-select="false"
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
                                  <multiselect :options="yearoptions" :selected.sync="basist2dm.injection_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatenYear" :disabled="isNoneT">  </multiselect>
                                </div>
                                <label class="control-label col-xs-2" for="injectionmonth">月份</label>
                                <div class="col-xs-3">
                                  <multiselect :options="monthoptions" :selected.sync="basist2dm.injection_month" :multiple="false" :searchable="true" :close-on-select="false"
                                               :show-labels="false" @update="updatenMonth" :disabled="isNoneT">  </multiselect>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xs-6 col-xs-offset-4">
                                <bs-input name="insulin" :value.sync="basist2dm.insulin" :disabled="isNoneT"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="insulin">天次</label>
                            </div>
                            <!-- div class="row" -->
                              <!-- label class="control-label col-xs-2 col-xs-offset-3" for="longterm">長效</label -->
                              <!-- div class="col-xs-5" -->
                                <!-- bs-input name="longterm" :value.sync="basist2dm.longterm" :disabled="isNoneT"></bs-input -->
                              <!-- /div -->
                              <!-- label class="control-label col-xs-2" for="longterm">單位/天</label -->
                            <!-- /div -->
                            <!-- div class="row" -->
                              <!-- label class="control-label col-xs-2 col-xs-offset-3" for="ineffect">中效</label -->
                              <!-- div class="col-xs-5" -->
                                <!-- bs-input name="ineffect" :value.sync="basist2dm.ineffect" :disabled="isNoneT"></bs-input -->
                              <!-- /div -->
                              <!-- label class="control-label col-xs-2" for="ineffect">單位/天</label -->
                            <!-- /div -->
                            <!-- div class="row" -->
                              <!-- label class="control-label col-xs-2 col-xs-offset-3" for="shortacting">短效</label -->
                              <!-- div class="col-xs-5" -->
                                <!-- bs-input name="shortacting" :value.sync="basist2dm.shortacting" :disabled="isNoneT"></bs-input -->
                              <!-- /div -->
                              <!-- label class="control-label col-xs-2" for="shortacting">單位/天</label -->
                            <!-- /div -->
                            <!-- div class="row" -->
                              <!-- label class="control-label col-xs-2 col-xs-offset-3" for="quickeffect">速效</label -->
                              <!-- div class="col-xs-5" -->
                                <!-- bs-input name="quickeffect" :value.sync="basist2dm.quickeffect" :disabled="isNoneT"></bs-input -->
                              <!-- /div -->
                              <!-- label class="control-label col-xs-2" for="quickeffect">單位/天</label -->
                            <!-- /div -->
                          <!-- /div -->
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="GLP-1" type="warning" :disabled="isNoneT">GLP-1</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <label class="control-label col-xs-2" for="injectionyear">西元年</label>
                              <div class="col-xs-3">
                                <multiselect :options="yearoptions" :selected.sync="basist2dm.glp1_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateiYear" :disabled="isNoneT">  </multiselect>
                              </div>
                              <label class="control-label col-xs-1" for="injectionmonth">月份</label>
                              <div class="col-xs-3">
                                <multiselect :options="monthoptions" :selected.sync="basist2dm.glp1_month" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateiMonth" :disabled="isNoneT">  </multiselect>
                              </div>
                              <div class="col-xs-3">
                                <v-select name="glp1" :options="['無','2次/天','1次/天','1次/周','1次/月']" :value.sync="basist2dm.glp1" clear-button :disabled="isNoneT"></v-select>
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
                      <label class="control-label col-xs-2 col-xs-offset-1" for="continuous">持續治療</label>
                      <div class="col-xs-7">
                        <v-select name="continuous" :options="['無','規則治療','不規則治療']" :value.sync="basist2dm.continuous" clear-button></v-select>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="comorbidity">共病</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basist2dm.comorbidity" buttons="false">
                          <p><pre>Values: {{ basist2dm.comorbidity }}</pre></p>
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
                              <multiselect :options="['COPD','開放型','非開放性']" :selected.sync="basist2dm.lung"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateLung"
                                           :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="肝臟病" type="warning" :disabled="isNoneC">肝臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['A型肝炎','B型肝炎','C型肝炎','脂肪肝','肝硬化']" :selected.sync="basist2dm.liver"
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
                              <multiselect :options="['失眠','焦慮','憂鬱','躁鬱','精神功能症','失智','其他']" :selected.sync="basist2dm.mental"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateMental"
                                           :disabled="isNoneC">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="癌症" type="warning" :disabled="isNoneC">癌症</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <bs-input name="cancer" :value.sync="basist2dm.cancer" :disabled="isNoneC"></bs-input>
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
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basist2dm.gestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateGestation"
                                       v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                      <div class="row">
                        <label class="control-label col-xs-2 col-xs-offset-1" for="stopgestation">因糖尿病終止妊娠</label>
                        <div class="col-xs-7">
                          <multiselect :options="['不詳','0次','1次','2次','3次','4次','5次','6次','7次','8次','9次','10次']" :selected.sync="basist2dm.stopgestation"
                                       :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateStopgestation"
                                       v-if="isFemale">  </multiselect>
                        </div>
                        <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="complication">倂發症</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basist2dm.complication" buttons="false">
                          <p><pre>Values: {{ basist2dm.complication }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <v-checkbox value="不詳">不詳</v-checkbox>
                          <v-checkbox value="視網膜病變" type="warning" :disabled="isNoneN">視網膜病變</v-checkbox>
                          <v-checkbox value="糖尿病腎病變" type="warning" :disabled="isNoneN">糖尿病腎病變</v-checkbox>
                          <v-checkbox value="糖尿病神經病變" type="warning" :disabled="isNoneN">糖尿病神經病變</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="冠心病" type="warning" :disabled="isNoneN">冠心病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['支架','冠狀動脈繞道術','氣球擴張術','其他']" :selected.sync="basist2dm.heartdisease"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateHeartdisease"
                                           :disabled="isNoneN">  </multiselect>
                            </div>
                          </div>
                          <v-checkbox value="腦中風" type="warning" :disabled="isNoneN">腦中風</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="失明" type="warning" :disabled="isNoneN">失明</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['糖尿病','非糖尿病']" :selected.sync="basist2dm.blindness"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateBlindness"
                                           :disabled="isNoneN">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="洗腎" type="warning" :disabled="isNoneN">洗腎</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['糖尿病','非糖尿病']" :selected.sync="basist2dm.dialysis"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateDialysis"
                                           :disabled="isNoneN">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="截肢" type="warning" :disabled="isNoneN">截肢</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['糖尿病','非糖尿病']" :selected.sync="basist2dm.amputation"
                                           :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateAmputation"
                                           :disabled="isNoneN">  </multiselect>
                            </div>
                          </div>
                        </button-group>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="fami_medic_his">家族史</label>
                      <div class="col-xs-7">
                        <button-group :value.sync="basist2dm.fami_medic_his" buttons="false">
                          <p><pre>Values: {{ basist2dm.fami_medic_his }}</pre></p>
                          <v-checkbox value="無">無</v-checkbox>
                          <v-checkbox value="不詳">不詳</v-checkbox>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="糖尿病" type="warning" :disabled="isNoneF">糖尿病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basist2dm.relatives"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateRelatives"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="高血壓" type="warning" :disabled="isNoneF">高血壓</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basist2dm.hypertension"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateHypertension"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="心臟病" type="warning" :disabled="isNoneF">心臟病</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basist2dm.cardiovascular"
                                           :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateCardiovascular"
                                           :disabled="isNoneF">  </multiselect>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-2">
                              <v-checkbox value="中風" type="warning" :disabled="isNoneF">中風</v-checkbox>
                            </div>
                            <div class="col-xs-10">
                              <multiselect :options="['父系','母系','兄弟姐妹','子女','孫子女']" :selected.sync="basist2dm.stroke"
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
                        <multiselect :options="['無','輕','中','重','臥床']" :selected.sync="basist2dm.activity"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateActivity">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="education">教育程度</label>
                      <div class="col-xs-7">
                        <multiselect :options="['不詳','不識字','識數字','識字','日教','國小','國中','高中','大專','大學','碩士','博士']" :selected.sync="basist2dm.education"
                                     :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateEducation">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="occupation">職業內容</label>
                      <div class="col-xs-7">
                        <bs-input name="occupation" :value.sync="basist2dm.occupation" placeholder="若無，請忽略此欄；若有，請填寫!"></bs-input>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="worktime">工作時間</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basist2dm.worktime | json }}</pre></p>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.worktime" value="固定" type="info">固定</radio>
                          </div>
                          <div class="col-xs-10">
                            <!-- p><pre>Value: {{-- basist2dm.fixedtime_from | json }}</pre></p -->
                            <vue-timepicker :time-value.sync="basist2dm.fixedtime_from" format="HH:mm"></vue-timepicker>～
                            <vue-timepicker :time-value.sync="basist2dm.fixedtime_to" format="HH:mm"></vue-timepicker>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.worktime" value="不固定" type="danger">不固定</radio>
                          </div>
                          <div class="col-xs-10">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.worktime" value="輪班" type="warning">輪班</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="dayshift">日班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basist2dm.dayshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basist2dm.dayshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="middleshift">中班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basist2dm.middleshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basist2dm.middleshift_to" format="HH:mm"></vue-timepicker>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="nightshift">晚班</label>
                              <div class="col-xs-10">
                                <vue-timepicker :time-value.sync="basist2dm.nightshift_from" format="HH:mm"></vue-timepicker>～
                                <vue-timepicker :time-value.sync="basist2dm.nightshift_to" format="HH:mm"></vue-timepicker>
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
                        <multiselect :options="['聽力障礙','視力障礙','手部不靈活','失聰','失明','智力障礙','情緒因素','疾病因素','其他']" :selected.sync="basist2dm.affectlearning"
                                     :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateAffectlearning">  </multiselect>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="livingcondition">居住狀況</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basist2dm.livingcondition | json }}</pre></p>
                        <radio :checked.sync="basist2dm.livingcondition" value="獨居" type="info">獨居</radio>
                        <radio :checked.sync="basist2dm.livingcondition" value="與家人同住" type="info">與家人同住</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.livingcondition" value="安養中心" type="danger">安養中心</radio>
                          </div>
                          <div class="col-xs-10">
                            <multiselect :options="['24小時','日托']" :selected.sync="basist2dm.nursinghome"
                                         :multiple="false" :searchable="true" :close-on-select="false" :show-labels="false" @update="updateNursinghome">  </multiselect>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="careunit">照顧單位</label>
                          <div class="col-xs-9">
                            <bs-input name="careunit" :value.sync="basist2dm.careunit" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="livingtel">聯絡電話</label>
                          <div class="col-xs-9">
                            <bs-input name="livingtel" :value.sync="basist2dm.livingtel" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="selfcare">自我照顧</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basist2dm.selfcare | json }}</pre></p>
                        <radio :checked.sync="basist2dm.selfcare" value="完全獨立" type="info">完全獨立</radio>
                        <radio :checked.sync="basist2dm.selfcare" value="需部分協助照顧" type="info">需部分協助照顧</radio>
                        <radio :checked.sync="basist2dm.selfcare" value="完全由旁人照顧" type="danger">完全由旁人照顧</radio>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="caregiver">照顧者</label>
                          <div class="col-xs-9">
                            <bs-input name="caregiver" :value.sync="basist2dm.caregiver" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                        <div class="row">
                          <label class="control-label col-xs-2 col-xs-offset-1" for="caretel">聯絡電話</label>
                          <div class="col-xs-9">
                            <bs-input name="caretel" :value.sync="basist2dm.caretel" placeholder="請填寫!"></bs-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="sport">運動次數</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basist2dm.sport | json }}</pre></p>
                        <radio :checked.sync="basist2dm.sport" value="無" type="info">無</radio>
                        <radio :checked.sync="basist2dm.sport" value="不規律" type="info">不規律</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.sport" value="規律" type="danger">規律</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-2" for="sporta">A種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta1" :value.sync="basist2dm.sporta1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true" lazy></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sporta2" :value.sync="basist2dm.sporta2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sporta">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportb">B種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb1" :value.sync="basist2dm.sportb1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportb2" :value.sync="basist2dm.sportb2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportb">分鐘/次</label>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-2" for="sportc">C種類:</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc1" :value.sync="basist2dm.sportc1" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">次/周</label>
                              <div class="col-xs-3">
                                <bs-input name="sportc2" :value.sync="basist2dm.sportc2" pattern="^[0-9]+$" maxlength="5" placeholder="請填寫!" error="請輸入數值!" clear-button="true"></bs-input>
                              </div>
                              <label class="control-label col-xs-2" for="sportc">分鐘/次</label>
                            </div>
                            <div class="row">
                              <div class="col-xs-2 col-xs-offset-1">
                                <!-- button type="button" class="btn btn-danger" @click="calcSport">計算加總</button -->
                              </div>
                              <div class="col-xs-5 text-right">
                                <bs-input name="sportsum" :value.sync="basist2dm.sportsum" pattern="^[0-9]+$" maxlength="5" placeholder="自動計算加總" help="此欄位係自動累加上述數值!" :disabled="isSum" icon></bs-input>
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
                        <p><pre>Radio value: {{ basist2dm.glucometer | json }}</pre></p>
                        <radio :checked.sync="basist2dm.glucometer" value="無" type="info">無</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.glucometer" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-6">
                            <bs-input name="glucometerfrequency" :value.sync="basist2dm.glucometerfrequency" placeholder="請填寫!"></bs-input>
                          </div>
                          <label class="control-label col-xs-4" for="glucometerfrequency">次/月</label>
                        </div>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <div class="row">
                      <label class="control-label col-xs-2 col-xs-offset-1" for="g6pd">G6PD</label>
                      <div class="col-xs-7">
                        <p><pre>Radio value: {{ basist2dm.g6pd | json }}</pre></p>
                        <radio :checked.sync="basist2dm.g6pd" value="無" type="info">無</radio>
                        <radio :checked.sync="basist2dm.g6pd" value="不詳" type="info">不詳</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.g6pd" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-10">
                            <label class="control-label col-xs-4" for="g6pdyear">診斷日期: 西元年</label>
                            <div class="col-xs-3">
                              <multiselect :options="yearoptions" :selected.sync="basist2dm.g6pd_year" :multiple="false" :searchable="true" :close-on-select="false"
                                           :show-labels="false" @update="updategYear">  </multiselect>
                            </div>
                            <label class="control-label col-xs-2" for="g6pdmonth">月份</label>
                            <div class="col-xs-3">
                              <multiselect :options="monthoptions" :selected.sync="basist2dm.g6pd_month" :multiple="false" :searchable="true" :close-on-select="false"
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
                        <p><pre>Radio value: {{ basist2dm.closed | json }}</pre></p>
                        <radio :checked.sync="basist2dm.closed" value="無" type="info">無</radio>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.closed" value="有" type="danger">有</radio>
                          </div>
                          <div class="col-xs-10">
                            <datepicker v-ref:dp :value.sync="basist2dm.close_date" :disabled-days-of-Week="disabled" :format="format.toString()" :clear-button="clear" width="370px"></datepicker>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-2">
                            <radio :checked.sync="basist2dm.closed" value="歿" type="warning">歿</radio>
                          </div>
                          <div class="col-xs-10">
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedyear">西元年</label>
                              <div class="col-xs-3">
                                <multiselect :options="yearoptions" :selected.sync="basist2dm.closed_year" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecYear">  </multiselect>
                              </div>
                              <label class="control-label col-xs-3" for="closedmonth">月份</label>
                              <div class="col-xs-3">
                                <multiselect :options="monthoptions" :selected.sync="basist2dm.closed_month" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updatecMonth">  </multiselect>
                              </div>
                            </div>
                            <div class="row">
                              <label class="control-label col-xs-3" for="closedcause">原因</label>
                              <div class="col-xs-3">
                                <multiselect :options="['Select option','意外','癌症','疾病','不明原因']" :selected.sync="basist2dm.closedcause" :multiple="false" :searchable="true" :close-on-select="false"
                                             :show-labels="false" @update="updateCause">  </multiselect>
                              </div>
                              <div class="col-xs-6">
                                <bs-input name="closedreason" :value.sync="basist2dm.closedreason" placeholder="請填寫!"></bs-input>
                              </div>
                            </div>
                          </div>
                        </div>
                        <radio :checked.sync="basist2dm.closed" value="其它診斷" type="success">其它診斷</radio>
                      </div>
                      <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
                    </div>
                    <br /><br />
                  </form-group>
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-warning" :disabled="!valid.all" @click="updateBasist2dm(basist2dm)"><i class="fa fa-pencil-square-o"></i>修改</button>
                    <button type="button" class="btn btn-danger" :disabled="!disablequery" @click="deleteBasist2dm(basist2dm)"><i class="fa fa-minus-circle"></i>刪除</button>
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
      this.fetchCache()
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
        basist2dm: {},
        cases: {},
        yearoptions: [],
        monthoptions: [],
        weightoptions: [],
        today: ''
        // sportsum: false
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
//              this.$http({url: 'logout', method: 'GET'}).then(function (response) {
                window.location.assign('/auth/logout')
//              })
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
        this.$set('basist2dm.log_date', thisday)
        this.$set('basist2dm.close_date', thisday)
//        this.$set('basist2dm.medicationdate', today)
//        this.$set('basist2dm.injectiondate', today)
//        console.log(this.today)
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
      deleteBasist2dm (basist2dm) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回該名使用者的基本資料(T2DM)!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
  //        self.basist2dm.$remove(basist2dm)
          self.$http.delete('/api/basist2dm/' + basist2dm.id, basist2dm).then(function (response) {
            self.$router.go('/basist2dm')
            swal(
              '已刪除!!',
              '該名使用者的基本資料(T2DM)已經刪除!!',
              'success'
            )
            this.active = 0
            this.disablequery = 0
          }, function (response){
            show_stack_error('刪除使用者的基本資料(T2DM)發生錯誤!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
            swal(
              '已取消',
              '該名使用者的基本資料(T2DM)維持現狀 :)',
              'error'
            )
          }
        })
      },
      createBasist2dm () {
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
          this.$http.post('/api/basist2dm', input)
            .then(function (response) {
              show_stack_info('已新增', response)
              // var pid = $('input[name="pid"]').val()
              // alert(pid)
              this.$set('basist2dm', response.data)
              //結案日期
              if(this.basist2dm.closed != "有") {
                this.$set('basist2dm.close_date', this.today)
              }
              // console.log(response.data.message)
              // console.log(this.basist2dm.fixedtime_from)
  //            this.$http({url: '/api/basist2dm/showpid/' + pid, method: 'GET'})
  //              .then(function (response) {
  //                this.$set('basist2dm', response.data)
  //              })
  //              .catch(function(response) {
  //                console.log(response)
  //              })
              this.active = 1
              this.disablequery = 1
            }, function (response) {
              // console.log(response.data.error.message)
              if (response.data.error.message == "The pid has already been taken.") {
                show_stack_info('該基本資料(T2DM)已新增, 可修改!!', response)

                var pid = $('input[name="pid"]').val()
                this.$http({url: '/api/basist2dm/showpid1/' + pid, method: 'GET'})
                  .then(function (response) {
                    this.$set('basist2dm', response.data)
                    //結案日期
                    if(this.basist2dm.closed != "有") {
                      this.$set('basist2dm.close_date', this.today)
                    }
                    // for debug...
                    // console.log(response.data.message)
                    // console.log(this.basist2dm.fixedtime_from)
                    // JSON.parse(myJSONString)
                  })
                  .catch(function(response) {
                    console.log(response)
                  })

                this.active = 1
                this.disablequery = 1
              } else {
                show_stack_error('新增資料(T2DM)失敗!!', response)
              }
            })
            .catch(function(response) {
              console.log(response)
            })
        }
      },
      updateBasist2dm (basist2dm) {
  //      event.preventDefault()
//        alert(basist2dm.closed)
        this.$http.patch('/api/basist2dm/' + basist2dm.id, basist2dm)
          .then(function (response) {
            show_stack_success('使用者的基本資料(T2DM)已更新!', response)
  //          alert(JSON.stringify(response))
  //          console.log(response.data.message)
          }, function (response) {
            show_stack_error('使用者的基本資料(T2DM)更新失敗!', response)
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
            // alert('*  身份證號驗證計算錯誤！')
            if(confirm('*  身份證號驗證計算錯誤！\n\n是否照常作業?!')) {
              return true
            } else {
              return false
            }
          }
        } else {
          // alert('*  身份證號基本格式錯誤！')
          if(confirm('*  身份證號基本格式錯誤！\n\n是否照常作業?!')) {
            return true
          } else {
            return false
          }
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
        this.basist2dm.fall_ill_year = value
      },
      updateMonth (value) {
        this.basist2dm.fall_ill_month = value
      },
      updatemYear (value) {
        this.basist2dm.medication_year = value
      },
      updatemMonth (value) {
        this.basist2dm.medication_month = value
      },
      updatecYear (value) {
        this.basist2dm.closed_year = value
      },
      updatecMonth (value) {
        this.basist2dm.closed_month = value
      },
      updategYear (value) {
        this.basist2dm.g6pd_year = value
      },
      updategMonth (value) {
        this.basist2dm.g6pd_month = value
      },
      updateiYear (value) {
        this.basist2dm.glp1_year = value
      },
      updateiMonth (value) {
        this.basist2dm.glp1_month = value
      },
      updatenYear (value) {
        this.basist2dm.injection_year = value
      },
      updatenMonth (value) {
        this.basist2dm.injection_month = value
      },
      updateMedication (value) {
        this.basist2dm.medication = value
      },
      updateLung (value) {
        this.basist2dm.lung = value
      },
      updateLiver (value) {
        this.basist2dm.liver = value
      },
      updateMental (value) {
        this.basist2dm.mental = value
      },
      updateGestation (value) {
        this.basist2dm.gestation = value
      },
      updateStopgestation (value) {
        this.basist2dm.stopgestation = value
      },
      updateHeartdisease (value) {
        this.basist2dm.heartdisease = value
      },
      updateBlindness (value) {
        this.basist2dm.blindness = value
      },
      updateDialysis (value) {
        this.basist2dm.dialysis = value
      },
      updateAmputation (value) {
        this.basist2dm.amputation = value
      },
      updateRelatives (value) {
        this.basist2dm.relatives = value
      },
      updateHypertension (value) {
        this.basist2dm.hypertension = value
      },
      updateCardiovascular (value) {
        this.basist2dm.cardiovascular = value
      },
      updateStroke (value) {
        this.basist2dm.stroke = value
      },
      updateActivity (value) {
        this.basist2dm.activity = value
      },
      updateEducation (value) {
        this.basist2dm.education = value
      },
      updateAffectlearning (value) {
        this.basist2dm.affectlearning = value
      },
      updateNursinghome (value) {
        this.basist2dm.nursinghome = value
      },
      updateCause (value) {
        this.basist2dm.closedcause = value
      }
    },
    computed: {
      isSum: function () {
        var ss = 0
        if (this.basist2dm.sporta1 != null && this.basist2dm.sporta2 != null) {
          ss = this.basist2dm.sporta1 * this.basist2dm.sporta2
        }
        if (this.basist2dm.sportb1 != null && this.basist2dm.sportb2 != null) {
          ss = ss + this.basist2dm.sportb1 * this.basist2dm.sportb2
        }
        if (this.basist2dm.sportc1 != null && this.basist2dm.sportc2 != null) {
          ss = ss + this.basist2dm.sportc1 * this.basist2dm.sportc2
        }
        $('input[name="sportsum"]').val(ss)
        return (ss >= 0)
      },
      isNone: function () {
        if (this.basist2dm.symptom != null) {
          var symptoms = this.basist2dm.symptom || ""
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
  //      if (this.basist2dm.symptom != null) {
  //        var symptoms = this.basist2dm.symptom || ""
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
        if (this.basist2dm.treatment != null) {
          var treatments = this.basist2dm.treatment || ""
          var a = treatments.indexOf("無")
          if (a >= 0) {
            this.basist2dm.insulin = ""
            this.basist2dm.longterm = ""
            this.basist2dm.ineffect = ""
            this.basist2dm.shortacting = ""
            this.basist2dm.quickeffect = ""
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
        if (this.basist2dm.comorbidity != null) {
          var comorbiditys = this.basist2dm.comorbidity || ""
          var a = comorbiditys.indexOf("無共病")
          if (a >= 0) {
            this.basist2dm.cancer = ""
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
        if (this.basist2dm.complication != null) {
          var complications = this.basist2dm.complication || ""
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
        if (this.basist2dm.fami_medic_his != null) {
          var fami_medic_hiss = this.basist2dm.fami_medic_his || ""
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
        var bid = this.basist2dm.pid
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