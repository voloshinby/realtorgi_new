<template>
  <div class="registration-confirm" :class="{invalid: animated}" @animationend="animated = false">
    <div class="registration-confirm-wrapper">
      <h1 class="title">Подтверждение почты</h1>
      <div class="subtitle"><span>Введите код подтверждения, отправленный на Вашу почту</span></div>
      <div class="input-wrapper">
        <label for="email-confirmation-code">Введите код подтверждения:</label>
        <input type="text" id="email-confirmation-code" v-model="confirmationCode" :disabled="confirmationCode.length === 6" :class="{disabled: (confirmationCode.length === 6)}" @input="checkCode">
      </div>
      <div class="send-again">
        Отправить код заново?
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment'

export default {
  data: () => ({
    confirmationCode: '',
    moment: moment,
    animated: false,
  }),
  methods: {
    checkCode() {
      this.confirmationCode = this.confirmationCode.replace(new RegExp(/[^\d]/,'ig'), "")
      if (this.confirmationCode.length === 6) {
        this.$axios.post(process.env.API_URL +  `/admin/api/admin/registration/confirm/${this.$store.state.auth.userData.id}`, {
          registration_code: this.confirmationCode
        }).then((response) => {
          if (response.data.message === 'Ошибка, введите верный код') {
            this.confirmationCode = ''
            this.$notify({
              'group': 'user-notifications',
              'title': `<div class='title'>Неверный код подтверждения.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
              'text': 'Вы ввели неверный код подтверждения. Попробуйте, пожалуйста, еще раз.',
            })
            this.animated = true
          }
          if (response.data.message === 'Подтверждение прошло успешно') {
            this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
              user_id: this.$store.state.auth.userData.id,
              title: 'Электронный адрес вашей почты был подтвержден.',
              text: 'Подтверждение электронного адреса вашей почты прошло успешно.'
            })
            this.$notify({
              'group': 'user-notifications',
              'title': `<div class='title'>Электронный адрес вашей почты был подтвержден.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
              'text': 'Подтверждие электронного адреса вашей почты прошло успешно.',
            })
            this.$axios.$get(process.env.API_URL +  `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
              .then((response) => {
                this.$store.dispatch('getNotifications', {
                  notifications: response.data.data
                })
                this.$store.dispatch('countUnreadNotifications')
              })
            this.$store.dispatch('changeEmailActiveStatus', {
              activeStatus: 1
            })
            this.$router.push('/');
          }
        })
      }
    }
  },
  beforeMount() {
    if (this.$store.state.auth.authorized === true && this.$store.state.auth.userData.active) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Ошибка.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Электронный адрес Вашей почты уже подтвержден.',
      })
      this.$router.push('/')
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.invalid {
  animation: shake .5s linear;
}

@keyframes shake {
  8%, 41% {
    -webkit-transform: translateX(-15px);
  }
  25%, 58% {
    -webkit-transform: translateX(15px);
  }
  75% {
    -webkit-transform: translateX(-8px);
  }
  92% {
    -webkit-transform: translateX(8px);
  }
  0%, 100% {
    -webkit-transform: translateX(0);
  }
}

.registration-confirm {
  width: 100%;
  height: 100%;
  margin-bottom: 3rem;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  &-wrapper {
    background-color: #fff;
    padding: 3rem 8rem 2rem 8rem;
    max-width: 52rem;
    border-radius: 10px;
    box-shadow:
      0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);
    .subtitle {
      margin-bottom: 2rem;
      color: $text-color-2;
    }
    .input-wrapper {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      label {
        margin-bottom: 1rem;
        font-size: 1.2rem;
      }
      input {
        margin-bottom: 2rem;
        background-color: #fff;
        border: none;
        outline: none;
        font-size: 4rem;
        height: auto;
        width: 24rem;
        letter-spacing: 1rem;
        text-align: center;
        border-bottom: 2px solid $border-color;
        transition: .1s ease-in-out;
      }
      .disabled {
        opacity: 0.4;
      }
    }
    .send-again {
      color: $link-color;
      cursor: pointer;
    }
  }
}
</style>
