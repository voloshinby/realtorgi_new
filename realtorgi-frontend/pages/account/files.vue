<template>
  <div>
    <loading-spinner v-if="$fetchState.pending"/>
    <div class="grid-files-wrapper">
      <div class="grid-files">
        <h1>Просмотр и управление загруженными документами</h1>
        <div class="requests-table">
          <div class="requests-table-wrapper">
            <div class="table-row first">
              <span class="name">Название файла</span>
              <span class="starts-at">Дата загрузки</span>
              <span class="delete"></span>
            </div>
            <div class="table-row" :data-id="file.id" v-for="file in files" :key="file.id">
              <span class="number">{{ file.name }}</span>
              <span class="starts-at">
                {{ moment(file.created_at).format('YYYY.MM.DD') }}
              </span>
              <span class="delete">
               <a @click="deleteFile(file.id)" style="cursor: pointer"> Удалить</a> / <a :href="file.path" target="_blank" style="color: blue">Просмотр</a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import {ArrowRightIcon, PlusIcon} from 'vue-feather-icons'
import moment from 'moment'

export default {
  components: {
    ArrowRightIcon,
    PlusIcon,
    LoadingSpinner: () => import('~/components/LoadingSpinner')
  },

  data: () => ({
    requestedAuctions: [],
    moment: moment,
    files: [],
    lot: 'Выберите лот',
  }),
  methods: {
    submitComment() {
      if (this.comment.length > 1 && this.lot !== 'Выберите лот') {
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/lot/comment/' + this.lot, {
          comment: this.comment,
          lot: this.lot,
          user: this.$store.state.auth.userData.id,
        })
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Новый комментарий добавлен</div>`,
          'text': ``,
          'duration': 5000
        })
        this.sendCommentModal = !this.sendCommentModal;
        this.comment = '';
      }
    },
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
          'duration': 5000
        });
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
          user_id: this.$store.state.auth.userData.id,
          title: 'Заявка на организацию торгов принята.',
          text: `Ваша заявка на организацию торгов была принята. В скором времени администратор площадки с Вами свяжется`
        });
        this.orginizeAuctionOpen = !this.orginizeAuctionOpen
      }
    },
    deleteFile(id) {
      let alertSure = confirm('Вы уверены что хотите удалить файл?');

      if (alertSure) {
        this.$axios.post(process.env.API_URL + '/admin/api/admin/gallery/destroyFiles/' + id,
        ).then(function () {
         // document.querySelector("[data-id="+id+"]").remove();
          //$('#file_'+id).remove();
        })
          .catch(function (reason) {            console.log('reason', reason);
          });
      }

      location.reload();
    },
    loadFiles() {
      this.$axios.get(process.env.API_URL + `/admin/api/admin/user/${this.$store.state.auth.userData.id}/files`)
        .then( response => {
          this.files = response.data.data;
          //$('#file_'+id).remove();
        })
        .catch(function (e) {
          console.log(e);
        });
    },
  },
  async fetch() {
    this.loadFiles();
  },
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

  head() {
    return {
      title: 'Покупателю',
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.grid-files-wrapper {
  background-color: #fff;
  margin: 0 1rem 2rem 1rem;
  border-radius: 10px;
  box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
}

.grid-files {
  margin: 20px;
}

h1 {
  text-align: center;
  padding: 20px 0;
}

.requests-table {
  display: flex;
  flex-direction: column;
  margin-bottom: 2rem;
  overflow-x: auto;

  &-wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-height: 80rem;

    .table-row {
      width: 100%;
      text-align: center;
      min-height: 3rem;
      border-bottom: 1px solid $border-color;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-column-gap: 10px;

      .number {
        width: 5rem;
      }

      .name {
        width: 20rem;
        text-align: left;
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
      }

      .link {
        color: $link-color;
      }

      .delete {
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
</style>
