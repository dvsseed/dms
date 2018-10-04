<template>
<section class="content">
  <div class="callout callout-info lead">
    <h4>公告管理</h4>
    <h5><p>
      您可以在此管理::[<b>公告內容</b>]--(<b>本共照平台的公告文章</b>)，功能有：<b>新增、修改、刪除、查看</b>...等
    </p></h5>
  </div>
  <a @click="createPost" href="#">
    <button type="button" class="btn btn-lg btn-primary btn-flat" style="margin-bottom: 15px;"><i class="fa fa-file-text" aria-hidden="true"></i> 新增</button>
  </a>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">所有文章</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-striped">
            <tr>
              <th>ID</th>
              <th>標題</th>
              <th>描述</th>
              <th>圖像</th>
              <th>類別</th>
              <th>功能</th>
              <th>狀態</th>
            </tr>
            <tr v-for="post in posts">
              <td>{{ post.hashid }}</td>
              <td class="col-md-3">{{ post.title }}</td>
              <td class="col-md-3">{{ post.description }}</td>
              <td class="col-md-2">
                <div class="box-body">
                  <img class="img-responsive img-thumbnail" :src="post.image || 'http://i.imgur.com/F12Dfl0.jpg'" alt="Photo">
                </div>
              </td>
              <td class="col-md-1">
                <div v-for="category in post.categories.data">
                 <b class="badge bg-aqua">{{ category.name }}</b>
               </div>
              </td>
              <td class="col-md-3">
                <div class="btn-group">
                  <!-- a href="/blog/{{post.slug}}" target="blank" class="btn btn-success" role="button">看</a -->
                  <button v-link="{ name: 'showpost', params: {hashid: post.hashid} }" type="button" class="btn btn-success">看</button>
                  <button v-link="{ name: 'editpost', params: {hashid: post.hashid}}" type="button" class="btn btn-info">改</button>
                  <button type="button" class="btn btn-danger" @click="deletePost(post)">刪</button>
                </div>
              </td>
              <td>
                <b v-if="post.status == 'approved'" class="label label-success">{{ post.status }}</b>
                <b v-if="post.status == 'postponed'" class="label label-info">{{ post.status }}</b>
                <b v-if="post.status == 'pending'" class="label label-warning">{{ post.status }}</b>
                <b v-if="post.status == 'rejected'" class="label label-danger">{{ post.status }}</b>
              </td>
            </tr>
          </table>
          <div>
            <!-- v-paginator :resource.sync="posts" :resource_url="resource_url" :options="options"></v-paginator -->
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
</section>
</template>

<script>
  var csrf_token = $('meta[name="csrf-token"]').attr('content')

  import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'
  import Multiselect from 'vue-multiselect/lib/vue-multiselect.min.js'
  import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

  export default {
    components: {
      VPaginator: VuePaginator,
      Multiselect
    },
    created () {
      this.categoryId = this.$route.params.hashid
    },
    ready () {
     /* this.fetchCategories() */
    },
    data () {
      return {
        token: csrf_token,
        options: {
          remote_data: 'data',
          remote_current_page: 'meta.pagination.current_page',
          remote_last_page: 'meta.pagination.total_pages',
          remote_next_page_url: 'meta.pagination.links.next',
          remote_prev_page_url: 'meta.pagination.links.previous'
        },
        posts: [],
        /* options2: [], */
        categoryId: '',
      }
    },
    methods: {
      fetchCategories () {
        this.$http({url: '/api/categories', method: 'GET'})
          .then(function (response) {
            this.$set('options2', response.data.data)
          })
          .catch(function(response) {
            console.log(response.data)
            if (response.data.data == "Unauthorized." && response.data.status == 401) {
              alert('Auto Logout after idle for 20 mins!!')
//              this.$http({url: 'logout', method: 'GET'}).then(function (response) {
                window.location.assign('/auth/logout')
//              })
            }
          })
      },
      deletePost (post) {
        let self = this
        swal({
          title: '您確定嗎?!',
          text: '您將無法救回此文章!!',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '是的, 刪除它!!',
          cancelButtonText: '不, 保持原狀',
        }).then(function() {
          self.posts.$remove(post)
          self.$http.delete('/api/posts/' + post.hashid, post).then(function (response) {
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
      },
      createPost () {
        this.$http({url: '/api/posts', method: 'POST'}).then(function (response) {
          show_stack_info('已新增', response)
          this.$router.go('/posts/'  + response.data.hashid + '/edit')
        }, function (response){
          show_stack_error('新增文章失敗!!', response)
        })
        this.$router.go('/')
      },
    },
    computed: {
      resource_url: function () {
        if (this.$route.path == '/posts/categories/' + this.categoryId) {
          return '/api/categories/' + this.categoryId + '/posts'
        } else {
          return '/api/posts?include=categories'
        }
      }
    },
  }
</script>
