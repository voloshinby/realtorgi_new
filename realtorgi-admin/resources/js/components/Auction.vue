<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Auctions List</h3>

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
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="auction in auctions.data" :key="auction.id">
                      <td>{{auction.id}}</td>
                        <td>
                            {{auction.name}}
                        </td>
                      <td>{{auction.starts_at}} - {{auction.ends_at}}</td>
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
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
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
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Начало</label>
                            <input v-model="form.starts_at" type="datetime-local" name="starts_at"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('starts_at') }">
                            <has-error :form="form" field="starts_at"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Конец</label>
                            <input type="datetime-local" v-model="form.ends_at" name="ends_at"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('ends_at') }">
                            <has-error :form="form" field="ends_at"></has-error>
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
                    id : '',
                    name: '',
                    starts_at: '',
                    ends_at: '',
                }),

                autocompleteItems: [],
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('/admin/api/admin/auction?page=' + page).then(({ data }) => (this.auctions = data.data));

              this.$Progress.finish();
          },
          loadAuctions(){

            // if(this.$gate.isAdmin()){
              axios.get("/admin/api/admin/auction").then(({ data }) => (this.auctions = data.data));
            // }
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
              this.$Progress.start();

              this.form.post('/admin/api/admin/auction')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
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
          },
          updateAuction(){
              this.$Progress.start();
              this.form.put('/admin/api/admin/auction/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadAuctions();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

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
                              this.form.delete('/admin/api/admin/auction/'+id).then(()=>{
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

        },
        mounted() {

        },
        created() {
            this.$Progress.start();

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
