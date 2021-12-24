<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Сообщения</h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr :id="message.id" v-for="message in messages.data" :key="message.id"
                                    :class="{ 'not-viewed' : message.status === 'new'}">
                                    <td>{{ message.id }}</td>
                                    <td>{{ message.name }}</td>
                                    <td>{{ message.email }}</td>
                                    <td>
                                        <a @click="editModal(message)">
                                            <button type="button" class="btn btn-primary">Прочитать</button>
                                        </a>
                                        /
                                        <a @click="deleteAuction(message.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="messages" @pagination-change-page="getResults"></pagination>
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
                            <h5 class="modal-title" v-show="editmode">Детальная информация</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form>
                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Имя</label>
                                    <input readonly v-model="form.name" type="text" name="name"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input readonly v-model="form.email" type="text" name="email"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Сообщение</label>
                                    <input readonly :value="form.text" type="text" name="text"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('text') }">
                                    <has-error :form="form" field="text"></has-error>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style>
.not-viewed {
    background-color: #ddd;
    font-weight: bold;
}
</style>
<script>
import VueTagsInput from '@johmun/vue-tags-input';

export default {
    components: {
        VueTagsInput,
    },
    data() {
        return {
            editmode: false,
            messages: {},
            form: new Form({
                id: '',
                name: '',
                email: '',
                text: '',
                status: '',
            }),

            autocompleteItems: [],
        }
    },
    methods: {

        getResults(page = 1) {

            this.$Progress.start();

            axios.get('/admin/api/admin/message?page=' + page).then(({data}) => (this.messages = data.data));

            this.$Progress.finish();
        },
        loadNotifications() {

            // if(this.$gate.isAdmin()){
            axios.get("/admin/api/admin/message").then(({data}) => (this.messages = data.data));
            // }
        },

        editModal(notification) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(notification);
            this.$Progress.start();
            this.form.put('/admin/api/admin/message/' + this.form.id)
            $('tr#' + this.form.id).removeClass('not-viewed');
            this.$Progress.finish();
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        deleteNotification(id) {
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
                    this.form.delete('/admin/api/admin/message/' + id).then(() => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        // Fire.$emit('AfterCreate');
                        this.loadAuctions();
                    }).catch((data) => {
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

        this.loadNotifications();

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
