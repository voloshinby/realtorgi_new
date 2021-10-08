<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Список аукционов</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Add New
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Дата начала - конец</th>
                      <th>Начало ставок - Конец ставок</th>
                      <th>Продавец</th>
                      <th>Шаг</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="auction in auctions.data" :key="auction.id">
                      <td>{{auction.id}}</td>
                        <td>
                            {{auction.name}}
                        </td>
                      <td>{{ formatDate(auction.starts_at) }} - {{ formatDate(auction.ends_at) }}</td>
                      <td>{{ formatDate(auction.start_selling) }} - {{ formatDate(auction.end_selling) }}</td>
                      <td v-if="auction.seller_id != 0">{{auction.user_seller.full_name}}</td>
                      <td v-else>{{ auction.seller_custom }}</td>
                      <td v-if="auction.step == '1'">От начальной цены</td>
                      <td v-if="auction.step == '2'">От текущей цены</td>
                      <td>
                        <a @click="editModal(auction)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a @click="deleteAuction(auction.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="auctions" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Auction</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Auction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateAuction() : createAuction()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Вид торгов</label>
                            <select class="form-control form-type" v-model="form.type" v-on:change="changeType">
                                <option value="">Выберите тип торгов</option>
                                <option :selected="form.type == 'econom'" value="econom">Торги в результате экономической несостоятельности</option>
                                <option :selected="form.type == 'classic'" value="classic">Классические электронные торги</option>
                            </select>
                            <!-- <has-error :form="form" field="type"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Область</label>
                            <select class="form-control" v-model="form.city_id">
                                <option value="">Выберите область</option>
                                <option
                                    v-for="(ct,index) in cities" :key="index"
                                    :value="index"
                                    :selected="index == form.city_id">{{ ct }}</option>
                            </select>
                            <!-- <has-error :form="form" field="city_id"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Название</label>
                            <input v-model="form.name" type="text" name="name" placeholder="Название"
                                class="form-control form-name">
                            <!-- <has-error :form="form" field="name"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Номер аукциона</label>
                            <input v-model="form.auction_number" type="text" name="auction_number" placeholder="Номер аукциона"
                                class="form-control form-number">
                            <!-- <has-error :form="form" field="auction_number"></has-error> -->
                        </div>

                        <div class="form-group" v-if="editmode">
                            <label>Организатор аукциона</label>
                            <ckeditor v-model="form.organizer_requisites" name="organizer_requisites"></ckeditor>
                            <!-- <has-error :form="form" field="organizer_requisites"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Дата приема заявок</label>
                            <input v-model="form.starts_at" type="datetime-local" name="starts_at"
                                class="form-control">
                            <!-- <has-error :form="form" field="starts_at"></has-error> -->
                            <span v-if="formatDate(form.starts_at) != '1970:01:1 3:0:0' && formatDate(form.starts_at) != 'NaN:NaN:NaN NaN:NaN:NaN'"><b style="color: green;">Текущее значение: {{ formatDate(form.starts_at) }}</b></span>
                        </div>

                        <div class="form-group">
                            <label>Дата и время окончания приема заявок</label>
                            <input type="datetime-local" v-model="form.ends_at" name="ends_at"
                                class="form-control">
                            <!-- <has-error :form="form" field="ends_at"></has-error> -->
                            <span v-if="formatDate(form.ends_at) != '1970:01:1 3:0:0' && formatDate(form.starts_at) != 'NaN:NaN:NaN NaN:NaN:NaN'"><b style="color: green;">Текущее значение: {{ formatDate(form.ends_at) }}</b></span>
                        </div>

                        <div class="form-group">
                            <label>Дата и время начала торгов</label>
                            <input v-model="form.start_selling" type="datetime-local" name="start_selling"
                                class="form-control">
                            <!-- <has-error :form="form" field="start_selling"></has-error> -->
                            <span v-if="formatDate(form.start_selling) != '1970:01:1 3:0:0' && formatDate(form.starts_at) != 'NaN:NaN:NaN NaN:NaN:NaN'"><b style="color: green;">Текущее значение: {{ formatDate(form.start_selling) }}</b></span>
                        </div>

                        <div class="form-group">
                            <label>Дата и время окончания торгов</label>
                            <input type="datetime-local" v-model="form.end_selling" name="end_selling"
                                class="form-control">
                            <!-- <has-error :form="form" field="end_selling"></has-error> -->
                            <span v-if="formatDate(form.end_selling) != '1970:01:1 3:0:0' && formatDate(form.starts_at) != 'NaN:NaN:NaN NaN:NaN:NaN'"><b style="color: green;">Текущее значение: {{ formatDate(form.end_selling) }}</b></span>
                        </div>

                        <div class="form-group">
                            <label>Собственник (список)</label>
                            <select class="form-control form-seller_id" v-model="form.seller_id">
                                <option value="">Выберите собственника</option>
                                <option
                                    v-for="(us,index) in users" :key="index"
                                    :value="us.id"
                                    :selected="us.id == form.seller_id">{{ us.first_name+' '+ us.last_name}}</option>
                            </select>
                            <!-- <has-error :form="form" field="seller_id"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Собственник (новый)</label>
                            <input type="text" v-model="form.seller_custom" name="seller_custom" placeholder="Имя Фамилия собственника (необязательно)"
                                class="form-control form-seller_custom">
                            <!-- <has-error :form="form" field="seller_custom"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Контактный номер продавца</label>
                            <input type="text" v-model="form.seller_phone" name="seller_phone" placeholder="Номер продавца (Пример: +375291234567)"
                                class="form-control">
                            <!-- <has-error :form="form" field="seller_phone"></has-error> -->
                        </div>

                        <div class="form-group" v-if="editmode">
                            <label>Реквизиты для внесения задатка</label>
                            <ckeditor v-model="form.requisites" name="requisites"></ckeditor>
                            <!-- <has-error :form="form" field="requisites"></has-error> -->
                        </div>

                        <div class="form-group" v-if="form.type == 'econom'">
                            <label>Антикризисный управляющий</label>
                            <input type="text" v-model="form.anticrisis_manager" name="anticrisis_manager" placeholder="Антикризисный управляющий"
                                class="form-control">
                            <!-- <has-error :form="form" field="anticrisis_manager"></has-error> -->
                        </div>

                        <div class="form-group" v-if="form.type == 'econom'">
                            <label>Номер управляющего</label>
                            <input type="text" v-model="form.anticrisis_manager_phone" name="anticrisis_manager_phone" placeholder="Номер управляющего (Пример: +375291234567)"
                                class="form-control">
                            <!-- <has-error :form="form" field="anticrisis_manager_phone"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Как посмотреть имущество</label>
                            <ckeditor v-model="form.how_to_view_the_property" name="how_to_view_the_property"></ckeditor>
                            <!-- <has-error :form="form" field="how_to_view_the_property"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Контактное лицо</label>
                            <input type="text" v-model="form.contact_person" name="contact_person" placeholder="Контактное лицо"
                                class="form-control">
                            <!-- <has-error :form="form" field="contact_person"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Возмещение затрат по аукциону</label>
                            <input type="text" v-model="form.compensation" name="compensation" placeholder="Возмещение затрат по аукциону"
                                class="form-control">
                            <!-- <has-error :form="form" field="compensation"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Шаг торгов</label>
                            <select class="form-control form-step" v-model="form.step">
                                <option value="">Выберите шаг</option>
                                <option :selected="form.step == '1'" value="1">От начальной цены</option>
                                <option :selected="form.step == '2'" value="2">От текущей цены</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Дополнительная информация</label>
                            <ckeditor v-model="form.additional_info" name="additional_info"></ckeditor>
                            <!-- <has-error :form="form" field="additional_info"></has-error> -->
                        </div>

                        <div class="form-group">
                            <label>Изображения / Файлы</label>
                            <input class="form-control"
                                type="file" name="images" multiple v-on:change="handleImagesUpload" />
                            <!-- <has-error :form="form" field="images"></has-error> -->
                        </div>

                        <div class="form-group">
                            <div v-if="form.gallery" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; cursor: pointer;">
                                <div :id="'image_'+im.id" v-on:click="deleteImage(im.id)" v-for="(im, index) in form.gallery" :key="index" style="flex: 1 2 25%;">
                                    <img style="width: 100%" :src="im.path" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-if="form.files">
                            <div v-if="form.files" style="cursor: pointer;">
                                <div :id="'file_'+im.id" v-for="(im, index) in form.files" :key="index" style="flex: 1 2 25%;">
                                    <a :href="im.path" target="_blank">{{im.name}}</a>
                                    <span v-on:click="deleteFile(im.id)" style="color: red">Удалить</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                auctions : {},
                form: new Form({
                    /* MAIN */
                    id : '',
                    type: '',
                    name: '',
                    auction_number: '',
                    organizer_requisites: '',
                    seller_id: '',
                    seller_phone: '',
                    requisites: '',
                    anticrisis_manager: '',
                    anticrisis_manager_phone: '',
                    how_to_view_the_property: '',
                    contact_person: '',
                    compensation: '',
                    step: '',
                    additional_info: '',
                    starts_at: '',
                    ends_at: '',
                    start_selling: '',
                    end_selling: '',
                    deleted_at: '',
                    seller_custom: '',
                    city_id: '',
                    images: '',
                    /* ADDITIONAL */
                    user: '',
                    gallery: '',
                    files: '',
                }),
                users: [],
                cities: [],
                autocompleteItems: [],
            }
        },
        methods: {
            handleImagesUpload(e){
                this.files = e.target.files;
            },

            changeType(e){
                var value = e.target.value;
                $('.user_info_form').css('display', 'none');
                if(value == 'phys'){
                    $('#phys').css('display', 'block');
                } else if (value == 'jur') {
                    $('#jur').css('display', 'block');
                } else if (value == 'ip'){
                    $('#ip').css('display', 'block');
                } else {
                    $('.user_info_form').css('display', 'none');
                }
            },

            getResults(page = 1) {

              this.$Progress.start();

              axios.get('/api/auction?page=' + page).then(({ data }) => (this.auctions = data.data));

              this.$Progress.finish();
            },
            loadAuctions(){
            // if(this.$gate.isAdmin()){
              axios.get("/api/auction").then(({ data }) => (this.auctions = data.data));
            // }
            },

          formatDate(time){
            var timestamp = time; //Get the timestamp
            var date = new Date(timestamp * 1000); //If the timestamp is 10 digits, *1000 is required. If the timestamp is 13 digits, it does not need to be multiplied by 1000
            var Y = date.getFullYear() + ':';
            var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + ':';
            var D = date.getDate() + ' ';
            var h = date.getHours() + ':';
            var m = date.getMinutes() + ':';
            var s = date.getSeconds();
            return Y+M+D+h+m+s;
          },

          loadUsers(){
            //   axios.get("/api/user/list").then(({ data }) => (this.users = data.data));
            //   axios.get("/api/user/list").then(({ data }) => (console.log(data.data)));
            //   axios.get("/api/user/all/list").then(({ data }) => (console.log(data.data)));
              axios.get("/api/user/all/list").then(({ data }) => (this.users = data.data));

          },
          loadCategories(){
              axios.get("/api/category/list").then(({ data }) => (this.categories = data.data));
          },
          loadCities(){
              axios.get("/api/city/list").then(({ data }) => (this.cities = data.data));
          },

          editModal(auction){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(auction);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createAuction(){
              var error = false;

              if (!this.form.name) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Введите название'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-name').addClass('is-invalid');
              } else if (!this.form.type){
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите тип торгов'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-type').addClass('is-invalid');
              } else if (!this.form.seller_id && !this.form.seller_custom) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите собственника из списка или сами напишите'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-seller_id').addClass('is-invalid');
                  $('.form-seller_custom').addClass('is-invalid');
              } else if (!this.form.step) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите шаг торгов'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-step').addClass('is-invalid');
              }
              if(!error){
                this.$Progress.start();
                this.form.post('/api/auction')
                .then((data)=>{
                    if(data.data.success){
                    $('#addNew').modal('hide');

                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });

                    if (typeof this.files !== 'undefined') {
                        // UPLOAD IMAGES
                        let formData = new FormData();
                        for( var i = 0; i < this.files.length; i++ ){
                            let file = this.files[i];
                            formData.append('images[' + i + ']', file);
                        }
                        axios.post( '/api/auction/uploadImages/'+data.data.data.id,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        ).then(function(){
                            console.log('SUCCESS!!');
                        })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });
                    }

                    this.$Progress.finish();
                    this.loadAuctions();

                    } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });

                    this.$Progress.failed();
                    }
                })
                .catch(()=>{

                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })
              }
          },
          updateAuction(){
              var error = false;

              if (!this.form.name) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Введите название'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-name').addClass('is-invalid');
              } else if (!this.form.type){
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите тип торгов'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-type').addClass('is-invalid');
              } else if (!this.form.seller_id && !this.form.seller_custom) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите собственника из списка или сами напишите'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-seller_id').addClass('is-invalid');
                  $('.form-seller_custom').addClass('is-invalid');
              } else if (!this.form.step) {
                  error = true;
                  Toast.fire({
                      icon: 'error',
                      title: 'Выберите шаг торгов'
                  });
                  $('.form-control').removeClass('is-invalid');
                  $('.form-step').addClass('is-invalid');
              }
              if(!error){
                this.$Progress.start();
                this.form.put('/api/auction/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });

                    let formData = new FormData();
                    if (typeof this.files !== 'undefined') {
                        // UPLOAD FILES
                        for( var i = 0; i < this.files.length; i++ ){
                            let file = this.files[i];
                            formData.append('images[' + i + ']', file);
                        }
                        axios.post( '/api/auction/uploadImages/'+this.form.id,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        ).then(function(){
                            console.log('SUCCESS!!');
                        })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });
                    }

                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadAuctions();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
              }

          },
          deleteAuction(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('/api/auction/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadAuctions();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },

          deleteImage(id){
                var alertSure = confirm('Вы уверены что хотите удалить изображение?');

                if(alertSure){
                    axios.post( '/api/auction/destroyImages/'+id,
                        ).then(function(){
                            console.log('SUCCESS!!');
                            $('#image_'+id).remove();
                            this.loadLots();
                        })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });
                }
            },

            deleteFile(id){
                var alertSure = confirm('Вы уверены что хотите удалить файл?');

                if(alertSure){
                    axios.post( '/api/auction/destroyFiles/'+id,
                        ).then(function(){
                            console.log('SUCCESS!!');
                            $('#file_'+id).remove();
                            this.loadLots();
                        })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });
                }
            },

        },
        mounted() {
        },
        created() {
            this.$Progress.start();

            this.loadAuctions();
            this.loadUsers();
            this.loadCategories();
            this.loadCities();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          filteredItems() {
            return this.autocompleteItems.filter(i => {
              return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
          },
        },
    }


</script>
