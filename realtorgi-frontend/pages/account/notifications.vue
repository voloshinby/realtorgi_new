<template>
  <loading-spinner v-if="$fetchState.pending" />
  <div v-else class="notifications">
    <div class="notifications-wrapper">
      <h1>Ваши уведомления</h1>
      <div class="no-notifications" v-if="this.$store.state.auth.userData.notifications.length === 0">
        Уведомлений нет.
      </div>
      <div class="notifications-list" v-if="this.$store.state.auth.userData.notifications.length > 0">
        <div class="notification" v-for="(notification, index) in paginatedData" :key="index">
          <div class="notification-header">
            <div v-if="notification.title" class="title">{{ notification.title }}</div>
            <div v-else class="title">Без заголовка</div>
            <div class="time">{{ moment((Date.parse(notification.created_at))).fromNow()}}</div>
          </div>
          <div class="notification-subtitle">
            {{ notification.text }}
          </div>
        </div>
      </div>
      <div class="notifications-pagination" v-if="this.$store.state.auth.userData.notifications.length > 10">
        <div class="pagination-number">
          <span>
            Страница {{ pageNumber + 1 }} из {{ pagesAmount }}
          </span>
        </div>
        <div class="pagination-arrows">
          <div class="left-arrow arrow" @click="prevPage" aria-label="Назад" :class="{'arrow-disabled': ((pageNumber + 1) === 1)}">
            <svg viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="right-arrow arrow" @click="nextPage" aria-label="Вперед" :class="{'arrow-disabled': ((pageNumber + 1) === pageCount)}">
            <svg viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment'

export default ({

  components: {
    LoadingSpinner: () => import('~/components/LoadingSpinner'),
  },

  data: () => ({
    moment: moment,
    pageNumber: 0,
    pageSize: 10,
    pagesAmount: 0,
  }),

  async fetch() {
    var data = await this.$axios.$get(process.env.API_URL +  `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
    this.$store.dispatch('getNotifications', {
      notifications: data.data.data
    })
    data = await this.$axios.$post(process.env.API_URL + '/admin/api/admin/notifications/update_all', {
      user_id: this.$store.state.auth.userData.id,
      status: 'old'
    }).then(() => {
      this.$axios.get(process.env.API_URL +  `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
      .then((response) => {
        this.$store.dispatch('getNotifications', {
          notifications: response.data.data.data
        })
        this.$store.dispatch('countUnreadNotifications')
      })
    })
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
  },

  computed: {
    pageCount(){
      let l = this.$store.state.auth.userData.notifications.length,
      s = this.pageSize;
      this.pagesAmount = Math.ceil(l/s)
      return this.pagesAmount
    },
    paginatedData(){
      const start = this.pageNumber * this.pageSize;
      const end = start + this.pageSize;
      return this.$store.state.auth.userData.notifications.slice(start, end);
    }
  },

  methods: {
    nextPage(){
      if (this.pageNumber !== (this.pageCount - 1)) {
        this.pageNumber++;
        window.scrollTo(0,0);
      }
    },
    prevPage(){
      if (this.pageNumber !== 0) {
        this.pageNumber--;
        window.scrollTo(0,0);
      }
    },
  },

})
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.notifications {
  background-color: #fff;
  margin: 0 1rem 3rem 1rem;
  border-radius: 10px;
  padding: 2rem;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    h1 {
      text-align: center;
    }
    .no-notifications {
      margin-top: 1rem;
    }
    .notifications-list {
      margin-top: 2rem;
      .notification {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 1rem 2rem;
        border-bottom: 1px solid $border-color;
        .notification-header {
          display: flex;
          width: 100%;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 0.5rem;
          .title {
            font-weight: 500;
            font-size: 1.2rem;
            width: 80%;
          }
          .time {
            color: $text-color-2;
            font-size: 0.8rem;
          }
        }
        .notification-subtitle {
          font-size: 0.9rem;
          color: $text-color-2;
          margin-right: 1rem;
          width: 80%;
          display: -webkit-box;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          text-overflow: ellipsis;
          overflow: hidden;
        }
      }
      .new {
        background-color: rgb(255, 252, 238);
      }
    }
    .notifications-pagination {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 1rem;
      margin-top: 2rem;
      .pagination-arrows {
        display: flex;
        .right-arrow {
          margin-left: 0.5rem;
        }
        .arrow {
          cursor: pointer;
          svg {
            width: 1.5rem;
            height: 1.5rem;
          }
        }
        .arrow-disabled {
          opacity: 0.2;
          cursor: not-allowed
        }
      }
    }
  }
}

@media (max-width: 664px) {
  .notifications {
    &-wrapper {
      .notifications-list {
        .notification {
          padding: 1rem 0;
          &-header {
            flex-direction: column;
            margin-bottom: 1rem !important;
            .title {
              width: 100% !important;
            }
            .time {
              width: 100%;
            }
          }
        }
      }
    }
  }

}

@media (max-width: 375px) {
  .notifications {
    margin: 0 0 3rem 0;
  }
}
</style>
