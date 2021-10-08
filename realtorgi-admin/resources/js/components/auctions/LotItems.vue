<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Список предметов</h3>

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
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="item in items.data" :key="item.id">
                        <td>{{item.id}}</td>
                        <td>
                            {{item.name}}
                        </td>
                        <td>
                            <a @click="editModal(item)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a @click="deleteItem(item.id)">
                                <i class="fa fa-trash red"></i>
                            </a>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="items" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Create New Item</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateItem() : createItem()" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-group">
                            <input type="hidden" name="lot_id" v-model="form.lot_id">
                        </div>

                        <div class="form-group">
                            <label>Наименование</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Описание</label>
                            <ckeditor v-model="form.info" name="info" :class="{ 'is-invalid': form.errors.has('info') }"></ckeditor>
                            <has-error :form="form" field="info"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Изображения</label>
                            <input class="form-control" :class="{ 'is-invalid': form.errors.has('images') }"
                                type="file" name="images" multiple v-on:change="handleImagesUpload" />
                            <has-error :form="form" field="images"></has-error>
                        </div>

                        <div class="form-group">
                            <div v-if="form.gallery" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; cursor: pointer;">
                                <div :id="'image_'+im.id" v-on:click="deleteImage(im.id)" v-for="(im, index) in form.gallery" :key="index" style="flex: 1 2 25%;">
                                    <img style="width: 100%" :src="im.path" alt="">
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
                items : {},
                form: new Form({
                    id : '',
                    name: '',
                    lot_id: this.$route.params.id,
                    lot: '',
                    info: '',
                    images: '',
                    deleted_at: '',

                    // ADDITIONAL
                    gallery: '',
                }),

                autocompleteItems: [],
            }
        },
        methods: {

            handleImagesUpload(e){
                this.files = e.target.files;
            },

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('/api/lot_item/'+this.$route.params.id+'?page=' + page).then(({ data }) => (this.items = data.data));

              this.$Progress.finish();
          },
          loadItems(){
            // if(this.$gate.isAdmin()){
              axios.get("/api/lot_item/"+this.$route.params.id).then(({ data }) => (this.items = data.data));
            // }
          },

          editModal(item){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(item);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createItem(){
              this.$Progress.start();

              this.form.post('/api/lot_item')
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
                        axios.post( '/api/item/uploadImages/'+data.data.data.id,
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
                  this.loadItems();

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
          },
          updateItem(){
              this.$Progress.start();

              this.form.put('/api/lot_item/'+this.form.id)
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
                        axios.post( '/api/item/uploadImages/'+this.form.id,
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
                  this.loadItem();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
            deleteItem(id){
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
                              this.form.delete('/api/lot_item/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadItems();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
            },

            deleteImage(id){
                var alertSure = confirm('Вы уверены что хотите удалить изображение?');

                if(alertSure){
                    axios.post( '/api/item/destroyImages/'+id,
                        ).then(function(){
                            console.log('SUCCESS!!');
                            $('#image_'+id).remove();
                            this.loadItems();
                        })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });
                }
            },

            deleteFile(id){
                var alertSure = confirm('Вы уверены что хотите удалить файл?');

                if(alertSure){
                    axios.post( '/api/item/destroyFiles/'+id,
                        ).then(function(){
                            console.log('SUCCESS!!');
                            $('#file_'+id).remove();
                            this.loadItems();
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

            this.loadItems();

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
