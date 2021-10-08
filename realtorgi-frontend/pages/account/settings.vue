<template>
  <div class="settings">
    <div class="settings-menu">
      <div class="settings-menu-option" @click="changeOption('email')" :class="{active: (choosenOption === 'changeEmail')}">
        <span>Изменить почту</span>
      </div>
      <div class="settings-menu-option" @click="changeOption('password')" :class="{active: (choosenOption === 'changePassword')}">
        <span>Изменить пароль</span>
      </div>
    </div>
    <form class="settings-wrapper" onsubmit="return false">
      <h1 class="settings-title">
        Настройки
      </h1>
      <div v-if="choosenOption === 'changeEmail'" class="settings-subtitle">
        Изменить почтовый адрес аккаунта
      </div>
      <div v-if="choosenOption === 'changePassword'" class="settings-subtitle">
        Изменить пароль аккаунта
      </div>
      <div class="settings-email" v-if="choosenOption === 'changeEmail'">
        <div class="input-wrapper">
          <label for="email">Введите новую почту:</label>
          <input id="email" type="text" required minlength="7" v-model="email" @change="validateEmail">
        </div>
        <div class="input-wrapper">
          <label for="password">Введите пароль от аккаунта:</label>
          <input id="password" type="password" required minlength="6" v-model="password" @input="validateEmailPassword">
        </div>
      </div>
      <div class="settings-password" v-if="choosenOption === 'changePassword'">
        <div class="input-wrapper">
          <label for="password-old">Введите старый пароль:</label>
          <input id="password-old" type="password" required minlength="6" v-model="passwordOld" @input="validateOldPassword">
        </div>
        <div class="input-wrapper">
          <label for="password">Введите новый пароль:</label>
          <input id="password" type="password" required minlength="6" v-model="password" @input="validatePassword">
        </div>
        <div class="input-wrapper">
          <label for="password-confirm">Подтвердите новый пароль:</label>
          <input id="password-confirm" type="password" required minlength="6" v-model="passwordConfirm" @input="validateConfirmPassword">
        </div>
      </div>
      <div class="error-status">
        <p v-for="error in errorStatus" :key="error.name">
          {{ error.status }}
        </p>
      </div>
      <div class="success-status">
        {{ successStatus }}
      </div>
      <button class="submit" @click="submit">Отправить</button>
    </form>
  </div>
</template>
<script>
import moment from 'moment'

