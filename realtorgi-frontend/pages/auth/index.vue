<template>
    <div class="sign-in">
        <div class="sign-in-wrapper">
            <form class="content-wrapper" onsubmit="return false" @submit="submit">
                <h1 class="title">Войдите в свой аккаунт</h1>
                <div class="subtitle"><span>Если у вас нет аккаунта, вы можете <nuxt-link class="link" :to="{ name: 'auth-registration' }">зарегистрироваться.</nuxt-link></span></div>
                <div class="inputs">
                  <div class="email-input input">
                    <div class="input-wrapper">
                      <label for="email">Email:</label>
                      <input id="email" type="text" required minlength="7" v-model="email" @change="validateEmail">
                    </div>
                  </div>
                  <div class="password-input input">
                    <div class="input-wrapper">
                      <label for="password">Пароль:</label>
                      <input id="password" type="password" required minlength="6" v-model="password" @input="validatePassword">
                    </div>
                  </div>
                </div>
                <div class="error-status">
                  <p v-for="error in errorStatus" :key="error.name">
                    {{ error.status }}
                  </p>
                </div>
                <div class="remember-checkbox">
                  <span>Запомнить меня</span>
                  <input type="checkbox" name="remember" v-model="rememberMe">
                </div>
                <div class="buttons-wrapper">
                  <nuxt-link :to="{ name: 'index' }">
                    Отмена
                  </nuxt-link>
                  <button type="submit" class="submit">Войти</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {

  data: () => ({
    email: '',
    password: '',
    rememberMe: true,
    errorStatus: [],
  }),
  
  methods: {
    submit() {
      if (this.errorStatus.length === 0) {
        this.$axios.$post('https://realtorgi.by/admin/api/admin/user/login', {
          email: this.email,
          password: this.password
        }).then(response => {
          if (response.success && response.data !== 'Error') {
            var encodedEmail = btoa(this.email)
            var encodedPassword = btoa(this.password)
            var encodedData = encodedEmail + '9c1eEB325C3Cf308A61eE5B5D6f699BCb668E608' + encodedPassword
            this.$store.dispatch('changeAuthStatus', {
              authStatus: true,
              userData: {
                active: response.data.email_verified_at,
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
                  type_user: response.data.type_user,
                  files: response.data.files
                },
                notifications: []
              }
            })
            if (this.rememberMe) {
              localStorage.setItem('AUTH_TOKEN', encodedData)
            }
            this.$axios.$get(`https://realtorgi.by/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
            .then((response) => {
              this.$store.dispatch('getNotifications', {
                notifications: response.data.data
              })
              this.$store.dispatch('countUnreadNotifications')
              this.$router.push('/');
            })
            
          }
          if (response.data === 'Error') {
            const checkEmailExististing = obj => obj.name === 'emailAndPasswordCheck'
            var emailAndPasswordCheck = {name: 'emailAndPasswordCheck', status: 'Неверные почта или пароль'}
            this.errorStatus.push(emailAndPasswordCheck)
            if (!this.errorStatus.some(checkEmailExististing)) {
              this.errorStatus.push(emailAndPasswordCheck)
            }
          }
        })
      }
    },
    validatePassword() {
      var passwordError = {name: 'Password error', status: 'Поле "Пароль" содержит недопустимые символы'}
      const checkPasswordError = obj => obj.name === 'Password error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'emailAndPasswordCheck';
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
    validateEmail() {
      var emailError = {name: 'Email error', status: 'Неверный формат электронной почты'}
      const checkEmailError = obj => obj.name === 'Email error'
      this.errorStatus = this.errorStatus.filter(function( item ) {
        return item.name !== 'emailAndPasswordCheck';
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
    }
  },

  beforeMount() {
    if (this.$store.state.auth.authorized === true) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Ошибка.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Вы уже авторизованы в системе.',
      })
      this.$router.push('/')
    }
  },

  head() {
    return {
      title: 'Вход',
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.sign-in {
    display: flex;
    width: 100%;
    height: 100%;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
    .sign-in-wrapper {
      background-color: #fff;
      padding: 3rem 8rem 2rem 8rem;
      border-radius: 10px;
      box-shadow:
        0 2.8px 2.2px rgba(0, 0, 0, 0.034),
        0 6.7px 5.3px rgba(0, 0, 0, 0.048),
        0 12.5px 10px rgba(0, 0, 0, 0.06),
        0 22.3px 17.9px rgba(0, 0, 0, 0.072),
        0 41.8px 33.4px rgba(0, 0, 0, 0.086),
        0 100px 80px rgba(0, 0, 0, 0.12);
      .title {
        margin-bottom: 1rem;
      }
      .subtitle {
        margin-bottom: 2rem;
        .link {
          color: $link-color;
        }
      }
      .inputs {
        display: flex;
        width: 100%;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        .input {
          display: flex;
          justify-content: center;
          width: 100%;
          .input-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            label {
            margin-right: 1rem;
            width: 5rem;
            text-align: right;
            }
            .error {
              border: 2px solid $error-color !important;
            }
            input {
              max-width: 18rem;
              margin-bottom: 1rem;
              margin-right: 3rem;
            }
          }
          .input:last-child {
            .input-wrapper {
              .input {
                margin-bottom: 0;
              }
            }
          }
        }
      }
      .error-status {
        color: $error-color;
        margin-bottom: 1rem;
        font-size: 0.8rem;
      }
      .remember-checkbox {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        input {
          margin-left: 0.5rem;
          background-color: $background-color-1;
          border: 1px solid $border-color;
          width: 1rem;
          margin-top: 1px;
        }
      }
      .buttons-wrapper {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        .submit {
          @include button-active;
          background-color: $link-color;
          margin-left: 1rem;
        }
        .submit:hover {
          @include button-rounded;
          border-color: $link-color;
          color: $link-color;
        }
      }
    }
}

@media (max-width: 748px) {
  .sign-in {
    .sign-in-wrapper {
      background-color: #fff;
      padding: 3rem 3rem 2rem 3rem;
      border-radius: 10px;
      .inputs {
        .input {
          .input-wrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
            label {
            margin-right: 0;
            width: auto;
            text-align: left;
            margin-bottom: 0.25rem;
            }
          }
        }
      }
    }
  }
}
</style>
