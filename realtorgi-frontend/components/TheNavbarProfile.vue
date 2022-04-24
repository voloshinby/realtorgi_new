<template>
  <div class="profile-wrapper">
    <div class="profile-data">
      <!-- <nuxt-link :to="{ name: 'account-profiles' }"> -->
        <img class="user-image" v-if="this.$store.state.auth.userData.image !== ''" :src="this.$store.state.auth.userData.image" />
        <img class="user-image" v-if="this.$store.state.auth.userData.image === ''" src="~/assets/img/user-avatar.png" />
      <!-- </nuxt-link> -->
      <div class="user-data">
        <span class="user-name">{{ this.$store.state.auth.userData.firstName }} {{ this.$store.state.auth.userData.lastName }} <span class="user-id">(id: {{ this.$store.state.auth.userData.id }})</span></span>
        <span class="user-email">{{ this.$store.state.auth.userData.email }}</span>

      </div>
    </div>
    <div class="profile-menu">
      <nuxt-link
        :to="{ name: 'account-notifications' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header notification-link">
            Уведомления
            <span v-if="this.$store.state.auth.userData.unreadNotifications > 0" class="notification-satus">{{ this.$store.state.auth.userData.unreadNotifications }}</span>
          </p>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{ name: 'account-profiles' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header">
            Профиль
          </p>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{ name: 'account-files' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header">
            Управление документами
          </p>
        </div>
      </nuxt-link>
    </div>
    <div class="profile-menu">
      <nuxt-link
        :to="{ name: 'account-customer' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header">
            Покупателю
          </p>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{ name: 'account-seller' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header">
            Продавцу
          </p>
        </div>
      </nuxt-link>
    </div>
    <div class="profile-menu">
      <nuxt-link
        :to="{ name: 'account-settings' }"
        class="profile-menu-link"
      >
        <div class="link-title">
          <p class="link-header">
            Настройки
          </p>
        </div>
      </nuxt-link>
    </div>
    <div class="profile-menu">
      <div class="profile-menu-link exit-button" @click="signOut()">
        <div class="link-title">
          <p class="link-header">
            Выйти
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  BellIcon,
  UsersIcon,
  SettingsIcon,
  ShoppingCartIcon,
  BriefcaseIcon,
} from 'vue-feather-icons'

export default {
  components: {
    BellIcon,
    UsersIcon,
    SettingsIcon,
    // eslint-disable-next-line vue/no-unused-components
    ShoppingCartIcon,
    // eslint-disable-next-line vue/no-unused-components
    BriefcaseIcon,
  },
  data: () => ({}),
  methods: {
    signOut() {
      this.$store.dispatch('changeAuthStatus', {
        authStatus: false,
        userData: {}
      })
      localStorage.removeItem('AUTH_TOKEN')
      this.$router.push('/')
    }
  }
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.profile-wrapper {
  padding: 1rem 1rem 0 1rem;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  width: 18rem;
  .profile-data {
    display: flex;
    align-items: center;
    border-bottom: 1px solid $border-color;
    padding-bottom: 1rem;
    margin-bottom: 0.5rem;
    .user-image {
      width: 4rem;
      height: 4rem;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 1rem;
    }
    .user-data {
      display: flex;
      flex-direction: column;
      .user-name {
        font-weight: 700;
        font-size: 1.2rem;
      }
      .user-email {
        color: $text-color-2;
        font-size: 0.875rem;
        max-width: 10rem;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .user-id {
        color: $text-color-2;
        font-size: 0.8rem;
        font-weight: 500;
      }
    }
  }
  .profile-menu {
    border-bottom: 1px solid $border-color;
    padding-bottom: 0.5rem;
    margin-bottom: 0.5rem;
    .profile-menu-link {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
      transition: .2s ease-in-out;
      .link-title {
        .link-header {
          font-size: 1rem;
          font-weight: 400;
          display: flex;
          align-items: center;
          .notification-satus {
            margin-left: 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.6rem;
            height: 1.2rem;
            width: 1.2rem;
            border-radius: 50%;
            background-color: $link-color;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
          }
        }
      }
    }

    .profile-menu-link:last-child {
      margin-bottom: 0;

    }
    .profile-menu-link:hover {
      transform: translateX(0.5rem);
    }
  }
  .exit-button {
    color: $error-color;
  }
  .profile-menu:last-child {
    margin-bottom: 0;
    border-bottom: none;
  }
}

@media (max-width: 1024px) {
  .notification-link {
    display: flex;
    flex-direction: row-reverse;
    .notification-satus {
      margin-right: 0.5rem;
      margin-left: 0;
    }
  }
  .profile-wrapper {
    display: flex;
    width: 12rem;
    right: 0;
    .profile-data {
      display: none;
    }
    .profile-menu {
      display: flex;
      width: 100%;
      justify-content: center;
      flex-direction: column;
      .profile-menu-link {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        @media (max-width: 500px) {
          justify-content: flex-start;
        }
      }
      .profile-menu-link:last-child {
        margin-bottom: 0;
      }
      .profile-menu-link:hover {
        transform: translateX(-0.5rem);
      }
    }
  }
}
</style>
