<template>
  <div class="new-profile">
    <div class="new-profile-wrapper">
      <div class="new-profile-header">
        <h1 v-if="this.$store.state.auth.userData.profile.type_user === 'jur' || this.$store.state.auth.userData.profile.type_user === 'ip' || this.$store.state.auth.userData.profile.type_user === 'phys'" class="title">
          Изменить профиль
        </h1>
        <h1 v-else class="title">
          Добавить профиль
        </h1>
        <div class="subtitle">
          Юридическая форма собственности, от лица которой будут совершаться действия
        </div>
        <div class="choose-profile">
          <label>Форма собственности:</label>
          <div class="profile-types">
            <div class="ul button" @click="counterparty = 'jur'" :class="{ active: (counterparty === 'jur') }">Юридическое лицо</div>
            <div class="ip button" @click="counterparty = 'ip'" :class="{ active: (counterparty === 'ip') }">Индивидуальный предприниматель</div>
            <div class="pl button" @click="counterparty = 'phys'" :class="{ active: (counterparty === 'phys') }">Физическое лицо</div>
          </div>
        </div>
      </div>
      <div class="new-profile-counterparty">
        <legal-entity-form v-if="counterparty === 'jur'" ref="legalEntityForm" />
        <individual-enterpreneur-form v-if="counterparty === 'ip'" ref="individualEnterpreneurForm" />
        <physical-form v-if="counterparty === 'phys'" ref="physicalForm" />
      </div>
      <div class="button">
        <nuxt-link :to="{ name: 'account-profiles' }">
          <button class="new-profile-back">Назад</button>
        </nuxt-link>
        <button class="new-profile-submit" @click="addProfile">Отправить</button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import LegalEntityForm from '~/components/common/counterparties/legal/LegalEntityForm';
