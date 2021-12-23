<template>
  <header>
    <!-- <div class="notifications">
      <div class="notifications-list">
        <notification class="hidden-notification" v-for="(notification, index) in this.$store.state.auth.userData.notifications" :key="index" :notification="notification"/>
      </div>
    </div> -->
    <div v-show="orginizeAuctionOpen" class="orginize-auction"  @click="orginizeAuctionOpen = !orginizeAuctionOpen">
      <div class="orginize-auction-form" @click.stop>
        <form class="orginize-auction-form-wrapper" onsubmit="return false">
          <h1 class="title">Организуйте торги</h1>
          <div class="subtitle">Оставьте ваши контактные данные и организатор свяжется с Вами!</div>
          <div class="orginize-auction-forms">
            <div class="name-input input">
              <div class="input-wrapper">
                <label for="name">Имя:</label>
                <input id="name" type="text" v-model="name" required minlength="2">
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
    <div class="header-wrapper">
      <nav class="large-navigation">
        <div class="navigation-links">
          <div class="logo">
            <nuxt-link :to="{ name: 'index' }">
              <img src="~/assets/img/logo.png" alt="">
            </nuxt-link>
          </div>
          <div class="navigation-links-wrapper">
              <nuxt-link v-for="menuItem in menu" :key="menuItem.id" :to="menuItem.to" class="navigation-link">
                <span>{{ menuItem.title }}</span>
              </nuxt-link>
          </div>
        </div>
        <div class="header-info">
          <div class="header-contacts">
            <span class="contacts-title">
                Задай вопрос организатору
            </span>
            <div class="contacts-socials">
              <a href="tel:+375172566135" class="phone icon">
                <phone-icon stroke-width="1.5" size="22"/>
              </a>
              <a href="mailto:auction@expertiza.by" class="mail icon">
                <mail-icon stroke-width="1.5" size="22"/>
              </a>
              <a href="viber://chat?number=%2B375255498622" target="_blank" class="viber icon">
                <viber-icon />
              </a>
              <a href="https://t.me/%2B375255498622" target="_blank" class="telegram icon">
                <telegram-icon />
              </a>
            </div>
          </div>
          <div @click="orginizeAuctionOpen = !orginizeAuctionOpen" class="make-auction-button">
            <plus-icon/>
            Организовать торги
          </div>
          <div class="user-avatar" v-if="this.$store.state.auth.authorized === true" @click="openProfileNavbar = !openProfileNavbar">
            <img v-if="this.$store.state.auth.userData.image !== ''" :src="this.$store.state.auth.userData.image" />
            <img v-if="this.$store.state.auth.userData.image === ''" src="~/assets/img/user-avatar.png" />
            <div v-if="this.$store.state.auth.userData.unreadNotifications > 0" class="notification-status"><span>{{ this.$store.state.auth.userData.unreadNotifications }}</span></div>
            <the-navbar-profile v-show="openProfileNavbar" class="header-profile-menu" @click="openProfileNavbar = false"/>
          </div>
          <nuxt-link :to="{ name: 'auth' }" v-if="this.$store.state.auth.authorized === false" class="sign-in-button">
            Личный кабинет
          </nuxt-link>
        </div>
      </nav>
      <nav class="mobile-navigation">
        <div class="menu-icon" @click="openMenu = !openMenu" :class="{ open: openMenu }">
          <span :class="{ open: openMenu }"></span>
          <span :class="{ open: openMenu }"></span>
          <span :class="{ open: openMenu }"></span>
        </div>
        <div class="logo">
          <nuxt-link :to="{ name: 'index' }">
            <img src="~/assets/img/logo.png" alt="">
          </nuxt-link>
        </div>
        <div class="actions">
          <div @click="orginizeAuctionOpen = !orginizeAuctionOpen" class="make-auction-button">
            <plus-icon/>
            Организовать торги
          </div>
          <div class="user-avatar" v-if="this.$store.state.auth.authorized === true" @click="openProfileNavbar = !openProfileNavbar">
            <img v-if="this.$store.state.auth.userData.image !== ''" :src="this.$store.state.auth.userData.image" />
            <img v-if="this.$store.state.auth.userData.image === ''" src="~/assets/img/user-avatar.png" />
            <the-navbar-profile v-show="openProfileNavbar" class="header-profile-menu" @click.stop/>
            <div v-if="this.$store.state.auth.userData.unreadNotifications > 0" class="notification-status"><span>{{ this.$store.state.auth.userData.unreadNotifications }}</span></div>
          </div>
          <nuxt-link :to="{ name: 'auth' }" v-if="this.$store.state.auth.authorized === false" class="sign-in-button">
            Личный кабинет
          </nuxt-link>
          <nuxt-link :to="{ name: 'auth' }" v-if="this.$store.state.auth.authorized === false" class="sign-in-button-mobile">
            Войти
          </nuxt-link>
        </div>
        <div class="menu-navigation" v-show="openMenu" @click="openMenu = false">
          <nuxt-link
            v-for="menuItem in mobileMenu" :key="menuItem.id"
            :to="menuItem.to"
            class="menu-navigation-link"
            >
            {{menuItem.title}}
          </nuxt-link>
          <div class="menu-navigation-link" v-if="this.$store.state.auth.authorized === true" @click="orginizeAuctionOpen = !orginizeAuctionOpen">Организовать аукцион</div>
        </div>
      </nav>
    </div>
  </header>
