<template>
  <div class="counterparty">
    <div class="counterparty-header">
      <div class="title">
        Основные данные
      </div>
      <div class="subtitle">
        Внесите юридические данные для вашего аккаунта
      </div>
    </div>
    <div class="counterparty-inputs">
      <div class="counterparty-input">
        <label for="full_name">ФИО:</label>
        <input type="text" id="full_name" v-model="full_name">
      </div>
      <div class="counterparty-input">
        <label for="registration_address">Адрес прописки:</label>
        <input type="text" id="registration_address" v-model="registration_address">
      </div>
      <div class="counterparty-input" :class="{ disabled: (sameAddresses === true) }">
        <label for="living_address">Адрес проживания:</label>
        <input type="text" id="living_address" v-model="living_address">
      </div>
      <div class="checkbox">
        <label for="addresses_comparison">Адресы проживания и прописки совпадают</label>
        <input type="checkbox" name="addresses_comparison" id="addresses_comparison" v-model="sameAddresses">
      </div>
      <div class="counterparty-input">
        <label for="passport_number">Серия и номер пасспорта:</label>
        <input type="text" id="passport_number" v-model="passport_number">
      </div>
      <div class="counterparty-input">
        <label for="passport_issuer">Кем выдан пасспорт:</label>
        <input type="text" id="passport_issuer" v-model="passport_issuer">
      </div>
      <div class="counterparty-input">
        <label for="passport_date">Когда выдан пасспорт:</label>
        <input type="date" id="passport_date" v-model="passport_date">
      </div>
      <div class="counterparty-input">
        <label for="passport_personal">Личный номер пасспорта:</label>
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
      full_name: '',
      registration_address: '',
      living_address: '',
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
  pointer-events: none;
  cursor: not-allowed;
}
</style>
