<template>
  <section>
    <div class="contacts">
      <h1 class="contacts-title">Контакты</h1>
      <div class="contacts-wrapper">
        <div class="contacts-content">
          <h2>ООО «Реалконсалтинг»</h2>
          Телефон/факс: <a href="tel:+375172566135">+375 (17) 256-61-35</a><br>
          Тел. моб.: <a href="tel:+37525549862">+375 (25) 549-86-22</a><br>
          Email: <a href="mailto:auction@expertiza.by">auction@expertiza.by</a><br>
          <p>г. Минск, 220015, Республика Беларусь<br>
          ул. Пономаренко 35А, помещение 701, каб. 8<br>
          Бизнес-центр «ИНФО»<br>
          Время работы: Пн - Пт: 09:00 - 18:00
          </p>
          <p>
          Реквизиты счета:<br>
          р/с № BY70SLAN30124358100130000000 в ЗАО Банк ВТБ (Беларусь) <br>
          г. Минск, ул. Московская, 14, БИК SLANBY22<br>
          УНП 191302068, ОКПО 379206815000<br>
          </p>
        </div>
        <div class="contacts-form">
          <h1 class="form-title">
            Свяжитесь с нами
          </h1>
          <div class="form-subtitle">
            Вы можете получить обратную связь на любой интересующий Вас вопрос
          </div>
          <form onsubmit="return false" @submit="submit">
            <div class="name-input input">
              <div class="input-wrapper">
                <label for="name">Имя:</label>
                <input id="name" type="text" v-model="name" required minlength="2"/>
              </div>
              <div class="input-wrapper">
                <label for="password">Email:</label>
                <input id="password" type="text" v-model="email" required minlength="7"/>
              </div>
            </div>
            <div class="message-input input">
              <div class="input-wrapper">
                <label for="text">Сообщение:</label>
                <textarea id="text" type="text" v-model="text" required/>
              </div>
            </div>
            <div class="button-wrapper">
              <button class="submit">Отправить</button>
            </div>
          </form>
        </div>
      </div>
      <div class="contacts-map">
        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A0132207be51793f9d29549c54aec9835db19f9615939b8a3b2fa4e6d467758ad&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
      </div>
    </div>
  </section>
</template>
<script>
import moment from 'moment'
export default {
  transition: 'slide-right',

  data: () => ({
    name: '',
    text: '',
    email: '',
    moment: moment
  }),

  methods: {
    submit() {
      this.$axios.post('https://realtorgi.by/admin/api/admin/message', {
        name: this.name,
        email: this.email,
        text: this.text
      }).then((response) => {
        if (response.data.message === 'Message Created Successfully') {
          this.$notify({
            'group': 'user-notifications',
            'title': `<div class='title'>Ваше письмо отправлено.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
            'text': 'В скором времени администратор площадки свяжется с Вами и ответит на любой Ваш вопрос.',
          })
          this.name = ''
          this.email = ''
          this.text = ''
        }
      })
    }
  },

  head() {
    return {
      title: 'Контакты',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

section {
  margin: 0 1rem 3rem 1rem;
  .contacts {
    padding: 2rem;
    border-radius: 10px;
    background-color: #fff;
    box-shadow:
      0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);
    &-title {
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid $border-color;
    }
    .contacts-wrapper {
      display: flex;
      justify-content: space-between;
      .contacts-content {
        color: $text-color-2;
        margin-right: 1rem;
        p {
          margin: 0.5rem 0;
        }
        h2 {
          font-size: 1.2rem;
          font-weight: 500;
          color: $text-color-1;
          margin-bottom: 0.5rem;
        }
        a {
          color: $link-color;
        }
        a:hover {
          text-decoration: underline;
        }
      }
      .contacts-form {
        .form-subtitle {
          margin-bottom: 1rem;
          color: $text-color-2;
        }
        form {
          flex-grow: 1;
          .input {
            display: flex;
            .input-wrapper {
            margin-right: 1rem;
            }
          }
          .message-input {
            width: 100%;
            .input-wrapper {
              width: 100%;
              textarea {
                height: 8rem;
              }
            }
          }
          .name-input {
            margin-bottom: 1rem;
            .input-wrapper {
              width: 49%;
            }
          }
          .button-wrapper {
            margin-top: 2rem;
            .submit {
              @include button-active;
            }
            .submit:hover {
              @include button-rounded;
            }
          }
        }
      }
    }
    .contacts-map {
      margin-top: 4rem;
    }
  }
}

@media (max-width: 768px) {
  .contacts-wrapper {
    flex-direction: column;
    .contacts-content {
      margin-right: 0;
      margin-bottom: 2rem; 
    }
  }
}

@media (max-width: 425px) {
  .input {
    display: flex;
    flex-direction: column;
    .input-wrapper {
      margin-right: 0 !important;
      width: 100% !important;
    }
    .input-wrapper:first-child {
      margin-bottom: 1rem;
    }
  }
}

@media (max-width: 375px) {
  section {
    margin: 0 0 3rem 0;
  }
}
</style>