</template>
<script>
import TheNavbarProfile from '~/components/TheNavbarProfile'
import ViberIcon from '~/assets/img/icons/viber.vue';
import TelegramIcon from '~/assets/img/icons/telegram.vue';
import clickOutside from 'vue-click-outside'
import moment from 'moment'
import Notification from '~/components/Notification';
import { PlusIcon, PhoneCallIcon, SmartphoneIcon, MailIcon, FacebookIcon, InstagramIcon, PhoneIcon } from 'vue-feather-icons'

export default {
  directives: {
    clickOutside,
  },
  components: {
    PlusIcon, PhoneCallIcon, SmartphoneIcon, MailIcon, FacebookIcon, InstagramIcon, TelegramIcon, ViberIcon, PhoneIcon,
    TheNavbarProfile,
    Notification
  },
  data: () => ({
    appName: 'Auctions',
    openMenu: false,
    profile: false,
    orginizeAuctionOpen: false,
    mobileMenuOpen: false,
    openProfileNavbar: false,
    name: '',
    phone: '',
    notifications: [],
    moment: moment,
    menu: [
      {
        title: 'Аукционы',
        to: { name: 'auctions' },
      },
      {
        title: 'Как участвовать',
        to: { name: 'participate' },
      },
      {
        title: 'О площадке',
        to: { name: 'about' },
      },
      {
        title: 'Контакты',
        to: { name: 'contacts' },
      },
    ],
    mobileMenu: [
      {
        title: 'Главная',
        to: { name: 'index' },
      },
    ]
  }),
  methods: {
    submit(e) {
      if (this.name.length > 1 && this.phone.length > 5) {
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/feedback', {
          name: this.name,
          phone: this.phone
        })
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Заявка на организацию торгов принята.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          'text': `Ваша заявка на организацию торгов была принята. В скором времени администратор площадки с Вами свяжется`,
        })
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
          user_id: this.$store.state.auth.userData.id,
          title: 'Заявка на организацию торгов принята.',
          text: `Ваша заявка на организацию торгов была принята. В скором времени администратор площадки с Вами свяжется`
        })
        this.orginizeAuctionOpen = !this.orginizeAuctionOpen;
        this.name = '';
        this.phone = '';
      }
    }
  },
  mounted() {
    this.mobileMenu.push(...this.menu)
  }
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

