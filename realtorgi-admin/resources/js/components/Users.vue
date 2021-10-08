<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">User List</h3>

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
                      <th>Тип</th>
                      <th>Тип лица</th>
                      <th>Имя</th>
                      <th>Email</th>
                      <th>Email Проверен?</th>
                      <th>Создан</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="user in users.data" :key="user.id">

                      <td>{{user.id}}</td>
                      <td class="text-capitalize">{{user.type}}</td>
                      <td v-if="user.type_user == 'phys'" class="text-capitalize">Физ. лицо</td>
                      <td v-if="user.type_user == 'jur'" class="text-capitalize">Юр. лицо</td>
                      <td v-if="user.type_user == 'ip'" class="text-capitalize">ИП</td>
                      <td v-if="user.type_user == '' || user.type_user == null" class="text-capitalize">Не указан</td>
                      <td class="text-capitalize">{{user.first_name}} {{user.last_name}}</td>
                      <td>{{user.email}}</td>
                      <td :inner-html.prop="user.email_verified_at | yesno"></td>
                      <td>{{ getDateFormatter(user.created_at) }}</td>

                      <td>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New User</h5>
                    <h5 class="modal-title" v-show="editmode">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Тип лица</label>
                            <select v-on:change="changeType" name="type_user" v-model="form.type_user" id="type_user" class="form-control" :class="{ 'is-invalid': form.errors.has('type_user') }">
                                <option value="">Выберите тип лица</option>
                                <option value="phys">Физ. лицо</option>
                                <option value="jur">Юр. лицо</option>
                                <option value="ip">ИП</option>
                            </select>
                            <has-error :form="form" field="type_user"></has-error>
                        </div>
                        <!--Start Физ лицо-->
                        <div id="phys" class="user_info_form">
                            <div class="form-group">
                                <label>Имя</label>
                                <input v-model="form.first_name" type="text" name="first_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                <has-error :form="form" field="first_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Фамилия</label>
                                <input v-model="form.last_name" type="text" name="last_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>ФИО</label>
                                <input v-model="form.full_name" type="text" name="full_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('full_name') }">
                                <has-error :form="form" field="full_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="false">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Логин</label>
                                <input readonly v-model="form.login" type="text" name="login"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('login') }">
                                <has-error :form="form" field="login"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Серия и номер пасспорта</label>
                                <input v-model="form.passport_number" type="text" name="passport_number"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_number') }">
                                <has-error :form="form" field="passport_number"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Кем выдан пасспорт</label>
                                <input v-model="form.passport_issuer" type="text" name="passport_issuer"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_issuer') }">
                                <has-error :form="form" field="passport_issuer"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Дата выдачи паспорта</label>
                                <input type="text" v-model="form.passportDate" name="passportDate" :formatter="getDate(form.passportDate)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passportDate') }">
                                <has-error :form="form" field="passportDate"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Личный номер пасспорта</label>
                                <input v-model="form.passport_personal" type="text" name="passport_personal"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_personal') }">
                                <has-error :form="form" field="passport_personal"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Адрес прописки</label>
                                <input v-model="form.registration_address" type="text" name="registration_address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('registration_address') }">
                                <has-error :form="form" field="registration_address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Адрес проживания</label>
                                <input v-model="form.residence_address" type="text" name="residence_address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('residence_address') }">
                                <has-error :form="form" field="residence_address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="text" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Номер телефона</label>
                                <input v-model="form.phone" type="text" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                            </div>
                        </div>
                        <!--End Физ лицо-->

                        <!--Start Юр лицо-->
                        <div id="jur" class="user_info_form">
                            <div class="form-group">
                                <label>Имя</label>
                                <input v-model="form.first_name" type="text" name="first_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                <has-error :form="form" field="first_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Фамилия</label>
                                <input v-model="form.last_name" type="text" name="last_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="false">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Логин</label>
                                <input readonly v-model="form.login" type="text" name="login"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('login') }">
                                <has-error :form="form" field="login"></has-error>
                            </div>

                            <div class="form-group">
                                <label>УНП</label>
                                <input v-model="form.inn" type="text" name="inn"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('inn') }">
                                <has-error :form="form" field="inn"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Короткое название организации</label>
                                <input v-model="form.short_name" type="text" name="short_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('short_name') }">
                                <has-error :form="form" field="short_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Полное название организации</label>
                                <input v-model="form.full_name" type="text" name="full_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('full_name') }">
                                <has-error :form="form" field="full_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Дата регистрации ЕГР</label>
                                <input type="text" v-model="form.egr_date" name="egr_date" :formatter="getDate(form.egr_date)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('egr_date') }">
                                <has-error :form="form" field="egr_date"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Данные по руководителю (Должность и ФИО полностью)</label>
                                <input v-model="form.manager_data" type="text" name="manager_data"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('manager_data') }">
                                <has-error :form="form" field="manager_data"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Юридический адрес</label>
                                <input v-model="form.place_of_residence" type="text" name="place_of_residence"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('place_of_residence') }">
                                <has-error :form="form" field="place_of_residence"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Почтовый адрес (Почтовый индекс, город, улица, дом), если не совпадает с юридическим адресом</label>
                                <input v-model="form.registration_address" type="text" name="registration_address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('registration_address') }">
                                <has-error :form="form" field="registration_address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Банковские реквизиты: банк, код банка (bic), УНП банка, р/счёт (iban)</label>
                                <input v-model="form.bank_details" type="text" name="bank_details"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_details') }">
                                <has-error :form="form" field="bank_details"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Фио - Лицо, участвующее от имени юридического лица</label>
                                <input v-model="form.legal_entity" type="text" name="legal_entity"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('legal_entity') }">
                                <has-error :form="form" field="legal_entity"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Серия и номер пасспорта</label>
                                <input v-model="form.passport_number" type="text" name="passport_number"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_number') }">
                                <has-error :form="form" field="passport_number"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Кем выдан пасспорт</label>
                                <input v-model="form.passport_issuer" type="text" name="passport_issuer"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_issuer') }">
                                <has-error :form="form" field="passport_issuer"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Дата выдачи паспорта</label>
                                <input type="text" v-model="form.passportDate" name="passportDate" :formatter="getDate(form.passportDate)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passportDate') }">
                                <has-error :form="form" field="passportDate"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Личный номер пасспорта</label>
                                <input v-model="form.passport_personal" type="text" name="passport_personal"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_personal') }">
                                <has-error :form="form" field="passport_personal"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="text" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Номер телефона</label>
                                <input v-model="form.phone" type="text" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                            </div>
                        </div>
                        <!--End Юр лицо-->

                        <!--Start ИП-->
                        <div id="ip" class="user_info_form">
                            <div class="form-group">
                                <label>Имя</label>
                                <input v-model="form.first_name" type="text" name="first_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                <has-error :form="form" field="first_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Фамилия</label>
                                <input v-model="form.last_name" type="text" name="last_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>ФИО</label>
                                <input v-model="form.full_name" type="text" name="full_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('full_name') }">
                                <has-error :form="form" field="full_name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="false">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                                <label>УНП</label>
                                <input v-model="form.inn" type="text" name="inn"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('inn') }">
                                <has-error :form="form" field="inn"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Дата регистрации ЕГР</label>
                                <input type="text" v-model="form.egr_date" name="egr_date" :formatter="getDate(form.egr_date)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('egr_date') }">
                                <has-error :form="form" field="egr_date"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Логин</label>
                                <input readonly v-model="form.login" type="text" name="login"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('login') }">
                                <has-error :form="form" field="login"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Серия и номер пасспорта</label>
                                <input v-model="form.passport_number" type="text" name="passport_number"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_number') }">
                                <has-error :form="form" field="passport_number"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Кем выдан пасспорт</label>
                                <input v-model="form.passport_issuer" type="text" name="passport_issuer"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_issuer') }">
                                <has-error :form="form" field="passport_issuer"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Дата выдачи паспорта</label>
                                <input type="text" v-model="form.passportDate" name="passportDate" :formatter="getDate(form.passportDate)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passportDate') }">
                                <has-error :form="form" field="passportDate"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Личный номер пасспорта</label>
                                <input v-model="form.passport_personal" type="text" name="passport_personal"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('passport_personal') }">
                                <has-error :form="form" field="passport_personal"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Адрес прописки</label>
                                <input v-model="form.registration_address" type="text" name="registration_address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('registration_address') }">
                                <has-error :form="form" field="registration_address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Адрес проживания</label>
                                <input v-model="form.residence_address" type="text" name="residence_address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('residence_address') }">
                                <has-error :form="form" field="residence_address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Банковские реквизиты: банк, код банка (bic), УНП банка, р/счёт (iban)</label>
                                <input v-model="form.bank_details" type="text" name="bank_details"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_details') }">
                                <has-error :form="form" field="bank_details"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="text" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Номер телефона</label>
                                <input v-model="form.phone" type="text" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                            </div>
                        </div>
                        <!--End ИП-->

                        <div class="form-group">
                            <label>Изображения / Файлы</label>
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

                        <div class="form-group" v-if="form.files">
                            <div v-if="form.files" style="cursor: pointer;">
                                <div :id="'file_'+im.id" v-for="(im, index) in form.files" :key="index" style="flex: 1 2 25%;">
                                    <a :href="im.path" target="_blank">{{im.name}}</a>
                                    <span v-on:click="deleteFile(im.id)" style="color: red">Удалить</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Роль</label>
                            <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                <option value="">Select User Role</option>
                                <option value="admin">Администратор</option>
                                <option value="user">Пользователь</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
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
    export default {
        data () {
            return {
                editmode: false,
                users : {},
                form: new Form({
                    id : '',
                    type : '',
                    first_name: '',
                    last_name: '',
                    full_name: '',
                    login: '',
                    email: '',
                    password: '',
                    type_user: '',
                    passport_number: '',
                    passport_issuer: '',
                    passportDate: '',
                    passport_personal: '',
                    registration_address: '',
                    residence_address: '',
                    phone: '',
                    inn: '',
                    short_name: '',
                    egr_date: '',
                    manager_data: '',
                    place_of_residence: '',
                    bank_details: '',
                    legal_entity: '',
                    email_verified_at: '',
                    images: '',
                    files: '',
                    gallery: '',
                })
            }
        },
        methods: {

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

            handleImagesUpload(e){
                this.files = e.target.files;
            },

            getDate(datetime) {
                let date = new Date(datetime).toJSON();
                return date;
            },

            getDateFormatter(datetime){
                let date = new Date(datetime).toJSON().slice(0,10).replace(/-/g,'-');
                return date;
            },

            getResults(page = 1) {

                  this.$Progress.start();

                  axios.get('api/user?page=' + page).then(({ data }) => (this.users = data.data));

                  this.$Progress.finish();
            },
            updateUser(){
                this.$Progress.start();

                this.form.put('api/user/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });

                    if (typeof this.files !== 'undefined') {
                        // UPLOAD IMAGES
                        let formData = new FormData();
                        for( var i = 0; i < this.files.length; i++ ){
                            let file = this.files[i];
                            formData.append('images[' + i + ']', file);
                        }
                        console.log(formData);
                        axios.post( '/api/gallery/uploadFiles/'+this.form.id,
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

                    this.loadUsers();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);

                $('.user_info_form').css('display', 'none');
                if(user.type_user == 'phys'){
                    $('#phys').css('display', 'block');
                } else if (user.type_user == 'jur') {
                    $('#jur').css('display', 'block');
                } else if (user.type_user == 'ip'){
                    $('#ip').css('display', 'block');
                } else {
                    $('.user_info_form').css('display', 'none');
                }

            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');

                $('.user_info_form').css('display', 'none');
            },
            deleteUser(id){
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
                                this.form.delete('api/user/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadUsers();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
          loadUsers(){
            this.$Progress.start();

            if(this.$gate.isAdmin()){
              axios.get("api/user").then(({ data }) => (this.users = data.data));
            }

            this.$Progress.finish();
          },

          createUser(){

              this.form.post('api/user')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                if (typeof this.files !== 'undefined') {
                    // UPLOAD IMAGES
                    let formData = new FormData();
                    for( var i = 0; i < this.files.length; i++ ){
                        let file = this.files[i];
                        formData.append('images[' + i + ']', file);
                    }
                    console.log(formData);
                    axios.post( '/api/gallery/uploadFiles/'+response.data.data.id,
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
                  this.loadUsers();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },

          deleteImage(id){
                var alertSure = confirm('Вы уверены что хотите удалить изображение?');

                if(alertSure){
                    axios.post( '/api/gallery/destroyImages/'+id,
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
                    axios.post( '/api/gallery/destroyFiles/'+id,
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
            console.log('User Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadUsers();
            this.$Progress.finish();
        }
    }
</script>
