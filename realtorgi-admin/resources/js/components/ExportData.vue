<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Category List</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" v-on:click="downloadArchive()">
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
                      <th>Name</th>
                      <!-- <th>Photo</th> -->
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="ed in export_data.data" :key="ed.id">

                      <td>{{ed.id}}</td>
                      <td>{{ ed.name }}</td>
                      <td>
                        <a
                            :data-name="ed.name.replace(' ', '-') + '-' + ed.id +'.zip'"
                            :id="'archive-download-' + ed.id"
                            @click="downloadArchive(ed.id, '/uploads/archives/' + ed.name.replace(' ', '-') + '-' + ed.id +'.zip')"
                        >
                            <button class="col-md-4 form-control btn btn-primary">
                                Скачать
                            </button>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
              export_data : {},
              // Create a new form instance
              form: new Form({
                  id : '',
                  user_id: '',
                  lot_id: '',
                  user_ip: '',
              })
            }
        },
        methods: {

            loadData(){
                // if(this.$gate.isAdmin()){
                axios.get("api/archive").then(({ data }) => (this.export_data = data.data));
                // }
            },

            downloadArchive(id, link){
                axios.get("/api/archive/download/" + id)
                .then((data)=>{
                    var lk = document.getElementById("archive-download-" + id);
                    console.log(lk);
                    var name = lk.getAttribute('data-name');
                    console.log(name);
                    lk.setAttribute('href', link.replace(' ', ''));
                    lk.setAttribute('download', name);
                    lk.click();
                    this.$Progress.finish();
                    location.reload();
                })
                .catch(()=>{

                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })

            },

        },
        mounted() {

        },
        created() {
            this.$Progress.start();
            this.loadData();
            this.$Progress.finish();
        }
    }
</script>
