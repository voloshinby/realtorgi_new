<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Уведомления</h3>
                            <button type="button" class="btn btn-secondary" @click="readAll()">Прочитать все</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Текст</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr :id="notification.id" v-for="notification in notifications.data"
                                    :key="notification.id"
                                    :class="{ 'not-viewed' : notification.is_view_by_admin === 0}">
                                    <td>{{ notification.id }}</td>
                                    <td v-html="notification.text">{{ notification.text }}</td>
                                    <td>
                                        <a @click="editModal(notification)">
                                            <button type="button" class="btn btn-primary">Прочитать</button>
                                        </a>
                                        /
                                        <a @click="deleteNotification(notification.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="notifications" @pagination-change-page="getResults"></pagination>
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
                                    <label>Текст</label>
                                    <textarea readonly v-model="form.text" type="datetime-local" name="text"
                                              class="form-control" :class="{ 'is-invalid': form.errors.has('text') }">
                            </textarea>
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

.card-header {
    display: flex;
}

.card-title {
    flex: 50%;
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
            notifications: {},
            form: new Form({
                id: '',
                user: '',
                user_id: '',
                text: '',
            }),

            autocompleteItems: [],
        }
    },
    methods: {

        getResults(page = 1) {

            this.$Progress.start();

            axios.get('/admin/api/admin/notification?page=' + page).then(({data}) => (this.notifications = data.data));

            this.$Progress.finish();
        },
        loadNotifications() {

            // if(this.$gate.isAdmin()){
            axios.get("/admin/api/admin/notification").then(({data}) => (this.notifications = data.data));
            // }
        },

        editModal(notification) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(notification);
            this.$Progress.start();
            this.form.put('/admin/api/admin/notification/' + this.form.id)
            $('tr#' + this.form.id).removeClass('not-viewed');
            this.loadNotifications();
            this.$Progress.finish();
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        readAll() {
            Swal.fire({
                title: 'Уверены ли вы?',
                text: "Вы не сможете отменить это!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Да, прочитать все!'
            }).then((result) => {

                // Send request to the server
                if (result.value) {
                    this.form.post('/admin/api/admin/notifications/readAll').then(() => {
                        Swal.fire(
                            'Прочитано!',
                            'Все увдомления были прочитаны',
                            'success'
                        );
                        $('tr').removeClass('not-viewed');
                        // Fire.$emit('AfterCreate');
                    }).catch((data) => {
                        Swal.fire("Failed!", data.message, "warning");
                    });
                }
            })
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
                    this.form.delete('/admin/api/admin/notification/' + id).then(() => {
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
