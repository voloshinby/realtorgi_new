<template>
  <div class="lot-card">
    <!-- <nuxt-link
      :to="{
        name: 'auctions-id',
        params: {
          id: auction.id,
        },
      }"
    > -->
      <div class="lot-card-wrapper">
        <div v-if="auction.status" class="lot-status" :class="{ canceled: (auction.status === 'Отмененные') || (auction.status === 'Несостоявшиеся'), coming: (auction.status === 'Предстоящие' || auction.status === 'Повторные'), current: (auction.status === 'Текущие')}">
          <span>{{ auction.status }}</span>
        </div>
        <div v-if="auction.gallery.length !== 0" class="image">
          <img :src="'https://realtorgi.by' + auction.gallery[0].path" :alt="auction.name">
        </div>
        <div class="card-body">
          <div class="title">
            <span>{{ auction.name }}</span>
          </div>
          <div class="lot-info">
            <div class="participants-count" title="Количество участников">
              <users-icon/>
              <span v-if="auction.users_count > 0">{{ auction.users_count }}</span>
              <span v-else>Нет участников</span>
            </div>
            <div class="lot-date" title="Начало проведения торгов">
              <calendar-icon/>
              <span>{{ moment((parseInt(auction.auction.start_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}</span>
            </div>
            <div class="lot-number">
              <span>Номер лота: <span class="number">№{{ auction.lot_number }}</span></span>
            </div>
            <div class="auction-number">
              <span>Номер аукциона: <span class="number">№{{ auction.auction.auction_number }}</span></span>
            </div>
          </div>
        </div>
        <div class="price">
          <div class="price-wrapper">
            <span>{{ auction.price_start }} BYN</span>
          </div>
        </div>
      </div>
    <!-- </nuxt-link> -->
  </div>
</template>

<script>
import { CalendarIcon, UsersIcon, MapPinIcon } from 'vue-feather-icons'
import moment from 'moment'
import ru from 'moment/locale/ru'

export default {
  components: {
    CalendarIcon,
    UsersIcon,
    MapPinIcon,
  },
  data: () => ({
    moment: moment,

  }),
  props: {
    auction: {
      type: Object
    },
  },
  // async fetch() {
  //   // const data = await this.$axios.get(`https://realtorgi.by/admin/api/admin/lot`)
  //   // // console.log(data.data.data);
  //   // this.requestedAuctions = data.data.data.data;
  //   console.log(this.auction);
  //   // console.log(this.auction.auction.start_selling);
  // },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.lot-card {
  height: auto;
  border-radius: 10px;
  overflow: hidden;
  width: 19rem;
  border: 1px solid $border-color;
  height: fit-content;
  transition: .2s ease-in-out;
  cursor: pointer;
  box-shadow:
  0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    height: 100%;
    width: 100%;
    background-color: #fff;
    position: relative;
    z-index: 6;
    .lot-status {
      position: absolute;
      color: #ffffff;
      right: 0;
      top: 1rem;
      backdrop-filter: blur(15px);
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 2;
      padding: 0.2rem 0.75rem;

    }
    .canceled {
      background-color: rgba(255, 0, 0, 0.6)
    }
    .current {
      background-color: rgba(60, 179, 113, 0.6)
    }
    .coming {
      background-color: rgba(255, 217, 0, 0.6)
    }
    .image {
      background-color: #000;
      width: 100%;
      position: relative;
      img {
        height: 12rem;
        width: 100%;
        object-fit: cover;
      }
    }
    .card-body {
      padding: 0 1rem;
      .title {
        padding-top: 1rem;
        font-size: 1.2rem;
        font-weight: 500;
        line-height: 1.25;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        overflow: hidden;
      }
      .lot-info {
        opacity: 0.9;
        margin-bottom: 0.5rem;
        display: flex;
        flex-direction: column;
        .lot-description {
          margin-bottom: 0.5rem;
          display: -webkit-box;
          -webkit-line-clamp: 4;
          -webkit-box-orient: vertical;
          text-overflow: ellipsis;
          overflow: hidden;
          width: 100%;
          span {
            font-size: 0.9rem;
            opacity: 0.8;
          }
        }
        .participants-count {
          border-top: 1px solid $border-color;
          display: flex;
          align-items: center;
          margin-top: 0.5rem;
          padding-top: 0.5rem;
          font-size: 0.9rem;
          margin-bottom: 0.5rem;
          span {
            color: $link-color;
            font-weight: 500;
            margin-left: 0.5rem;
          }
        }
        .lot-date {
          display: flex;
          align-items: center;
          font-size: 0.9rem;
          margin-bottom: 0.5rem;
          span {
            color: $link-color;
            font-weight: 500;
            margin-left: 0.5rem;
          }
        }
        .lot-number {
          font-size: 0.8rem;
          margin-bottom: 0.5rem;
          .number {
            font-weight: 600;
            font-size: 0.9rem;
          }
        }
        .auction-number {
          font-size: 0.8rem;
          margin-bottom: 0.5rem;
          .number {
            font-weight: 600;
            font-size: 0.9rem;
          }
        }
        svg {
          height: 1.25rem;
          width: 1.25rem;
        }
      }
    }
    .price {
      padding: 0 1rem 1rem 1rem;
      &-wrapper {
        width: 100%;
        border-top: 1px solid #d3d3d3;
        display: flex;
        justify-content: center;
        color: $link-color;
        span {
          margin-top: 1rem;
          padding: 0.45rem 1rem;
          border: 2px solid $link-color;
          border-radius: 25px;
          font-size: 1rem;
          font-weight: 500;
          outline: none;
          margin-bottom: 0.5rem;
          transition: .2s ease-in-out;
        }
      }
    }
  }
}
.lot-card:hover {
  box-shadow: none;
  .lot-card-wrapper {
    .price {
      &-wrapper {
        span {
          background-color: $link-color;
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
  }
}

@media (max-width: 375px) {
  .lot-card {
    &-wrapper {
      .card-body {
        .lot-description {
          display: none !important;
        }
      }
    }
  }
}
</style>
