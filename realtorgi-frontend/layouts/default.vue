<template>
  <loading-spinner v-if="!loaded"/>
  <div v-else class="app-wrapper" @click="eventClickOnSubProfileManu">
    <notifications group="user-notifications" :duration='10000'/>
    <the-header/>
    <main>
      <nuxt/>
    </main>
    <the-footer/>
  </div>
</template>

<style lang="scss">
@import "~/assets/scss/common.scss";

#__nuxt {
  height: 100vh;
}

#__layout {
  height: 100vh;
}

html {
  line-height: 1.5;
  -webkit-text-size-adjust: 100%;
  font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
  -moz-tab-size: 4;
  tab-size: 4;
  font-size: 16px;
  font-synthesis: none;
  -moz-font-feature-settings: 'kern';
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body {
  margin: 0;
  padding: 0;
  background-color: rgba(0, 0, 75, 0.1);
  min-height: 100vh;
  color: $text-color-1;
  font-family: inherit;
  line-height: inherit;
  overflow-x: hidden;
}

b,
strong {
  font-weight: bolder;
}

img, video {
  max-width: 100%;
  height: auto;
}

blockquote,
dl,
dd,
h1,
h2,
h3,
h4,
h5,
h6,
hr,
figure,
p,
pre {
  margin: 0;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-size: inherit;
  font-weight: inherit;
}

ol,
ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

img,
svg,
video,
canvas,
audio,
iframe,
embed,
object {
  display: block;
  vertical-align: middle;
}

*,
::before,
::after {
  box-sizing: border-box;
  border-width: 0;
  border-style: solid;
  border-color: theme('borderColor.default', currentColor);
}

button, input, optgroup, select, textarea {
  padding: 0;
  line-height: inherit;
  font-size: inherit;
  font-weight: inherit;
  font-family: inherit;
  color: inherit;
  background-image: none;
  font-size: 100%;
}

select {
  -webkit-appearance: none;
  -moz-appearance: none;
  background: url('~/assets/img/icons/chevron-down.svg') 95% no-repeat;
  background-color: none;
  background-position: right;
  background-size: 14px;
  padding-right: 1.5rem !important;
  padding-left: 0 !important;
  cursor: pointer;
}

[type=button], [type=submit], button {
  -webkit-appearance: button;
}

button {
  background-color: inherit;
}

button, select {
  text-transform: none;
}

a {
  padding: 0;
  margin: 0;
  color: inherit;
  text-decoration: inherit;
}

path {
  width: 100%;
  height: 100%;
}

audio, canvas, embed, iframe, img, object, svg, video {
  display: block;
  vertical-align: middle;
}

button {
  border: none;
  cursor: pointer;
}

.app-wrapper {
  overflow: hidden;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  color: $text-color-1;
}

main {
  padding: 1rem 2rem;
  flex-grow: 1;
  margin-top: 6rem;
}

@media (max-width: 375px) {
  main {
    padding: 1rem 0;
  }
}
</style>

<script>
import TheHeader from '~/components/Header';
import TheFooter from '~/components/TheFooter';

export default {
  components: {
    TheHeader,
    TheFooter,
    LoadingSpinner: () => import('~/components/LoadingSpinner')
  },
  data: () => ({
    loaded: false,
  }),
  methods: {
    eventClickOnSubProfileManu(e) {
      let profileWrapper = document.querySelector(".mobile-navigation .profile-wrapper.header-profile-menu");

      if (!profileWrapper) {
        return;
      }

      if (!e.target.closest(".actions") || e.target.closest(".profile-menu-link")) {
        profileWrapper.style.display = "none";
      } else {
        profileWrapper.style.display = "block"
      }
    },
  },
  mounted() {
    if (localStorage.getItem('AUTH_TOKEN')) {
      var decodedData = (localStorage.getItem('AUTH_TOKEN')).split('9c1eEB325C3Cf308A61eE5B5D6f699BCb668E608')
      this.$axios.$post(process.env.API_URL + '/admin/api/admin/user/login', {
        email: atob(decodedData[0]),
        password: atob(decodedData[1])
      }).then(response => {
        this.$store.dispatch('changeAuthStatus', {
          authStatus: true,
          userData: {
            active: response.data.email_verified_at,
            id: response.data.id,
            login: response.data.login,
            email: response.data.email,
            firstName: response.data.first_name,
            lastName: response.data.last_name,
            image: '',
            profile: {
              bank_details: response.data.bank_details,
              egr_date: response.data.egr_date,
              full_name: response.data.full_name,
              inn: response.data.inn,
              legal_entity: response.data.legal_entity,
              manager_data: response.data.manager_data,
              passportDate: response.data.passportDate,
              passport_issuer: response.data.passport_issuer,
              passport_number: response.data.passport_number,
              passport_personal: response.data.passport_personal,
              phone: response.data.phone,
              place_of_residence: response.data.place_of_residence,
              registration_address: response.data.registration_address,
              residence_address: response.data.residence_address,
              short_name: response.data.short_name,
              type_user: response.data.type_user,
              files: response.data.files
            },
            notifications: [],
            unreadNotifications: 0,
          }

        })
        this.$axios.$get(process.env.API_URL + `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
          .then((response) => {
            this.$store.dispatch('getNotifications', {
              notifications: response.data.data
            })
            this.$store.dispatch('countUnreadNotifications')
            this.loaded = true
          })
      })
    } else {
      this.loaded = true
    }
  }
}
</script>