header {
  position: fixed;
  backdrop-filter: blur(15px);
  width: 100%;
  z-index: 20;
  background-color: rgba(240,240,240,0.8);
  display: flex;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
  justify-content: center;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.024),
    0 6.7px 5.3px rgba(0, 0, 0, 0.038),
    0 12.5px 10px rgba(0, 0, 0, 0.05),
    0 22.3px 17.9px rgba(0, 0, 0, 0.062),
    0 41.8px 33.4px rgba(0, 0, 0, 0.066),
    0 100px 80px rgba(0, 0, 0, 0.08);
    .notifications {
      position: fixed;
      right: 1rem;
      top: 6rem;
      &-list {
        display: flex;
        flex-direction: column;
      }
    }
  .orginize-auction {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 50;
    height: 100vh;
    width: 100vw;
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
  .header-wrapper {
    padding: 0.75rem 2rem;
    margin: 0 1rem;
    width: 100%;
    .mobile-navigation {
      display: none;
    }
    .large-navigation {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      .navigation-links {
        display: flex;
        align-items: center;
        .logo {
          width: 10rem;
          margin-right: 1rem;
          img {
            width: 100%;
          }
        }
        &-wrapper {
          .navigation-link {
            padding: 0.5rem 1rem;
            background-color: transparent;
            color: $text-color-1;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            cursor: pointer;
            transition: .2s ease-in-out;
          }
          .navigation-link:hover {
            background: rgba(0, 0, 0, 0.1)
          }
          .navigation-link:focus {
            background-color: $text-color-1;
            color: #ffffff;
            box-shadow:
            0 2.8px 2.2px rgba(0, 0, 0, 0.034),
            0 6.7px 5.3px rgba(0, 0, 0, 0.048),
            0 12.5px 10px rgba(0, 0, 0, 0.06),
            0 22.3px 17.9px rgba(0, 0, 0, 0.072),
            0 41.8px 33.4px rgba(0, 0, 0, 0.086),
            0 100px 80px rgba(0, 0, 0, 0.12);
          }
        }
      }
      .header-info {
        display: flex;
        align-items: center;
        .header-contacts {
          display: flex;
          flex-direction: column;
          align-items: flex-end;
          margin-right: 1rem;
          .contacts-title {
            font-size: 0.9rem;
          }
          .contacts-socials {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            margin-top: 0.5rem;
            .icon {
              height: 1.5rem;
              border-right: 1px solid $line-color;
              border-radius: 2px;
              margin-left: 0.5rem;
              padding-right: 0.5rem;
              opacity: 0.5;
              transition: .1s ease-in-out;
              svg {
                path {
                  transition: .1s ease-in-out;
                }
              }
            }
            .telegram {
              width: 2rem;
              height: 1.5rem;
              svg {
                width: 100%;
                height: 100%;
              }
            }
            .viber {
              width: 2rem;
              height: 1.5rem;
              svg {
                width: 100%;
                height: 100%;
              }
            }
            .icon:hover {
              opacity: 1;
            }
            .icon:last-child {
              border-right: none;
            }
          }
        }
        .user-avatar {
          margin-left: 2rem;
          cursor: pointer;
          position: relative;
          display: flex;
          img {
            width: 2.5rem;
            height: 2.5rem;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
          }
          .notification-status {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 1rem;
            width: 1rem;
            border-radius: 50%;
            background-color: $link-color;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 600;
            right: -0.25rem;
          }
          .header-profile-menu {
            position: absolute;
            border-radius: 10px;
            top: 4rem;
            right: 0;
            z-index: 4;
            box-shadow:
              0 2.8px 2.2px rgba(0, 0, 0, 0.034),
              0 6.7px 5.3px rgba(0, 0, 0, 0.048),
              0 12.5px 10px rgba(0, 0, 0, 0.06),
              0 22.3px 17.9px rgba(0, 0, 0, 0.072),
              0 41.8px 33.4px rgba(0, 0, 0, 0.086),
              0 100px 80px rgba(0, 0, 0, 0.12);
          }
        }
        .make-auction-button {
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
        .make-auction-button:hover {
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
        .sign-in-button {
          margin-left: 1rem;
          padding: 0.45rem 1rem;
          background-color: $link-color;
          color: #ffffff;
          border: 2px solid $link-color;
          border-radius: 25px;
          font-size: 0.95rem;
          font-weight: 500;
          outline: none;
          box-shadow:
            0 2.8px 2.2px rgba(0, 0, 0, 0.034),
            0 6.7px 5.3px rgba(0, 0, 0, 0.048),
            0 12.5px 10px rgba(0, 0, 0, 0.06),
            0 22.3px 17.9px rgba(0, 0, 0, 0.072),
            0 41.8px 33.4px rgba(0, 0, 0, 0.086),
            0 100px 80px rgba(0, 0, 0, 0.12);
          transition: .2s ease-in-out;
        }
      }
    }
    .mobile-navigation {
      .sign-in-button-mobile {
        display: none;
      }
      .menu-navigation {
        display: none;
      }
    }
  }
}
@media (max-width: 1372px) {
  .header-contacts {
    display: none !important;
  }
}
@media (max-width: 1165px) {
  header {
    .header-wrapper {
      .large-navigation {
        display: none;
        .navigation-links {
          &-wrapper {
            display: none;
          }
        }
        .header-info {
          display: flex;
          align-items: center;
          .header-contacts {
            display: none;
          }
          .make-auction-button {
            font-size: 0.75rem;
            svg {
              height: 1rem;
              width: 1rem;
            }
          }
          .sign-in-button {
            font-size: 0.75rem;
          }
        }
      }
      .mobile-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        .logo {
          img {
            width: 7.5rem !important;
          }
        }
        .actions {
          display: flex;
          align-items: center;
          .sign-in-button {
            padding: 0.45rem 1rem;
            background-color: $link-color;
            color: #ffffff;
            border: 2px solid $link-color;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            box-shadow:
              0 2.8px 2.2px rgba(0, 0, 0, 0.034),
              0 6.7px 5.3px rgba(0, 0, 0, 0.048),
              0 12.5px 10px rgba(0, 0, 0, 0.06),
              0 22.3px 17.9px rgba(0, 0, 0, 0.072),
              0 41.8px 33.4px rgba(0, 0, 0, 0.086),
              0 100px 80px rgba(0, 0, 0, 0.12);
            transition: .2s ease-in-out;
          }
          .make-auction-button {
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
          .make-auction-button:hover {
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
          .user-avatar {
            cursor: pointer;
            position: relative;
            display: flex;
            justify-content: center;
            .notification-status {
              position: absolute;
              display: flex;
              justify-content: center;
              align-items: center;
              height: 1rem;
              width: 1rem;
              border-radius: 50%;
              background-color: $link-color;
              color: #fff;
              font-size: 0.6rem;
              font-weight: 600;
              right: -0.25rem;
            }
            img {
              width: 2.5rem;
              height: 2.5rem;
              object-fit: cover;
              border-radius: 50%;
              border: 2px solid #fff;
            }
            .header-profile-menu {
              position: absolute;
              border-radius: 10px;
              right: 0;
              top: 4rem;
              z-index: 4;
              box-shadow:
                0 2.8px 2.2px rgba(0, 0, 0, 0.034),
                0 6.7px 5.3px rgba(0, 0, 0, 0.048),
                0 12.5px 10px rgba(0, 0, 0, 0.06),
                0 22.3px 17.9px rgba(0, 0, 0, 0.072),
                0 41.8px 33.4px rgba(0, 0, 0, 0.086),
                0 100px 80px rgba(0, 0, 0, 0.12);
            }
          }
        }
        .menu-icon {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          width: 2rem;
          height: 1.25rem;
          cursor: pointer;
          span {
            display: block;
            height: 3px;
            width: 100%;
            border-radius: 10px;
            background-color: $text-color-1;
            transition: .2s ease-in-out;
          }
          span:nth-child(1) {
            -webkit-transform-origin: left center;
            -moz-transform-origin: left center;
            -o-transform-origin: left center;
            transform-origin: left center;
          }

          span:nth-child(2) {
            -webkit-transform-origin: left center;
            -moz-transform-origin: left center;
            -o-transform-origin: left center;
            transform-origin: left center;
          }

          span:nth-child(3) {
            -webkit-transform-origin: left center;
            -moz-transform-origin: left center;
            -o-transform-origin: left center;
            transform-origin: left center;
          }

          .open:nth-child(1) {
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            transform: rotate(45deg) translateY(-4px);
          }

          .open:nth-child(2) {
            transform: translateX(-30px);
            opacity: 0;
          }

          .open:nth-child(3) {
            -webkit-transform: rotate(-45deg);
            -moz-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            transform: rotate(-45deg) translateY(4px);
          }
        }
        .menu-navigation {
          position: absolute;
          display: flex;
          flex-direction: column;
          background-color: #fff;
          border-radius: 10px;
          padding: 1rem 2rem 1rem 1rem;
          top: 5rem;
          z-index: 10;
          left: 3rem;
          box-shadow:
            0 2.8px 2.2px rgba(0, 0, 0, 0.034),
            0 6.7px 5.3px rgba(0, 0, 0, 0.048),
            0 12.5px 10px rgba(0, 0, 0, 0.06),
            0 22.3px 17.9px rgba(0, 0, 0, 0.072),
            0 41.8px 33.4px rgba(0, 0, 0, 0.086),
            0 100px 80px rgba(0, 0, 0, 0.12);
          .menu-navigation-link {
            margin-bottom: 0.5rem;
            transition: .2s ease-in-out;
            cursor: pointer;
          }
          .menu-navigation-link:last-child {
            margin-bottom: 0;
          }
          .menu-navigation-link:hover {
            transform: translateX(0.5rem);
          }
        }
      }
    }
  }
}
@media (max-width: 768px) {
  header {
    .header-wrapper {
      .mobile-navigation {
        .actions {
          .make-auction-button {
            display: none;
          }
        }
        .logo {
          img {
            width: 10rem;
          }
        }
      }
    }
  }
}
@media (max-width: 476px) {
  header {
    .header-wrapper {
      .mobile-navigation {
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
    }
  }
}

@media (max-width: 375px) {
  header {
    .header-wrapper {
      margin: 0;
    }
  }
}
</style>