export default ({
  data: () => ({
    choosenOption: 'changeEmail',
    email: '',
    passwordOld: '',
    password: '',
    passwordConfirm: '',
    errorStatus: [],
    successStatus: '',
    moment: moment
  }),

  beforeMount() {
    if (this.$store.state.auth.authorized === false) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Нет доступа.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Только авторизованные пользователи могут просматривать данный раздел.',
      })
      this.$router.push('/auth')
    }
    if (!this.$store.state.auth.userData.active) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Электронный адрес вашей почты не подтвержден.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Вы не можете расматривать страницы личного кабинета, пока не подтвердите адрес электронной почты.',
      })
      this.$router.push('/auth/registration/confirm')
    }
  },
  
  methods: {
    changeOption(type) {
      if (type === 'email') {
        this.choosenOption = 'changeEmail'
        this.errorStatus = []
        this.password = '',
        this.passwordOld = '',
        this.passwordConfirm = ''
        this.successStatus = ''
      }
      if (type === 'password') {
        this.choosenOption = 'changePassword'
        this.errorStatus = []
        this.email = ''
        this.password = ''
        this.successStatus = ''
      }
    },
    validatePassword() {
      var passwordError = {name: 'Password error', status: 'Поле "Новый пароль" содержит недопустимые символы'}
      const checkPasswordError = obj => obj.name === 'Password error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'Email check error';
      });
      if (this.password.match(/^[0-9a-zA-Z]+$/)) {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Password error';
        });
        if (document.querySelector('#password').classList.contains('error')) {
          document.querySelector('#password').classList.remove('error')
        }
      }
      else {
        if (!this.errorStatus.some(checkPasswordError)) {
          this.errorStatus.push(passwordError)
          document.querySelector('#password').classList.add('error')
        }
      }
      if (this.password === '') {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Password error';
        });
        if (document.querySelector('#password').classList.contains('error')) {
          document.querySelector('#password').classList.remove('error')
        }
      }
    },
    validateEmailPassword() {
      var passwordError = {name: 'Password error', status: 'Поле "Пароль" содержит недопустимые символы'}
      const checkPasswordError = obj => obj.name === 'Password error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'Email check error';
      });
      if (this.password.match(/^[0-9a-zA-Z]+$/)) {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Password error';
        });
        if (document.querySelector('#password').classList.contains('error')) {
          document.querySelector('#password').classList.remove('error')
        }
      }
      else {
        if (!this.errorStatus.some(checkPasswordError)) {
          this.errorStatus.push(passwordError)
          document.querySelector('#password').classList.add('error')
        }
      }
      if (this.password === '') {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Password error';
        });
        if (document.querySelector('#password').classList.contains('error')) {
          document.querySelector('#password').classList.remove('error')
        }
      }
    },
    validateOldPassword() {
      var passwordError = {name: 'Old password error', status: 'Поле "Старый пароль" содержит недопустимые символы'}
      const checkPasswordError = obj => obj.name === 'Old password error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'Email check error';
      });
      if (this.passwordOld.match(/^[0-9a-zA-Z]+$/)) {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Old password error';
        });
        if (document.querySelector('#password-old').classList.contains('error')) {
          document.querySelector('#password-old').classList.remove('error')
        }
      }
      else {
        if (!this.errorStatus.some(checkPasswordError)) {
          this.errorStatus.push(passwordError)
          document.querySelector('#password-old').classList.add('error')
        }
      }
      if (this.passwordOld === '') {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Old password error';
        });
        if (document.querySelector('#password-old').classList.contains('error')) {
          document.querySelector('#password-old').classList.remove('error')
        }
      }
    },
    validateConfirmPassword() {
      var passwordError = {name: 'Confirm password error', status: 'Поле "Подтвердите пароль" содержит недопустимые символы'}
      const checkPasswordError = obj => obj.name === 'Confirm password error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'Email check error';
      });
      if (this.passwordConfirm.match(/^[0-9a-zA-Z]+$/)) {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Confirm password error';
        });
        if (document.querySelector('#password-confirm').classList.contains('error')) {
          document.querySelector('#password-confirm').classList.remove('error')
        }
      }
      else {
        if (!this.errorStatus.some(checkPasswordError)) {
          this.errorStatus.push(passwordError)
          document.querySelector('#password-confirm').classList.add('error')
        }
      }
      if (this.passwordConfirm === '') {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Confirm password error';
        });
        if (document.querySelector('#password-confirm').classList.contains('error')) {
          document.querySelector('#password-confirm').classList.remove('error')
        }
      }
    },
    validateEmail() {
      var emailError = {name: 'Email error', status: 'Неверный формат электронной почты'}
      const checkEmailError = obj => obj.name === 'Email error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'Email check error';
      });
      if (this.email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Email error';
        });
        if (document.querySelector('#email').classList.contains('error')) {
          document.querySelector('#email').classList.remove('error')
        }
      }
      else {
        if (!this.errorStatus.some(checkEmailError)) {
          this.errorStatus.push(emailError)
          document.querySelector('#email').classList.add('error')
        }
      }
      if (this.email === '') {
        this.errorStatus = this.errorStatus.filter(function( item ) {
          return item.name !== 'Email error';
        });
        if (document.querySelector('#email').classList.contains('error')) {
          document.querySelector('#email').classList.remove('error')
        }
      }
    },
    submit() {
      if (this.choosenOption === 'changeEmail') {
        this.$axios.$post('https://realtorgi.by/admin/api/admin/user/login', {
          email: this.$store.state.auth.userData.email,
          password: this.password
        }).then((response) => {
          if (response.message === 'Неправильный пароль') {
            var emailCheckError = {name: 'Email check error', status: 'Неверный пароль'}
            this.errorStatus.push(emailCheckError)
            document.querySelector('#password').classList.add('error')
          }
          if (response.data !== 'Error') {
            this.$axios.$put(`https://realtorgi.by/admin/api/admin/user/${this.$store.state.auth.userData.id}`, {
              email: this.email
            }).then((response) => {
              this.$store.dispatch('changeAuthStatus', {
                authStatus: true,
                userData: {
                  id: response.data.id,
                  login: response.data.login,
                  email: response.data.email,
                  firstName: response.data.first_name,
                  lastName: response.data.last_name,
                  image: '',
                  profile: {
                    bank_details: response.data.bank_details,
                    egr_date: response.data.egr_date,
                    full_name: response.data.full_name,
                    inn: response.data.inn,
                    legal_entity: response.data.legal_entity,
                    manager_data: response.data.manager_data,
                    passportDate: response.data.passportDate,
                    passport_issuer: response.data.passport_issuer,
                    passport_number: response.data.passport_number,
                    passport_personal: response.data.passport_personal,
                    phone: response.data.phone,
                    place_of_residence: response.data.place_of_residence,
                    registration_address: response.data.registration_address,
                    residence_address: response.data.residence_address,
                    short_name: response.data.short_name,
                    type_user: response.data.type_user
                  }
                }
              })
              if (localStorage.getItem('AUTH_TOKEN')) {
                var encodedEmail = btoa(this.email)
                var encodedPassword = btoa(this.password)
                var encodedData = encodedEmail + '9c1eEB325C3Cf308A61eE5B5D6f699BCb668E608' + encodedPassword
                localStorage.setItem('AUTH_TOKEN', encodedData)
              }
              this.successStatus = 'Почта успешно изменена'
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Изменения данных.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Вы успешно сменили данные своего аккаунта.',
              })
              this.$axios.$get(`https://realtorgi.by/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
              .then((response) => {
                this.$store.dispatch('getNotifications', {
                  notifications: response.data.data
                })
                this.$store.dispatch('countUnreadNotifications')
              })
              this.$router.push('/');
            }).catch((error) => {
              if (error.response) {
                if (error.response.status === 422) {
                  var emailCheckError = {name: 'Email check error', status: 'Данная почта уже занята'}
                  this.errorStatus.push(emailCheckError)
                }
              }
            })
          }
        })
      }
      if (this.choosenOption === 'changePassword') {
        if (this.password !== this.passwordConfirm) {
          var emailCheckError = {name: 'Email check error', status: 'Пароли не совпадают'}
        this.errorStatus.push(emailCheckError)
        document.querySelector('#password').classList.add('error')
        document.querySelector('#password-confirm').classList.add('error')
        }
        else {
          this.$axios.$post('https://realtorgi.by/admin/api/admin/user/login', {
            email: this.$store.state.auth.userData.email,
            password: this.passwordOld
          }).then((response) => {
            if (response.message === 'Неправильный пароль') {
              var emailCheckError = {name: 'Email check error', status: 'Неверный пароль'}
              this.errorStatus.push(emailCheckError)
              document.querySelector('#password-old').classList.add('error')
            }
            if (response.data !== 'Error') {
              this.$axios.$put(`https://realtorgi.by/admin/api/admin/user/${this.$store.state.auth.userData.id}`, {
                password: this.password
              }).then(() => {
                this.$notify({
                  'group': 'user-notifications',
                  'title': `<div class='title'>Изменения данных.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                  'text': 'Вы успешно сменили данные своего аккаунта.',
                })
                this.$axios.$get(`https://realtorgi.by/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
                .then((response) => {
                  this.$store.dispatch('getNotifications', {
                    notifications: response.data.data
                  })
                  this.$store.dispatch('countUnreadNotifications')
                })
                if (localStorage.getItem('AUTH_TOKEN')) {
                  var encodedEmail = btoa(this.$store.state.auth.userData.email)
                  var encodedPassword = btoa(this.password)
                  var encodedData = encodedEmail + '9c1eEB325C3Cf308A61eE5B5D6f699BCb668E608' + encodedPassword
                  localStorage.setItem('AUTH_TOKEN', encodedData)
                }
                this.successStatus = 'Пароль успешно изменен'
                this.$router.push('/');
              })
            }
          })
        }
      }
    }
  }
})
</script>

