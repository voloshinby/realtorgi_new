<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Список ставок</h3>

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
                      <th>Пользователь</th>
                      <th>Ставка</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="bet in bets.data" :key="bet.id">
                      <td>{{bet.id}}</td>
                        <td>
                            {{bet.lot.name}}
                        </td>
                      <td v-if="bet.user.full_name != '' && bet.user.full_name != null">{{ bet.user.full_name }}</td>
                      <td v-else>{{ bet.user.first_name }} {{ bet.user.last_name }}</td>
                      <td>{{ bet.bet_amount }}</td>
                      <td>
                        <a @click="editModal(bet)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a @click="deleteBet(bet.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="bets" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Create New Bet</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Bet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateBet() : createBet()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Лот</label>
                            <select class="form-control" v-model="form.lot_id">
                              <option
                                  v-for="(lt,index) in lots" :key="index"
                                  :value="index"
                                  :selected="index == form.lot_id">{{ lt }}</option>
                            </select>
                            <has-error :form="form" field="lot_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label>user_id</label>
                            <input v-model="form.user_id" type="text" name="user_id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('user_id') }">
                            <has-error :form="form" field="user_id"></has-error>
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
                bets : {},
                form: new Form({
                    id : '',
                    user: '',
                    lot: '',
                    lot_id: '',
                    author_id: '',
                    bet_amount: '',
                    updated_at: '',
                    created_at: '',
                }),
                lots: [],

                autocompleteItems: [],
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('api/bet?page=' + page).then(({ data }) => (this.bets = data.data));

              this.$Progress.finish();
          },
          loadBets(){

            // if(this.$gate.isAdmin()){
              axios.get("api/bet").then(({ data }) => (this.bets = data.data));
            // }
          },
          loadUsers(){
              axios.get("/api/user/list").then(({ data }) => (this.users = data.data));
          },
          loadLots(){
            // if(this.$gate.isAdmin()){
              axios.get("/api/lot/list").then(({ data }) => (this.lots = data.data));
            // }
          },
          editModal(bet){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(bet);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createBet(){
              this.$Progress.start();

              this.form.post('api/bet')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadBets();

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
          updateBet(){
              this.$Progress.start();
              this.form.put('api/bet/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadBets();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
          deleteBet(id){
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
                              this.form.delete('api/bet/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadBets();
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

            this.loadBets();
            this.loadUsers();
            this.loadLots();

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
