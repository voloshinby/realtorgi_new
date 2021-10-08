<template>
  <div class="counterparty">
    <div class="counterparty-header">
      <div class="title">
        Основные данные:
      </div>
      <div class="subtitle">
        Внесите юридические данные для вашего аккаунта
      </div>
    </div>
    <div class="counterparty-inputs">
      <div class="counterparty-input">
        <label for="inn">УНП:</label>
        <input type="text" id="inn" v-model="inn">
      </div>
      <div class="counterparty-input">
        <label for="inn">Короткое название организации:</label>
        <input type="text" id="short_name" v-model="short_name">
      </div>
      <div class="counterparty-input">
        <label for="full_name">Полное название организации:</label>
        <input type="text" id="full_name" v-model="full_name">
      </div>
      <div class="counterparty-input">
        <label for="egr_date">Дата регистрации ЕГР:</label>
        <input type="date" id="egr_date" v-model="egr_date">
      </div>
      <div class="counterparty-input">
        <label for="manager_data">Данные по руководителю<br>(Должность, ФИО, Действует на основании):</label>
        <input type="text" id="manager_data" v-model="manager_data">
      </div>
      <div class="counterparty-input">
        <label for="legal_address">Юридический адрес:</label>
        <input type="text" id="legal_address" v-model="legal_address">
      </div>
      <div class="counterparty-input" :class="{ disabled: (sameAddresses === true) }"> 
        <label for="mail_address" >Почтовый адрес:</label>
        <input type="text" id="mail_address" :disabled="sameAddresses" v-model="mail_address">
      </div>
      <div class="checkbox">
        <label for="addresses_comparison">Юридический и почтовый адресы совпадают</label>
        <input type="checkbox" name="addresses_comparison" id="addresses_comparison" v-model="sameAddresses">
      </div>
      <div class="counterparty-input">
        <label for="bank_details">Банковские реквизиты <br>(Банк, Код банка (BIC), УНП банка, р/счёт (IBAN)):</label>
        <input type="text" id="bank_details" v-model="bank_details">
      </div>
      <div class="counterparty-input">
        <label for="legal_person">ФИО лица, участвующего от имени юридического лица:</label>
        <input type="text" id="legal_person" v-model="legal_person">
      </div>
      <div class="counterparty-input">
        <label for="passport_number">Серия и номер паспорта:</label>
        <input type="text" id="passport_nubmer" v-model="passport_number">
      </div>
      <div class="counterparty-input">
        <label for="passport_issuer">Кем выдан паспорт:</label>
        <input type="text" id="passport_issuer" v-model="passport_issuer">
      </div>
      <div class="counterparty-input">
        <label for="passport_date">Когда выдан паспорт:</label>
        <input type="date" id="passport_date" v-model="passport_date">
      </div>
      <div class="counterparty-input">
        <label for="passport_personal">Личный номер паспорта:</label>
        <input type="text" id="passport_personal" v-model="passport_personal">
      </div>
      <div class="counterparty-input">
        <label for="phone">Номер телефона:</label>
        <input type="text" id="phone" v-model="phone">
      </div>
      <div class="counterparty-input counterparty-file-input">
        <label class="label">Документы:</label>
        <div class="file-input-wrapper">
          <label for="file-input" class="files-input-label" @change="handeImageUpload">{{ label }}</label>
          <div class="explanation">Возможные документы для отправки:
            <ul>
              <li>Паспорт Гражданина Республики Беларусь - Фотографии (сканы) 25, 31, 32, 33 страниц паспорта (указать уточнение)</li>
              <li>Паспорт гражданина другого государства</li>
              <li>Вид на жительство в РБ</li>
              <li>Доверенность</li>
              <li>Свидетельство о гос. регистрации (в случае Юридического лица, или ИП)</li>
              <li>Документ подтверждающий полномочия (в случае Юридического лица)</li>
            </ul>
          </div>
          <input type="file" id="file-input" class="files-input" multiple @change="handeImageUpload">
        </div>
      </div>
    </div>
    <div class="personal">
      Добавляя новый профиль, вы даете согласие на <a href="https://realtorgi.by/policy" target="_blank" class="link">обработку персональных данных.</a>
    </div>
  </div>
</template>

<script>
export default {

  data() {
    return {
      inn: '',
      egr_date: null,
      short_name: '',
      full_name: '',
      manager_data: '',
      legal_address: '',
      mail_address: '',
      bank_details: '',
      legal_person: '',
      passport_number: '',
      passport_issuer: '',
      passport_date: null,
      passport_personal: '',
      phone: '',
      sameAddresses: false,
      files: {},
      label: 'Загрузите файлы'
    }
  },
  methods: {
    handeImageUpload(e) {
      this.files = e.target.files
      if (this.files.length === 1) {
        this.label = this.files[0].name
      }
      if (this.files.length > 1) {
        this.label = `Выбрано: ${this.files.length}`
      }
    }
  }
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
  cursor: not-allowed;
  pointer-events: none;
  user-select: none;
}
</style>
