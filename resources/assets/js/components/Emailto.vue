<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>Email寄信</h4>
      <h5><p>
        您可以在此使用 <b>Email</b> 寄發SMTP郵件給其他人。
      </p></h5>
    </div>
    <form role="form">
      <div class="box-body" style="background: #FFFFBB;">
        <form-group :valid.sync="valid.send">
          <div class="row">
            <label class="control-label col-xs-2 col-xs-offset-1" for="recipient"><span class="text-danger">*收件者</span></label>
            <div class="col-xs-7">
              <bs-input name="recipient" :value.sync="email.recipient" help="請輸入...正確的郵件信箱格式!!" error="請輸入...收件者" placeholder="收件者" pattern="^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$" required></bs-input>
            </div>
            <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
          </div>
          <div class="row">
            <label class="control-label col-xs-2 col-xs-offset-1" for="rname"><span class="text-danger">*收件者姓名</span></label>
            <div class="col-xs-7">
              <bs-input name="rname" :value.sync="email.rname" error="請輸入...收件者姓名" placeholder="收件者姓名" required></bs-input>
            </div>
            <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
          </div>
          <hr />
          <div class="row">
            <label class="control-label col-xs-2 col-xs-offset-1" for="cc">cc副本</label>
            <div class="col-xs-7">
              <bs-input name="recipient" :value.sync="email.cc" placeholder="副本" help="請輸入...正確的郵件信箱格式!!" pattern="^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$"></bs-input>
            </div>
            <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
          </div>
          <hr />
          <div class="row">
            <label class="control-label col-xs-2 col-xs-offset-1" for="subject"><span class="text-danger">*主旨</span></label>
            <div class="col-xs-7">
              <bs-input name="subject" :value.sync="email.subject" error="請輸入...主旨" placeholder="主旨" required></bs-input>
            </div>
            <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
          </div>
          <hr />
          <div class="row">
            <label class="control-label col-xs-2 col-xs-offset-1" for="ebody">內容</label>
            <div class="col-xs-7">
              <bs-input name="ebody" :value.sync="email.ebody" type="textarea"></bs-input>
            </div>
            <div class="col-xs-1 col-xs-offset-1"><span> </span></div>
          </div>
          <button type="button" class="btn btn-md btn-primary btn-flat" :disabled="!valid.send" @click="sendMail(email)" style="margin-bottom: 10px;"><i class="fa fa-envelope" aria-hidden="true"></i> Email傳送</button>
        </form-group>
      </div>
    </form>
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import formGroup from './vue-strap/src/FormGroup.vue'
  import bsInput from './vue-strap/src/Input.vue'
  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

  export default {
    components: {
      formGroup,
      bsInput
    },
    ready () {
    },
    data () {
      return {
        token: csrf_token,
        email: {},
        valid: {}
      }
    },
    methods: {
      sendMail (email) {
        if (confirm('確定寄信嗎?!')) {
          this.$http.post('/api/mailbyqueue', email).then(function (response) {
            // form submission successful, reset post data and set submitted to true
            this.email = {
              recipient: '',
              rname: '',
              cc: '',
              subject: '',
              ebody: '',
            }
            this.valid = {}
            // console.log(response.data)
            // clear previous form errors
//            this.$set('errors', '')
            show_stack_success('Email寄送成功!', response)
          }, function (response) {
            // form submission failed, pass form  errors to errors array
//            this.$set('errors', response.data)
            show_stack_error('Email寄送失敗!', response)
          })
        }
      }
    }
  }

</script>