import PhysicalForm from '~/components/common/counterparties/legal/PhysicalForm';
import IndividualEnterpreneurForm from '~/components/common/counterparties/legal/IndividualEnterpreneurForm';
export default {
  components: {
    LegalEntityForm,
    IndividualEnterpreneurForm,
    PhysicalForm,
  },

  data: () => ({
    counterparty: 'jur',
    moment: moment
  }),

  beforeMount() {
    if (this.$store.state.auth.authorized === false) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Нет доступа.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Только авторизованные пользователи могут просматривать данный раздел.',
        'duration': 5000
      })
      this.$router.push('/auth')
    }
    if (!this.$store.state.auth.userData.active) {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Электронный адрес вашей почты не подтвержден.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Вы не можете расматривать страницы личного кабинета, пока не подтвердите адрес электронной почты.',
        'duration': 5000
      })
      this.$router.push('/auth/registration/confirm')
    }
  },

  methods: {
    addProfile() {
      if (this.counterparty === 'jur') {
        let formData = new FormData()
        for ( var i = 0; i < this.$refs.legalEntityForm._data.files.length; i++ ) {
          let file = this.$refs.legalEntityForm._data.files[i]
          formData.append(`images[` + i + `]`, file);
        }
        this.$axios.$post(process.env.API_URL +  `/admin/api/admin/gallery/uploadFiles/` + this.$store.state.auth.userData.id,
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
        )
        this.$axios.$post(process.env.API_URL +  `/admin/api/admin/change_user`, {
          user_id: this.$store.state.auth.userData.id,
          type_user: 'jur',
          inn: this.$refs.legalEntityForm._data.inn,
          short_name: this.$refs.legalEntityForm._data.short_name,
          full_name: this.$refs.legalEntityForm._data.full_name,
          egr_date: this.$refs.legalEntityForm._data.egr_date,
          manager_data: this.$refs.legalEntityForm._data.manager_data,
          place_of_residence: this.$refs.legalEntityForm._data.legal_address,
          registration_address: this.$refs.legalEntityForm._data.sameAddresses ? this.$refs.legalEntityForm._data.legal_address : this.$refs.legalEntityForm._data.mail_address,
          bank_details: this.$refs.legalEntityForm._data.bank_details,
          legal_entity: this.$refs.legalEntityForm._data.legal_person,
          passport_number: this.$refs.legalEntityForm._data.passport_number,
          passport_issuer: this.$refs.legalEntityForm._data.passport_issuer,
          passportDate: this.$refs.legalEntityForm._data.passport_date,
          passport_personal: this.$refs.legalEntityForm._data.passport_personal,
          phone: this.$refs.legalEntityForm._data.phone,
        }).then((response) => {
          if (response.success) {
            this.$router.push('/account/seller');
            if (this.$store.state.auth.userData.profile.type_user === 'jur' ||
              this.$store.state.auth.userData.profile.type_user === 'ip' ||
              this.$store.state.auth.userData.profile.type_user === 'phys')
            {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на изменение профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на изменение профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
              this.$axios.$get(process.env.API_URL +  `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
              .then((response) => {
                this.$store.dispatch('getNotifications', {
                  notifications: response.data.data
                })
                this.$store.dispatch('countUnreadNotifications')
              })
            }
            else {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на добавление профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на добавление профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
              this.$axios.$get(process.env.API_URL +  `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
              .then((response) => {
                this.$store.dispatch('getNotifications', {
                  notifications: response.data.data
                })
                this.$store.dispatch('countUnreadNotifications')
              })
            }
          }
        })
      }
      if (this.counterparty === 'ip') {
          let formData = new FormData()
          for ( var i = 0; i < this.$refs.individualEnterpreneurForm._data.files.length; i++ ) {
            let file = this.$refs.individualEnterpreneurForm._data.files[i]
            formData.append(`images[` + i + `]`, file);
          }
          this.$axios.$post(process.env.API_URL +  `/admin/api/admin/gallery/uploadFiles/` + this.$store.state.auth.userData.id,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            }
          )
        this.$axios.$post(process.env.API_URL +  `/admin/api/admin/change_user`, {
          user_id: this.$store.state.auth.userData.id,
          type_user: 'ip',
          inn: this.$refs.individualEnterpreneurForm._data.inn,
          full_name: this.$refs.individualEnterpreneurForm._data.full_name,
          egr_date: this.$refs.individualEnterpreneurForm._data.egr_date,
          registration_address: this.$refs.individualEnterpreneurForm._data.registration_address,
          residence_address: this.$refs.individualEnterpreneurForm._data.sameAddresses ? this.$refs.individualEnterpreneurForm._data.registration_address : this.$refs.individualEnterpreneurForm._data.living_address,
          bank_details: this.$refs.individualEnterpreneurForm._data.bank_details,
          passport_number: this.$refs.individualEnterpreneurForm._data.passport_number,
          passport_issuer: this.$refs.individualEnterpreneurForm._data.passport_issuer,
          passportDate: this.$refs.individualEnterpreneurForm._data.passport_date,
          passport_personal: this.$refs.individualEnterpreneurForm._data.passport_personal,
          phone: this.$refs.individualEnterpreneurForm._data.phone,
        }).then((response) => {
          if (response.success) {
            this.$router.push('/account/seller');
            if (this.$store.state.auth.userData.profile.type_user === 'jur' ||
              this.$store.state.auth.userData.profile.type_user === 'ip' ||
              this.$store.state.auth.userData.profile.type_user === 'phys')
            {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на изменение профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на изменение профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
            }
            else {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на добавление профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на добавление профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
            }
          }
        })
      }
      if (this.counterparty === 'phys') {
        let formData = new FormData()
        for ( var i = 0; i < this.$refs.physicalForm._data.files.length; i++ ) {
          let file = this.$refs.physicalForm._data.files[i]
          formData.append(`images[` + i + `]`, file);
        }
        this.$axios.$post(process.env.API_URL +  `/admin/api/admin/gallery/uploadFiles/` + this.$store.state.auth.userData.id,
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
        )
        this.$axios.$post(process.env.API_URL +  `/admin/api/admin/change_user`, {
          user_id: this.$store.state.auth.userData.id,
          type_user: 'phys',
          full_name: this.$refs.physicalForm._data.full_name,
          registration_address: this.$refs.physicalForm._data.registration_address,
          residence_address: this.$refs.physicalForm._data.sameAddresses ? this.$refs.physicalForm._data.registration_address : this.$refs.physicalForm._data.living_address,
          passport_number: this.$refs.physicalForm._data.passport_number,
          passport_issuer: this.$refs.physicalForm._data.passport_issuer,
          passportDate: this.$refs.physicalForm._data.passport_date,
          passport_personal: this.$refs.physicalForm._data.passport_personal,
          phone: this.$refs.physicalForm._data.phone,
        }).then((response) => {
          if (response.success) {
            if (this.$store.state.auth.userData.profile.type_user === 'jur' ||
              this.$store.state.auth.userData.profile.type_user === 'ip' ||
              this.$store.state.auth.userData.profile.type_user === 'phys')
            {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на изменение профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на изменение профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
            }
            else {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Заявка на добавление профиля.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.',
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Заявка на добавление профиля.',
                text: 'Ваша заявка была принята. В скором времени администратор площадки её рассмотрит.'
              })
            }
            this.$router.push('/account/seller');
          }
        })
      }
    }
  },

  head() {
    return {
      title: 'Новый профиль',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.new-profile {
  max-width: 60rem;
  margin: 0 auto;
  background-color: #fff;
  border-radius: 10px;
  margin-bottom: 3rem;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    padding: 3rem 2rem;
    display: flex;
    flex-direction: column;
    .new-profile-header {
      .title {
        font-size: 2rem;
      }
      .subtitle {
        font-size: 0.9rem;
        margin-bottom: 2rem;
      }
      .choose-profile {
        margin-bottom: 1rem;
        label {
          font-weight: 500;
          font-size: 1.1rem;
        }
        .profile-types {
          display: flex;
          margin-bottom: 2rem;
          .button {
            @include button;
            margin-top: 0.5rem;
            margin-right: 0.25rem;
          }
          .button:hover {
            @include button-hover;
          }
          .active {
            @include button-active;
          }
          .active:hover {
            @include button-active;
          }
        }
      }
    }
    .button {
      max-width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 2rem;
      .new-profile-submit {
        @include button-active;
        background-color: $link-color;
        align-self: flex-end;
        width: fit-content;
        margin-left: 2rem;
      }
      .new-profile-submit:hover {
        @include button-rounded;
        border-color: $link-color;
        color: $link-color;
      }
      .new-profile-back {
        outline: none;
      }
    }

  }
}

@media (max-width: 593px) {
  .profile-types {
    flex-direction: column;
  }
}
</style>
