<template>
  <section class="content">
    <div class="callout callout-info lead">
      <h4>Telegram傳訊</h4>
      <h5><p>
        您可以在此使用 <b>Telegram Messenger</b> 跨平台的即時通訊軟體，並傳送訊息至 <b>DM診所</b> 群組。
      </p></h5>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-primary">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <form action="/api/telegram-send" class="form-signin" method="POST">
          <h2 class="form-signin-heading">Send Message To DM診所(Group) as Telegram Bot</h2>
          <br />
          <label class="typo__label">Select / dropdown</label>
          <multiselect :options="[{name: 'DM診所(群組)', userid: '-209111206'},{name: 'dmclinicyu(頻道)', userid: '-1001070282724'},{name: 'DMClinicYU_Bot(機器人)', userid: '315938616'}]"
                       :selected.sync="telegrams.chatid" :multiple="false" :searchable="true"
                       placeholder="請選擇...傳訊對象" track-by="name" label="name"
                       :close-on-select="false" :show-labels="false" @update="updateUserid">  </multiselect>
          <br />
          <label for="inputText" class="sr-only">Message</label>
          <textarea name="message" type="text" id="inputText" v-model="telegrams.message" class="form-control" placeholder="Message" required autofocus></textarea>
          <br />
          <!-- button class="btn btn-lg btn-primary btn-block" type="submit">Send Message</button -->
          <button type="button" class="btn btn-lg btn-primary btn-block" @click="postSendMessage(telegrams)">Send Message</button>
        </form>
        <br />
        <!--
        @if(Session::has('message'))
        <div class="alert alert-{{-- Session::get('status') --}} status-box">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          {{-- Session::get('message') --}}
        </div>
        @endif
        -->
      </div>
    </div>
  </section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'

  export default {
    components: {
      Multiselect
    },
    ready () {
    },
    data () {
      return {
        token: csrf_token,
        telegrams: {}
      }
    },
    methods: {
      postSendMessage (telegrams) {
        if ($("#inputText").val() == '') {
          alert('訊息內容不可空白!!')
          return false
        } else {
          if (confirm("確定發送訊息嗎?! \n請勿亂發群組訊息!!!")) {
            var input = this.telegrams
            this.$http.post('/api/telegram-send', input)
              .then(function (response) {
                // console.log(response.data.message)
                alert(response.data.message)
              }, function (response) {
                console.log(response.data.message)
              })
              .catch(function (response) {
                console.log(response)
              })
            $("#inputText").val('')
          }
        }
      },
      updateUserid (value) {
        this.telegrams.chatid = value
      }
    }
  }

</script>
