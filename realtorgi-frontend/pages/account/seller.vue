<template>
<loading-spinner v-if="$fetchState.pending" />
  <section v-else>
     <div v-show="orginizeAuctionOpen" class="orginize-auction"  @click="orginizeAuctionOpen = !orginizeAuctionOpen">
      <div class="orginize-auction-form" @click.stop>
        <form class="orginize-auction-form-wrapper" onsubmit="return false">
          <h1 class="title">Создать аукцион</h1>

          <div class="subtitle">Чтобы Связаться с представителем площадки позвоните на номер +375172566135, либо оставьте ваши контактные данные и организатор свяжется с Вами!</div>
          <div class="orginize-auction-forms">
            <div class="name-input input">
              <div class="input-wrapper">
                <label for="name">Имя:</label>
                <input id="name" type="text" v-model="name" required minlength="2">
              </div>
            </div>
            <div class="email-input input">
              <div class="input-wrapper">
                <label for="email">Email:</label>
                <input id="email" type="text" v-model="email" required minlength="6" maxlength="22">
              </div>
            </div>
            <div class="phone-input input">
              <div class="input-wrapper">
                <label for="phone">Телефон:</label>
                <input id="phone" type="text" v-model="phone" required minlength="6" maxlength="22">
              </div>
            </div>
          </div>
          <div class="orginize-auction-buttons">
            <button class="cancel" @click="orginizeAuctionOpen = !orginizeAuctionOpen">Отмена</button>
            <button class="submit" @click="submit">Отправить</button>
          </div>
        </form>
      </div>
    </div>
    <div class="warning">
      <div class="warning-wrapper">
        <div v-if="this.$store.state.auth.userData.profile.type_user === 'jur'" class="title">Юридическое лицо.</div>
        <div v-else-if="this.$store.state.auth.userData.profile.type_user === 'ip'" class="title">Индивидуальный предприниматель.</div>
        <div v-else-if="this.$store.state.auth.userData.profile.type_user === 'phys'" class="title">Физическое лицо.</div>
        <div v-else class="title">Для создания аукциона добавьте профиль.</div>
        <div class="add-profile">
          <nuxt-link v-if="
            this.$store.state.auth.userData.profile.type_user === 'jur' ||
            this.$store.state.auth.userData.profile.type_user === 'ip' ||
            this.$store.state.auth.userData.profile.type_user === 'phys'
            " :to="{ name: 'account-profiles-new' }" >
            <button class="add-profile-button">
              <plus-icon />Изменить профиль
            </button>
          </nuxt-link>
          <nuxt-link v-else :to="{ name: 'account-profiles-new' }" >
            <button class="add-profile-button">
              <plus-icon />Добавить профиль
            </button>
          </nuxt-link>
        </div>
      </div>
    </div>
    <div class="seller-auctions">
      <div class="seller-auctions-wrapper">
        <div class="title">
          <h1>Мои аукционы</h1>
        </div>
        <div v-if="requestedAuctions.length > 0" class="auctions-table">
          <div class="auctions-table-wrapper">
            <div class="table-row first">
              <span class="number">№</span>
              <span class="name">Название аукциона</span>
              <span class="starts-at">Дата проведения торгов</span>
              <span class="contact-person">Контактное лицо</span>
              <span class="auction-type">Тип аукциона</span>
            </div>
            <div class="table-row" v-for="auction in requestedAuctions" :key="auction.auction_number">
              <span class="number">{{ auction.auction_number }}</span>
              <span class="name">{{ auction.name }} </span>
              <span class="starts-at">{{ moment((parseInt(auction.starts_at)) * 1000).format('Do MMMM YYYY, HH:mm') }}</span>
              <span class="contact-person"><p>{{auction.contact_person}}</p><p>{{auction.seller_phone}}</p></span>
              <span v-if="auction.type === 'econom'" class="auction-type">Торги в результате экономической несостоятельности</span>
              <span v-if="auction.type === 'classic'" class="auction-type">Классические электронные торги</span>
            </div>
          </div>
        </div>
        <div v-else class="no-orginized-auctions">Не найдено аукционов, организованных Вами.</div>
        <div class="find-more">
          <div class="actions">
            <div @click="orginizeAuctionOpen = !orginizeAuctionOpen" class="auction-button">
              <plus-icon/>
              Создать аукцион
            </div>
            <!-- <div class="user-avatar" v-if="this.$store.state.auth.authorized === true" @click="openProfileNavbar = !openProfileNavbar">
              <img v-if="this.$store.state.auth.userData.image !== ''" :src="this.$store.state.auth.userData.image" />
              <img v-if="this.$store.state.auth.userData.image === ''" src="~/assets/img/user-avatar.png" />
              <the-navbar-profile v-show="openProfileNavbar" class="header-profile-menu" @click.stop/>
              <div v-if="this.$store.state.auth.userData.unreadNotifications > 0" class="notification-status"><span>{{ this.$store.state.auth.userData.unreadNotifications }}</span></div>
            </div> -->
          </div>
          <nuxt-link :to="{ name: 'auctions' }">
            <button class="find-more-button">Все аукционы <arrow-right-icon/></button>
          </nuxt-link>

        </div>
        <!-- <div @click="orginizeAuctionOpen = !orginizeAuctionOpen" class="make-auction-button">
            <plus-icon/>
            Организовать торги
        </div> -->

      </div>
    </div>


  </section>
