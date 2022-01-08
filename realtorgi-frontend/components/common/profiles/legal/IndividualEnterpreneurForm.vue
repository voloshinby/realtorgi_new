<template>
  <div class="counterparty">
    <div class="counterparty-header">
      <div class="title">
        Основные данные
      </div>
    </div>
    <div class="counterparty-inputs">
      <div class="counterparty-input">
        <label for="inn">УНП:</label>
        <input disabled type="text" id="inn" v-model="inn">
      </div>
      <div class="counterparty-input">
        <label for="full_name">ФИО:</label>
        <input disabled type="text" id="full_name" v-model="full_name">
      </div>
      <div class="counterparty-input">
        <label for="egr_date">Дата регистрации ЕГР:</label>
        <input disabled type="text" id="egr_date" v-model="egr_date">
      </div>
      <div class="counterparty-input">
        <label for="registration_address">Адрес прописки:</label>
        <input disabled type="text" id="registration_address" v-model="registration_address">
      </div>
      <div class="counterparty-input">
        <label for="living_address">Адрес проживания:</label>
        <input disabled type="text" id="living_address" v-model="living_address">
      </div>
      <div class="counterparty-input">
        <label for="bank_details">Банковские реквизиты <br>(Банк, Код банка (BIC), УНП банка, р/счёт (IBAN)):</label>
        <input disabled type="text" id="bank_details" v-model="bank_details">
      </div>
      <div class="counterparty-input">
        <label for="passport_number">Серия и номер пасспорта:</label>
        <input disabled type="text" id="passprt_nubmer" v-model="passport_number">
      </div>
      <div class="counterparty-input">
        <label for="passport_issuer">Кем выдан пасспорт:</label>
        <input disabled type="text" id="passport_issuer" v-model="passport_issuer">
      </div>
      <div class="counterparty-input">
        <label for="passport_date">Когда выдан пасспорт:</label>
        <input disabled type="text" id="passport_date" v-model="passport_date">
      </div>
      <div class="counterparty-input">
        <label for="passport_personal">Личный номер пасспорта:</label>
        <input disabled type="text" id="passport_personal" v-model="passport_personal">
      </div>
      <div class="counterparty-input">
        <label for="phone">Номер телефона:</label>
        <input disabled type="text" id="phone" v-model="phone">
      </div>
     <!-- <div class="counterparty-input counterparty-file-input">
        <label class="label">Документы:</label>
        <ul v-if="this.$store.state.auth.userData.profile.files">
          <li v-for="(file, index) in this.$store.state.auth.userData.profile.files" :key="index">{{ file.name }}</li>
        </ul>
        <ul v-else>
          <li>Нет файлов</li>
        </ul>
      </div>-->
    </div>
  </div>
</template>

<script>
export default {

  data() {
    return {
      inn: this.$store.state.auth.userData.profile.inn,
      full_name: this.$store.state.auth.userData.profile.full_name,
      egr_date: this.$store.state.auth.userData.profile.egr_date,
      registration_address: this.$store.state.auth.userData.profile.registration_address,
      living_address: this.$store.state.auth.userData.profile.place_of_residence,
      bank_details: this.$store.state.auth.userData.profile.bank_details,
      passport_number: this.$store.state.auth.userData.profile.passport_number,
      passport_issuer: this.$store.state.auth.userData.profile.passport_issuer,
      passport_date: this.$store.state.auth.userData.profile.passportDate,
      passport_personal: this.$store.state.auth.userData.profile.passport_personal,
      phone: this.$store.state.auth.userData.profile.phone,
      files: {},
      label: 'Загрузите файлы'
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.counterparty {
  &-header {
    .title {
      font-weight: 500;
      font-size: 1.1rem;
    }
    .subtitle {
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }
  }
  .counterparty-inputs {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    padding-left: 1rem;
    .checkbox {
      display: flex;
      align-items: center;
      flex-direction: row-reverse;
      justify-content: flex-end;
      margin-bottom: 2rem;
      margin-right: 3rem;
      label {
        font-size: 0.8rem;
        color: $text-color-2;
      }
      input {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5em;
        margin-top: 1px;
      }
    }
    .counterparty-input {
      display: flex;
      flex-direction: column;
      margin-right: 3rem;
      margin-bottom: 2rem;
      label {
        margin-bottom: 0.25rem;
      }
      input {
        width: 100%;
        max-width: 24rem;
      }
    }
    .counterparty-file-input {
      label {
        margin-bottom: 1rem;
      }
      .file-input-wrapper {
        position: relative;
      }
    }
    .explanation {
      margin: 2rem 0;
    }
    .files-input {
      width: 0.1px;
      height: 0.1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
    .files-input-label {
      @include button-active;
      margin-top: 0.5rem;
      width: fit-content;
    }
  }

  ul {
    list-style: disc;
    margin: 0.5rem 0;
    li {
      margin-left: 1.5rem;
      color: $text-color-2;
      font-size: 0.9rem;
    }
  }
  .personal {
    font-size: 0.9rem;
    .link {
      color: $link-color;
    }
  }
}
.disabled {
  opacity: 0.6;
  pointer-events: none;
  cursor: not-allowed;
}
</style>
