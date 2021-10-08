<template>
  <div class="notification">
    <div class="notification-wrapper">
      <div class="notification-close" @mouseover="iconMouseOver" @mouseout="iconMouseOut"><x-icon :stroke='iconStroke' :color='iconStroke'/></div>
      <div class="notification-header">
        <div class="title">{{ notification.title }}</div>
        <div class="time">{{ moment((Date.parse(notification.created_at))).fromNow()}}</div>
      </div>
      <div class="notification-subtitle">
        {{ notification.text }}
      </div> 
    </div>
  </div>
</template>
<script>
import { XIcon } from 'vue-feather-icons'
import moment from 'moment'
import ru from 'moment/locale/ru'

export default ({
  components: {
    XIcon,
  },

  data: () => ({
    iconHovered: false,
    iconStroke: '#6E6E73',
    moment: moment
  }),

  props: [
    'notification',
  ],

  methods: {
    iconMouseOver() {
      this.iconHovered = !this.iconHovered
      this.iconStroke = 'black'
    },
    iconMouseOut() {
      this.iconHovered = !this.iconHovered
      this.iconStroke = '#6E6E73'
    }
  }
})
</script>

<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.notification {
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 1rem;
  box-shadow:
    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    background-color: #fff;
    padding: 1rem;
    position: relative;
    cursor: pointer;
    .notification-close {
      position: absolute;
      right: 0.75rem;
      top: 0.75rem;
      cursor: pointer;
      svg {
        width: 1rem;
        height: 1rem;
      }
    }
    .notification-header {
      display: flex;
      width: 100%;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
      width: 25rem;
      .title {
        font-weight: 500;
        font-size: 1.2rem;
        width: 80%;
      }
      .time {
        margin-right: 1.5rem;
        color: $text-color-2;
        font-size: 0.8rem;
      }
    }
    .notification-subtitle {
      font-size: 0.9rem;
      color: $text-color-2;
      margin-right: 1rem;
      width: 20rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      text-overflow: ellipsis;
      overflow: hidden;
    }
  }
}

</style>