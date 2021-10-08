<template>
  <loading-spinner v-if="$fetchState.pending" />
  <div v-else class="customer-request">
    <div v-if="openParticipationPopup" class="participation-popup" @click="openParticipationPopup = !openParticipationPopup">
      <div class="participation-popup-form" @click.stop>
        <div class="participation-popup-form-wrapper">
          <h1 class="title">Подтвеждение участия в торгах</h1>
          <div class="subtitle">Подтверждаете ли Вы свое участие в торгах по данному лоту ({{ choosenLot.lot.name }})?</div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup = !openParticipationPopup">Закрыть</button>
            <button class="submit" @click="submitParticipation">Подтверждаю</button>
          </div>
        </div>
      </div>
    </div>
    <div class="participation-popup" v-if="deleteModal">
      <div class="participation-popup-form">
        <div class="participation-popup-form-wrapper">
          <h1 class="title">Удалить заявку на участие в торгах?</h1>
          <div class="participation-popup-lot">
            <div class="lot-title"> {{ choosenLot.lot.name }} </div>
            <div class="popup-lot-body">
              <div class="popup-lot-body-price">
                <div class="popup-lot-body-price-start">
                <span class="popup-lot-body-label">Начальная цена</span>
                <span class="popup-lot-body-value"> {{ choosenLot.lot.price_start }} BYN </span>
              </div>
              <div class="popup-lot-body-price-deposit">
                <span class="popup-lot-body-label">Задаток</span>
                <span class="popup-lot-body-value">{{ choosenLot.lot.deposit }} BYN</span>
              </div>
              </div>
            </div>
          </div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="deleteModal = !deleteModal">Отмена</button>
            <button class="submit" @click="deleteRequest">Удалить</button>
          </div>
        </div>
      </div>
    </div>
    <div class="customer-request-wrapper">
      <div class="title">
        <h1>Мои заявки <span class="user-id">(id: {{ this.$store.state.auth.userData.id }})</span></h1>
        <span class="subtitle">Заявки на участие в торгах</span>
      </div>
      <div v-if="lots.length > 0" class="requests-table">
        <div class="requests-table-wrapper">
          <div class="table-row first">
            <span class="number">№ лота</span>
            <span class="name">Название лота</span>
            <span class="starts-at">Начало торгов</span>
            <span class="status">Статус</span>
            <span class="confirm-status">Cтатус заявки</span>
            <span class="delete"></span>
          </div>
          <div class="table-row" v-for="( lot, index ) in lots" :key="index">
              <span v-if="lot.lot" class="number">{{ lot.lot.lot_number }}</span>
              <nuxt-link v-if="lot.lot" :to="{name: 'auctions-id', params: { id: lot.lot.id }}" class="name">{{ lot.lot.name }}</nuxt-link>
              <span v-if="lot.lot" class="starts-at">
                {{ moment((parseInt(lot.auction.start_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}
              </span>
              <span v-if="lot.lot" class="status">{{ lot.lot.status }}</span>
              <span v-if="lot.lot && (lot.confirmed_admin === 0 && (lot.lot.status === 'Предстоящие' || lot.lot.status === 'Повторные'))" class="confirm-status">Ожидает подтверждения администратором</span>
              <span @click="showParticipationPopup(lot)" v-if="lot.lot && (lot.confirmed_admin === 1 && lot.confirmed_user === 0 && (lot.lot.status === 'Предстоящие' || lot.lot.status === 'Повторные'))" class="confirm-status link">Ожидает вашего подтверждения</span>
              <span v-else class="confirm-status">Подтверждено</span>
              <span v-if="lot.lot && (lot.lot.status === 'Предстоящие' || lot.lot.status === 'Текущие' || lot.lot.status === 'Повторные')" @click="showDeleteModal(index)" class="delete"><x-circle-icon/></span>
              <span v-else class="delete"></span>
          </div>
        </div>
      </div>
      <div v-else class="no-lots-requests">Нет подтвержденных заявок на участие в аукционе.</div>
      <div class="find-more">
        <nuxt-link :to="{ name: 'auctions' }">
          <button class="find-more-button">Найти еще аукционы <arrow-right-icon/></button>
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ArrowRightIcon, XCircleIcon } from 'vue-feather-icons'
import moment from 'moment'

export default {

  data: () => ({
    deleteModal: false,
    lots: [],
    choosenLot: {},
    moment: moment,
    openParticipationPopup: false,
  }),
  components: {
    ArrowRightIcon,
    XCircleIcon,
    LoadingSpinner: () => import('~/components/LoadingSpinner')
  },

  async fetch() {
    const data = await this.$axios.get(`https://realtorgi.by/admin/api/admin/user_applications/${this.$store.state.auth.userData.id}`)
    
    this.lots = data.data.data.data
    console.log(this.lots)
  },

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
    showParticipationPopup(lot) {
      this.choosenLot = lot
      this.openParticipationPopup = !this.openParticipationPopup
    },
    submitParticipation() {
      this.$axios.put(`https://realtorgi.by/admin/api/admin/auctionConfirm/${this.choosenLot.id}`, {
        user_id: this.$store.state.auth.userData.id
      }).then(() => {
        this.openParticipationPopup = !this.openParticipationPopup
        this.$router.push({ name: 'auctions-id', params: { id: this.choosenLot.lot.id, } })
      })
    },
    deleteRequest () {
      this.$axios.delete(`https://realtorgi.by/admin/api/admin/auctionConfirm/${this.choosenLot.id}`).then ((response) => {
        this.choosenLot = {}
        this.deleteModal = false
        this.$fetch()
      })
      
    },
    showDeleteModal (index) {
      this.deleteModal = !this.deleteModal
      this.choosenLot = this.lots[index];
    }

  },

  head() {
    return {
      title: 'Покупателю',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.customer-request {
  background-color: #fff;
  margin: 0 1rem 3rem 1rem;
  border-radius: 10px;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    padding: 2rem;
    .title {
      margin-bottom: 2rem;
      .subtitle {
        font-size: 0.9rem;
      }
      .user-id {
        color: $text-color-2;
        font-size: 0.8rem;
        font-weight: 500;
      }
    }
    .requests-table {
      display: flex;
      flex-direction: column;
      margin-bottom: 2rem;
      overflow: scroll;
      &-wrapper {
        display: flex;
        flex-direction: column;
        width: 80rem;
        max-height: 80rem;
        .table-row {
          display: flex;
          width: 100%;
          justify-content: space-between;
          align-items: center;
          text-align: center;
          min-height: 3rem;
          border-bottom: 1px solid $border-color;
          .number {
            width: 5rem;
          }
          .name {
            width: 20rem;
            color: $link-color;
            &:hover {
              text-decoration: underline;
            }
          }
          .participants {
            width: 10rem;
          }
          .starts-at {
            width: 12rem;
          }
          .status {
            width: 15rem;
          }
          .confirm-status {
            width: 15rem;
            cursor: pointer;
            cursor: pointer;
          }
          .link {
            color: $link-color;
          }
          .delete {
            width: 3rem;
            color: $error-color; 
          }
        }
        .first {
          opacity: 0.7;
          .confirm-status {
            color: $text-color-1;
          }
          .name {
            color: $text-color-1;
            text-decoration: none !important;
          }
        }
      }
    }
    .no-lots-requests {
      color: $text-color-2;
      margin-bottom: 1rem;
    }
    .find-more {
      display: flex;
      width: 100%;
      justify-content: flex-end;
      &-button {
        display: flex;
        @include button-active;
        svg {
          margin-left: 0.5rem;
        }
      }
      &-button:hover {
        @include button-rounded;
      }
    }
  }
}
.participation-popup {
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 50;
    height: 100vh;
    width: 100vw;
    left: 0;
    top: 0;
    .participation-popup-form {
      padding: 2rem;
      background-color: #fff;
      border-radius: 10px;
      box-shadow:
        0 2.8px 2.2px rgba(0, 0, 0, 0.024),
        0 6.7px 5.3px rgba(0, 0, 0, 0.038),
        0 12.5px 10px rgba(0, 0, 0, 0.05),
        0 22.3px 17.9px rgba(0, 0, 0, 0.062),
        0 41.8px 33.4px rgba(0, 0, 0, 0.066),
        0 100px 80px rgba(0, 0, 0, 0.08);
      .title {
        margin-bottom: 1rem;
      }
      .subtitle {
        font-size: 0.9rem;
        margin-bottom: 2rem;
        opacity: 0.8;
      }
      .lot-title {
        margin-bottom: 1rem;
        border-top: 1px solid $border-color;
        padding-top: 0.5rem;
      }
      .popup-lot-body {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid $border-color;
        &-image {
          width: 10rem;
          height: 5rem;
          margin-right: 1rem;
          img {
            width: 100%;
            height: 100%;
            object-fit: cover;
          }
        }
        &-price {
          display: flex;
          align-items: center;
          &-start {
            display: flex;
            flex-direction: column;
            margin-right: 1rem;
            .popup-lot-body-value {
              width: fit-content;
              background-color: #777777;
              color: #fff;
              font-size: 1.2rem;
              font-weight: 500;
              padding: 0.3rem 0.8rem;
            }
            .popup-lot-body-label {
              font-size: 0.9rem;
              opacity: 0.9;
              margin-bottom: 0.2rem;
            }
          }
          &-deposit {
            display: flex;
            flex-direction: column;
            .popup-lot-body-value {
              width: fit-content;
              background-color: #777777;
              color: #fff;
              font-size: 1.2rem;
              font-weight: 500;
              padding: 0.3rem 0.8rem;
            }
            .popup-lot-body-label {
              font-size: 0.9rem;
              opacity: 0.9;
              margin-bottom: 0.2rem;
            }
          }
        }
      }
      .remember-checkbox {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        input {
          margin-right: 0.5rem;
          background-color: $background-color-1;
          border: 1px solid $border-color;
          width: 1rem;
          margin-top: 1px;
          cursor: pointer;
        }
        .link {
          color: $link-color;
        }
        .link:hover {
          text-decoration: underline;
        }
      }
      .participation-popup-buttons {
        display: flex;
        justify-content: flex-end;
        .cancel {
          margin-right: 1rem;
          outline: none;
        }
        .submit {
          @include button-active;
        }
        .submit:hover {
          @include button-rounded;
        }
      }
    }
  }

@media (max-width: 375px) {
  .customer-request {
    margin: 0 0 3rem 0;
  }
}
</style>