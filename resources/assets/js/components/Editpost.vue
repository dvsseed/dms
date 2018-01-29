<template>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">文章編輯器
                        <small>Markdown Editor</small>
                    </h3>
                </div>
                <div class="box-body pad">
                    <div class="form-horizontal" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="title" class="col-sm-1 control-label">標題</label>
                        <div class="col-sm-11">
                          <input class="form-control" id="title" placeholder="簡短明確" v-model="post.title">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="categories" class="col-sm-1 control-label">選擇類別</label>
                        <div class="col-sm-11">
                            <multiselect :selected.sync="post.categories.data" :options="options2" :multiple="true"
                            key="hashid" label="name" :on-change="onChangeAction">

                            </multiselect>
                        </div>
                      </div>
                      <div class="form-group">
                          <label for="image" class="col-sm-1 control-label">圖像</label>
                          <div class="col-sm-5">
                              <dropzone :model='post.image' :action="'/api/posts/'+postId+'/image'"></dropzone>
                          </div>
                      </div>
                      <div class="form-group">
                        <label for="description" class="col-sm-1 control-label">描述</label>
                        <div class="col-sm-11">
                          <input class="form-control" id="description" placeholder="說明內容" v-model="post.description">
                        </div>
                      </div>
                        <textarea id="mdeditor" name="content"></textarea>
                        <button v-if="isPublished" type="button" @click="publishPost(post)" class="btn btn-lg btn-success btn-flat pull-right"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> 公布</button>
                        <button type="button" @click="updatePost(post)" class="btn btn-lg btn-primary btn-flat pull-right"><i class="fa fa-cloud" aria-hidden="true"></i> 存</button>
                        <button type="button" @click="deletePost(post)" class="btn btn-lg btn-danger btn-flat"><i class="fa fa-trash-o" aria-hidden="true"></i> 刪</button>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-->
    </div>
    <!-- ./row -->
</section>
</template>
<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import SimpleMDE from 'simplemde'
  import Dropzone from './Dropzone.vue'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

  export default {
    created() {
      this.postId = this.$route.params.hashid
      this.fetchPost()
      this.fetchCatetgories()
    },
    components: {
      Dropzone,
      Multiselect
    },
    ready(){
      this.simplemde = new SimpleMDE({
        element: document.getElementById("mdeditor"),
        spellChecker: false,
      });
    },
    data () {
      return {
        token: csrf_token,
        post: {
          categories: {
            data:[]
          }
        },
        postId: '',
        simplemde: '',
        options2: [],
        values: []
        }
    },
    methods: {
      fetchPost () {
        this.$http({url: '/api/posts/' + this.postId + '?include=categories', method: 'GET'}).then(function (response) {
          this.$set('post', response.data)
          this.simplemde.value(this.post.content);
          this.$set('values', response.data.categories.data)
        })
      },
      updatePost (post) {
        post.content = this.simplemde.value()
        this.$http.patch('/api/posts/' + post.hashid, post).then((response) => {
            show_stack_success('文章已儲存!', response)
          }, function (response){
            show_stack_error('文章存檔失敗!', response)
          })
        this.$router.go('/')
      },
      publishPost (post) {
      if (this.values === undefined || this.values == 0) {
          swal('抱歉', '在公告本文章前, 請先選擇[類別]?!', 'info')
      } else {
        let self = this
        swal({
          title: '您即將發布這篇文章!!',
          text: '確定嗎?!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '請公布!!',
          cancelButtonText: '尚不公布',
        }).then(function() {
          self.$http.post('/api/posts/' + post.hashid + '/publish', post).then(function (response) {
          swal(
            '已公布!!',
            '您的文章已發布至網站!!',
            'success'
          );
          this.$router.go('/posts/')
          }, function (response){
            show_stack_error('發布文章失敗!!', response)
          })
        }, function(dismiss) {
          // dismiss can be 'cancel', 'overlay', 'close', 'timer'
          if (dismiss === 'cancel') {
          swal(
            '已取消',
            '您的文章維持現狀 :)',
            'error'
          );
          }
        });
       }
      },
      fetchCatetgories () {
        this.$http({url: '/api/categories', method: 'GET'}).then(function (response) {
          this.$set('options2', response.data.data)
        })
      },
      onChangeAction (value) {
        this.values = value
        this.$http.patch( '/api/posts/' + this.postId + '/categories', {categories: value}).then((response) => {
          show_stack_success('類別已更新!', response)
        }, function (response){
          show_stack_error('類別更新失敗!', response)
        });
      },
      deletePost (post) {
      let self = this
      swal({
        title: '確定嗎?!',
        text: '您將無法救回此文章!!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '是的, 刪除!!',
        cancelButtonText: '不, 取消!!',
      }).then(function() {
        self.$http.delete('/api/posts/' + post.hashid, post).then(function (response) {
          self.$router.go('/posts')
          swal(
          '已刪除!!',
          '您的文章已經刪除!!',
          'success'
          );
        }, function (response){
          show_stack_error('刪除文章發生錯誤!!', response)
        })
      }, function(dismiss) {
        // dismiss can be 'cancel', 'overlay', 'close', 'timer'
        if (dismiss === 'cancel') {
          swal(
          '已取消',
          '您的文章維持現狀 :)',
          'error'
          );
        }
      });
      }
    },
    computed: {
      isPublished: function () {
      if (this.post.status !== 'approved' ) {
        return true
      } else {
        return false
      }
      }
    }
  }
</script>