<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.settings {
  padding: 0 1rem;
  margin-bottom: 3rem;
  display: flex;
  justify-content: space-between;
  &-menu {
    width: 20%;
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
    &-option {
      border-bottom: 1px solid $border-color;
      padding: 0.75rem 0;
      color: $text-color-2;
      cursor: pointer;
      span {
        transition: .2s ease-in-out;
      }
    }
    &-option:hover {
      span {
        margin-left: 0.5rem;
      }
    }
    .active {
      span {
        color: $text-color-1;
        margin-left: 0.5rem;
      }
    }
  }
  &-wrapper {
    width: 77%;
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
    .settings-title {
      margin-bottom: 0.25rem;
    }
    .settings-subtitle {
      color: $text-color-2;
      margin-bottom: 2rem;
    }
    .input-wrapper {
      display: flex;
      flex-direction: column;
      width: 100%;
      label {
        margin-bottom: 0.5rem;
      }
      .error {
        border: 2px solid $error-color !important;
      }
      input {
        max-width: 18rem;
        margin-bottom: 1rem;
      }
    }
    .error-status {
      color: $error-color;
      margin-bottom: 1rem;
      font-size: 0.8rem;
    }
    .success-status {
      color: $text-color-2;
      margin-bottom: 1rem;
      font-size: 0.8rem;
    }
    .submit {
      @include button-active;
      margin-top: 2rem;
    }
    .submit:hover {
      @include button-rounded;
    }
  }
}

@media (max-width: 1085px) {
  .settings {
    flex-direction: column;
    &-menu {
      width: 100%;
      margin-bottom: 1rem;
    }
    &-wrapper {
      width: 100%;
    }
  }
}

@media (max-width: 375px) {
  .settings {
    padding: 0;
  }
}
</style>