<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Список лотов</h3>

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
                      <th>Категория</th>
                      <th>Предметы</th>
                      <th>Статус</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="lot in lots.data" :key="lot.id">
                        <td>{{lot.id}}</td>
                        <td>
                            {{lot.name}}
                        </td>
                        <td>{{lot.category.name}}</td>
                        <td><a :href="'lots/'+lot.id">Список предметов</a></td>
                        <td>{{lot.status}}</td>
                        <td>
                            <a @click="editModal(lot)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a @click="deleteLot(lot.id)">
                                <i class="fa fa-trash red"></i>
                            </a>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="lots" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Lot</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Lot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateLot() : createLot()" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Аукцион</label>
                            <select class="form-control" v-model="form.auction_id">
                              <option
                                  v-for="(auc,index) in auctions" :key="index"
                                  :value="index"
                                  :selected="index == form.auction_id">{{ auc }}</option>
                            </select>
                            <has-error :form="form" field="auction_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Категория</label>
                            <select class="form-control" v-model="form.category_id">
                              <option
                                  v-for="(ct,index) in categories" :key="index"
                                  :value="index"
                                  :selected="index == form.category_id">{{ ct }}</option>
                            </select>
                            <has-error :form="form" field="category_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Номер лота</label>
                            <input v-model="form.lot_number" type="text" name="lot_number"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('lot_number') }">
                            <has-error :form="form" field="lot_number"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Наименование лота</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Описание лота</label>
                            <ckeditor v-model="form.info" name="info" :class="{ 'is-invalid': form.errors.has('info') }"></ckeditor>
                            <has-error :form="form" field="info"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Начальная цена продажи лота (в руб.)</label>
                            <input v-model="form.price_start" type="text" name="price_start"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('price_start') }">
                            <has-error :form="form" field="price_start"></has-error>
                        </div>

                        <div class="form-group" v-if="editmode">
                            <label>Минимальная цена лота (в руб.)</label>
                            <input v-model="form.price_min" type="text" name="price_min"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('price_min') }">
                            <has-error :form="form" field="price_min"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Шаг торгов</label>
                            <select class="form-control" v-model="form.step">
                                <option :selected="form.step == '1'" value="1">В рублях</option>
                                <option :selected="form.step == '2'" value="2">В процентах</option>
                            </select>
                            <has-error :form="form" field="step"></has-error>
                        </div>

                        <div class="form-group" v-if="form.step == '1'">
                            <label>Шаг ставки</label>
                            <input v-model="form.price_step" type="text" name="price_step"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('price_step') }">
                            <has-error :form="form" field="price_step"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Сумма задатка</label>
                            <input v-model="form.deposit" type="text" name="deposit"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('deposit') }">
                            <has-error :form="form" field="deposit"></has-error>
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

                        <div class="form-group">
                            <label>Доп. информация</label>
                            <ckeditor v-model="form.additional_info" name="additional_info" :class="{ 'is-invalid': form.errors.has('additional_info') }"></ckeditor>
                            <has-error :form="form" field="additional_info"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Состояние</label>
                            <select class="form-control" v-model="form.status">
                                <option :selected="form.status == 'Черновик'" value="Черновик">Черновик</option>
                                <option :selected="form.status == 'На модерации'" value="На модерации">На модерации</option>
                                <option :selected="form.status == 'Предстоящие'" value="Предстоящие">Предстоящие</option>
                                <option :selected="form.status == 'Отмененные'" value="Отмененные">Отмененные</option>
                                <option :selected="form.status == 'Текущие'" value="Текущие">Текущие</option>
                                <option :selected="form.status == 'Несостоявшиеся'" value="Несостоявшиеся">Несостоявшиеся</option>
                                <option :selected="form.status == 'Состоявшиеся'" value="Состоявшиеся">Состоявшиеся</option>
                                <option :selected="form.status == 'Повторные'" value="Повторные">Повторные</option>
                                <option :selected="form.status == 'Архивные'" value="Архивные">Архивные</option>
                            </select>
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
                lots : {},
                form: new Form({
                    id : '',
                    category: '',
                    name: '',
                    category_id: '',
                    lot_number: '',
                    auction_id: '',
                    auction: '',
                    info: '',
                    price_start: '',
                    price_min: '',
                    step: '',
                    deposit: '',
                    images: '',
                    additional_info: '',
                    status: '',
                    deleted_at: '',
                    price_step: '',

                    // ADDITIONAL
                    gallery: '',
                    files: '',
                }),
                categories: [],
                auctions: [],

                autocompleteItems: [],
            }
        },
        methods: {

        handleImagesUpload(e){
            this.files = e.target.files;
        },

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('/api/lot?page=' + page).then(({ data }) => (this.lots = data.data));

              this.$Progress.finish();
          },
          loadLots(){
            // if(this.$gate.isAdmin()){
              axios.get("/api/lot").then(({ data }) => (this.lots = data.data));
            // }
          },
          loadCategories(){
              axios.get("/api/category/list").then(({ data }) => (this.categories = data.data));
          },
          loadAuctions(){
              axios.get("/api/auction/list").then(({ data }) => (this.auctions = data.data));
          },
          editModal(lot){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(lot);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          updateLot(){
              this.$Progress.start();

              this.form.put('/api/lot/'+this.form.id)
              .then((response) => {

                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });

                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');
                  this.loadLots();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
            deleteLot(id){
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
                              this.form.delete('/api/lot/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadLots();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
            },

            deleteImage(id){
                var alertSure = confirm('Вы уверены что хотите удалить изображение?');

                if(alertSure){
                    axios.post( '/api/lot/destroyImages/'+id,
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
                    axios.post( '/api/lot/destroyFiles/'+id,
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

            this.loadLots();
            this.loadCategories();
            this.loadAuctions();

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
