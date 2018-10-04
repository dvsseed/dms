var linebot = require('linebot');
var express = require('express');

var bot = linebot({
  channelId: '1527746449',
  channelSecret: '293332b9c8db2ac7c43d2baa394c05d5',
  channelAccessToken: 'MrdklU/eR63kV2KIHYpwm4sdoTCya/RcjIy0Vp9cnPn+DIa2+u1/7q6Okvor8OBr+iI/800fPgrZwV3+fLF5rb9ZsVxbiqGs7ZteOneh7wNlLjTwd9uFMC/RGEfMmVGsRLUH28bRA1xfSGUySSbBMgdB04t89/1O/w1cDnyilFU='
});

// 這一段的程式是專門處理當有人傳送文字訊息給LineBot時，我們的處理回應
bot.on('message', function(event) {
  if (event.message.type = 'text') {
    var msg = event.message.text;
    // 收到文字訊息時，直接把收到的訊息傳回去
    event.reply(msg).then(function(data) {
      // 傳送訊息成功時，可在此寫程式碼
      console.log(msg);
    }).catch(function(error) {
      // 傳送訊息失敗時，可在此寫程式碼
      console.log('錯誤產生!!錯誤碼：' + error);
    });
  }
});

const app = express();
const linebotParser = bot.parser();
app.post('/', linebotParser);

var server = app.listen(process.env.PORT || 8080, function() {
  var port = server.address().port;
  console.log('目前的port是', port);
});