</template>

<script>
import { ArrowRightIcon, PlusIcon } from 'vue-feather-icons'
import moment from 'moment'
import ru from 'moment/locale/ru'

export default {
  components: {
    ArrowRightIcon,
    PlusIcon,
    LoadingSpinner: () => import('~/components/LoadingSpinner')
  },

  data: () => ({
    requestedAuctions: [],
    name: '',
    email:'',
    phone: '',
    orginizeAuctionOpen: false,
    moment: moment,
  }),
  methods: {
    submit(e) {
      if (this.name.length > 1 && this.phone.length > 5 && this.email.length > 6) {
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/feedback', {
          name: this.name,
          phone: this.phone,
        });
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Заявка на создание аукциона принята.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          'text': `Ваша заявка на организацию торгов была принята. В скором времени администратор площадки с Вами свяжется`,
        });
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
          user_id: this.$store.state.auth.userData.id,
          title: 'Заявка на организацию торгов принята.',
          text: `Ваша заявка на организацию торгов была принята. В скором времени администратор площадки с Вами свяжется`
        });
        this.orginizeAuctionOpen = !this.orginizeAuctionOpen
        console.log(this.name);
        console.log(this.email);
        console.log(this.phone);

      }

    }
  },

  async fetch() {
    const data = await this.$axios.get(process.env.API_URL +  `/admin/api/admin/user/auctions/${this.$store.state.auth.userData.id}`)
    this.requestedAuctions = data.data.data.data
    // console.log(data.data.data.data)
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

  head() {
    return {
      title: 'Покупателю',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.warning {
  background-color: #fff;
  margin: 0 1rem 2rem 1rem;
  border-radius: 10px;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
    &-wrapper {
      display: flex;
      justify-content: space-between;
      padding: 2rem;
      align-items: center;
      .title {
        font-size: 1.2rem;
      }
      .add-profile {
        display: flex;
        &-button {
          display: flex;
          @include button-active;
          svg {
            margin-right: 0.5rem;
          }
        }
        &-button:hover {
          @include button-rounded;
        }
      }
    }
}

.seller-auctions {
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
    }
    .auctions-table {
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
            width: 15rem;
          }
          .participants {
            width: 10rem;
          }
          .starts-at {
            width: 12rem;
          }
          .contact-person {
            width: 15rem;
          }
          .auction-type {
            width: 15rem;
          }
        }
      }
      .first {
        opacity: 0.7;
      }
    }
    .no-orginized-auctions {
      color: $text-color-2;
      margin-bottom: 1rem;
    }
    .find-more {
      display: flex;
      width: 100%;
      justify-content: flex-end;
      &-button {
        display: flex;
        align-items: center;
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

@media (max-width: 375px) {
  .warning {
    margin: 0 0 2rem 0;
  }
  .seller-auctions {
    margin: 0 0 3rem 0;
  }
}


/*make auction in seller copy from header*/
.orginize-auction {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.8);
  z-index: 50;
  height: 100vh;
  width: 96vw;
  .orginize-auction-form {
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
      margin-bottom: 0.5rem;
    }
    .subtitle {
      margin-bottom: 1rem;
      max-width: 25rem;
      color: $text-color-2;
    }
    .orginize-auction-forms {
      margin-bottom: 2rem;
      .input {
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
        .input-wrapper {
          display: flex;
          justify-content: center;
          width: 100%;
          label {
          margin-right: 1rem;
          width: 10rem;
          text-align: right;
          }
          input {
            max-width: 18rem;
            margin-right: 3rem;
          }
        }
      }
    }
    .orginize-auction-buttons {
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

.auction-button {
  display: flex;
  align-items: center;
  padding: 0.45rem 1rem;
  background-color: transparent;
  color: $text-color-1;
  border: 2px solid $text-color-1;
  border-radius: 25px;
  font-size: 0.95rem;
  font-weight: 500;
  outline: none;
  cursor: pointer;
  transition: box-shadow .2s ease-in-out,  color .2s ease-in-out, background-color .2s ease-in-out;
  svg {
    margin-right: 0.25rem;
    path {
      fill: $text-color-1
    }
  }
  margin-right: 25px;
  margin-top: 2px;
}
.auction-button:hover {
  background-color: $text-color-1;
  color: #ffffff;
  box-shadow:
  0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
  svg {
    path {
      fill: #ffffff;
    }
  }
}


    // .mobile-navigation {
    //   display: none;
    // }
    // .large-navigation {
    //   display: flex;
    //   justify-content: space-between;
    //   align-items: center;
    //   width: 100%;
    //   .navigation-links {
    //     display: flex;
    //     align-items: center;
    //     .logo {
    //       width: 10rem;
    //       margin-right: 1rem;
    //       img {
    //         width: 100%;
    //       }
    //     }
    //     &-wrapper {
    //       .navigation-link {
    //         padding: 0.5rem 1rem;
    //         background-color: transparent;
    //         color: $text-color-1;
    //         border-radius: 25px;
    //         font-size: 0.95rem;
    //         font-weight: 500;
    //         outline: none;
    //         cursor: pointer;
    //         transition: .2s ease-in-out;
    //       }
    //       .navigation-link:hover {
    //         background: rgba(0, 0, 0, 0.1)
    //       }
    //       .navigation-link:focus {
    //         background-color: $text-color-1;
    //         color: #ffffff;
    //         box-shadow:
    //         0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    //         0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    //         0 12.5px 10px rgba(0, 0, 0, 0.06),
    //         0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    //         0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    //         0 100px 80px rgba(0, 0, 0, 0.12);
    //       }
    //     }
    //   }
    //   .header-info {
    //     display: flex;
    //     align-items: center;
    //     .header-contacts {
    //       display: flex;
    //       flex-direction: column;
    //       align-items: flex-end;
    //       margin-right: 1rem;
    //       .contacts-title {
    //         font-size: 0.9rem;
    //       }
    //       .contacts-socials {
    //         display: flex;
    //         width: 100%;
    //         align-items: center;
    //         justify-content: center;
    //         margin-top: 0.5rem;
    //         .icon {
    //           height: 1.5rem;
    //           border-right: 1px solid $line-color;
    //           border-radius: 2px;
    //           margin-left: 0.5rem;
    //           padding-right: 0.5rem;
    //           opacity: 0.5;
    //           transition: .1s ease-in-out;
    //           svg {
    //             path {
    //               transition: .1s ease-in-out;
    //             }
    //           }
    //         }
    //         .icon:hover {
    //           opacity: 1;
    //         }
    //         .icon:last-child {
    //           border-right: none;
    //         }
    //       }
    //     }
    //   }
    // }
    // .mobile-navigation {
    //   .sign-in-button-mobile {
    //     display: none;
    //   }
    //   .menu-navigation {
    //     display: none;
    //   }
    // }

// @media (max-width: 1372px) {
//   .header-contacts {
//     display: none !important;
//   }
// }
@media (max-width: 1165px) {
.auction-button {
            margin-right: 1rem;
            display: flex;
            align-items: center;
            padding: 0.45rem 1rem;
            background-color: transparent;
            color: $text-color-1;
            border: 2px solid $text-color-1;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            cursor: pointer;
            transition: box-shadow .2s ease-in-out,  color .2s ease-in-out, background-color .2s ease-in-out;
            svg {
              margin-right: 0.25rem;
              path {
                fill: $text-color-1
              }
            }
          }
.auction-button:hover {
  background-color: $text-color-1;
  color: #ffffff;
  box-shadow:
  0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
  svg {
    path {
      fill: #ffffff;
    }
  }
}


.auction-button {
  font-size: 0.75rem;
  svg {
    height: 1rem;
    width: 1rem;
  }
}

      // .mobile-navigation {
      //   display: flex;
      //   align-items: center;
      //   justify-content: space-between;
        .actions {
          display: flex;
          align-items: center;
        }
      // }

}
@media (max-width: 768px) {
  .actions {
    .auction-button {
      display: none;
    }
  }
}
@media (max-width: 476px) {
        .actions {
          .sign-in-button {
            display: none;
          }
          .sign-in-button-mobile {
            display: flex;
            color: $link-color;
          }
        }

}
</style>
