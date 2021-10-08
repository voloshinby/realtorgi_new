<template>
  <section>
    <div class="participate-wrapper">
      <div class="instruction">
        <h1 class="instruction-title">
          Как участвовать в онлайн-аукционах
        </h1>
        <div class="instruction-content">
          <p><strong>1.</strong> Для участие в аукционе на площадке realtorgi нажмите кнопку «Регистрация»</p>
          <p><strong>2.</strong> После ввода своей электронной почты, получите на указанный вами электронный адрес логин и пароль пользователя.</p>
          <p><strong>3.</strong> Войдите в свой личный кабинет нажав на кнопку «Личный кабинет» в правом верхнем углу главной страницы.</p>
          <p><strong>4.</strong> В личном кабинете заполните и сохраните свои персональные данные.</p>
          <p><strong>5.</strong> Подождите пока Вам на личную почту придёт уведомление от оператора ЭТП о том что вы успешно зарегистрировались на площадке.</p>
          <p><strong>6.</strong> Выберите лот, по которому Вы желаете принять участие в торгах, и на странице лота нажмите кнопку «Подать заявку».</p>
          <p><strong>7.</strong> Внесите задаток на реквизиты указанные на странице интересующего Вас лота.</p>
          <p><strong>8.</strong> Получите на почту уведомление от оператора ЭТП об успешной регистрации на торги по лоту.</p>
          <p><strong>9.</strong> В назначенный день и время торгов, войдя в личный кабинет под своим логином и паролем, учувствуйте в торгах.</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  transition: 'slide-right',
  head() {
    return {
      title: 'Как принять участие в аукционе?',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

section {
  margin: 0 1rem 3rem 1rem;
  .participate-wrapper {
    .instruction {
      margin-bottom: 2rem;
      background-color: #fff;
      border-radius: 10px;
      padding: 2rem;
      box-shadow:
        0 2.8px 2.2px rgba(0, 0, 0, 0.034),
        0 6.7px 5.3px rgba(0, 0, 0, 0.048),
        0 12.5px 10px rgba(0, 0, 0, 0.06),
        0 22.3px 17.9px rgba(0, 0, 0, 0.072),
        0 41.8px 33.4px rgba(0, 0, 0, 0.086),
        0 100px 80px rgba(0, 0, 0, 0.12);
      &-title {
        border-bottom: 1px solid $border-color;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
      }
      &-content {
        p {
          margin: 0.5rem 0;
        }
      }
    }
  }
}

@media (max-width: 375px) {
  section {
    margin: 0 0 3rem 0;
  }
}
</style>
