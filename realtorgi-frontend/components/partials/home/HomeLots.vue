<template>
  <loading-spinner v-if="$fetchState.pending"/>
  <section v-else>
    <div v-if="openParticipationPopup" class="participation-popup"
         @click="openParticipationPopup = !openParticipationPopup">
      <div class="participation-popup-form" @click.stop>
        <div class="participation-popup-form-wrapper">
          <h1 class="title">Вход в аукционный зал</h1>
          <div class="subtitle">Подтверждаете ли Вы свое участие в торгах по данному лоту ({{
              choosenAuction.name
            }})?
          </div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup = !openParticipationPopup">Закрыть</button>
            <button class="submit" @click="submitParticipation">Подтверждаю</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="this.$route.name === 'auctions'" class="filters">
      <div class="title">Фильтры:</div>
      <div class="filters-list">
        <h2 class="filters-title">Категории:</h2>
        <div class="filter-list-wrapper">
          <div @click="chooseFilter({name: 'Все'})" :class="{ active: (choosenFilter.name === 'Все') }" class="filter">
            <span class="filter-name">Все </span></div>
          <div v-for="(category, index) in categories" :key="index" @click="chooseFilter(category)"
               :class="{ active: (category.name === choosenFilter.name) }" class="filter"><span
            class="filter-name">{{ category.name }} <span class="lots-nubmer">({{ category.lot_count }})</span></span>
          </div>
        </div>
        <h2 class="filters-title-mobile" @click="showFilters = !showFilters">
          <chevron-down-icon :class="{ opened: (showFilters) }"/>
          Категории
        </h2>
        <div class="filter-list-wrapper-mobile" :class="{hidden: (!showFilters)}">
          <div @click="chooseFilter({name: 'Все'})" :class="{ active: (choosenFilter.name === 'Все') }" class="filter">
            <span class="filter-name">Все </span></div>
          <div v-for="(category, index) in categories" :key="index" @click="chooseFilter(category)"
               :class="{ active: (category.name === choosenFilter.name) }" class="filter"><span
            class="filter-name">{{ category.name }} <span class="lots-nubmer">({{ category.lot_count }})</span></span>
          </div>
        </div>
        <div class="filters-searches-mobile">
          <h2 class="filters-searches-title-mobile" @click="showSearches = !showSearches">
            <chevron-down-icon :class="{ opened: (showSearches) }"/>
            Поиск
          </h2>
          <div class="filters-searches-wrapper" :class="{hidden: (!showSearches)}">
            <div class="auction-search">
              <span class="label">Поиск по:</span>
              <select name="search-type" v-model="chooseSearchType">
                <option selected>Поиск по названию лота</option>
                <option>Поиск по номеру лота</option>
              </select>
            </div>
            <div class="lot-number-search" v-if="chooseSearchType === 'Поиск по номеру лота'">
              <span class="search-filters-title">Поиск по номеру лота:</span>
              <input type="text" v-model="lotNumberSearch" @input="findNumber">
            </div>
            <div class="lot-name-search" v-if="chooseSearchType === 'Поиск по названию лота'">
              <span class="search-filters-title">Поиск по названию лота:</span>
              <input type="text" v-model="lotNameSearch" @input="findName">
            </div>
          </div>
        </div>
        <div class="auction-sorting-mobile" v-if="auctions.length > 0">
          <h2 class="auction-sorting-title-mobile" @click="showSorting = !showSorting">
            <chevron-down-icon :class="{ opened: (showSorting) }"/>
            Сортировка
          </h2>
          <div class="auction-sorting-wrapper" v-if="auctions.length > 0" :class="{hidden: (!showSorting)}">
            <span class="label">Сортировать по:</span>
            <select name="sort-type" v-model="choosenSorting" @change="sortAuctions">
              <option>Все</option>
              <option>По цене min</option>
              <option>По цене max</option>
              <option>Ближайшие</option>
            </select>
          </div>
        </div>
        <div class="auction-sorting-mobile cities" v-if="auctions.length > 0">
          <h2 class="auction-sorting-title-mobile" @click="showCities = !showCities">
            <chevron-down-icon :class="{ opened: (showCities) }"/>
            Область
          </h2>
          <div class="auction-sorting-wrapper" v-if="auctions.length > 0" :class="{hidden: (!showCities)}">
            <span class="label">Область:</span>
            <select name="sort-type" v-model="choosenCity" @change='changeCity'>
              <option>Все</option>
              <option v-for="(city, index) in cities" :key="index">{{ city.name }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="auctions">
      <h1 class="title" v-if="this.$route.name === 'index'">
        Предстоящие торги
      </h1>
      <h1 class="title" v-if="this.$route.name === 'auctions-all-id'">
         Список лотов в аукционе №{{ this.$route.params.auctionNumber }}
      </h1>
      <div class="auction-filters" v-if="this.$route.name === 'auctions'">
        <div class="status-filters">
          <button v-for="(status, index) in statuses" :key="index" @click="chooseStatus(status)" class="status-filter"
                  :class="{'status-filter-active': (status.name === choosenStatuses.name)}">{{ status.name }}
          </button>
        </div>
        <div class="auction-filters-bottom">
          <div class="auction-search-wrapper">
            <div class="auction-search">
              <select name="search-type" v-model="chooseSearchType">
                <option selected>Поиск по названию лота</option>
                <option>Поиск по номеру лота</option>
              </select>
            </div>
            <div class="lot-number-search" v-if="chooseSearchType === 'Поиск по номеру лота'">
              <input type="text" v-model="lotNumberSearch" @input="findNumber" placeholder="Номер лота..">
            </div>
            <div class="lot-name-search" v-if="chooseSearchType === 'Поиск по названию лота'">
              <input type="text" v-model="lotNameSearch" @input="findName" placeholder="Название лота..">
            </div>
          </div>
          <div class="auction-sorting" v-if="auctions.length > 0">
            <span class="label">Сортировать по:</span>
            <select name="sort-type" v-model="choosenSorting" @change="sortAuctions">
              <option>Все</option>
              <option>По цене min</option>
              <option>По цене max</option>
              <option>Ближайшие</option>
            </select>
          </div>
          <div class="auction-sorting cities" v-if="auctions.length > 0">
            <span class="label">Область:</span>
            <select name="sort-type" v-model="choosenCity" @change='changeCity'>
              <option>Все</option>
              <option v-for="(city, index) in cities" :key="index">{{ city.name }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="auctions-list" v-if="filteredByStatusAuctions.length > 0">
        <div v-for="(auction, index) in paginatedData" :key="index" @click="checkParticipation(auction)"
             class="lot-item">
          <lot-card :auction="auction"/>
        </div>
      </div>
      <div
        v-if="(auctions.length > 0) && ((this.$route.name === 'auctions') || (this.$route.name === 'auctions-all-id')) && (paginatedData.length !== 0)">
        <div class="auctions-pagination">
          <div class="pagination-number">
          <span>
            Страница {{ pageNumber + 1 }} из {{ pagesAmount }}
          </span>
          </div>
          <div class="pagination-arrows">
            <div class="left-arrow arrow" @click="prevPage" aria-label="Назад"
                 :class="{'arrow-disabled': ((pageNumber + 1) === 1)}">
              <svg viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="right-arrow arrow" @click="nextPage" aria-label="Вперед"
                 :class="{'arrow-disabled': ((pageNumber + 1) === pageCount)}">
              <svg viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div v-show="filteredByStatusAuctions.length === 0 && !$fetchState.pending">
        <div>В данной категории нет аукционов</div>
      </div>
    </div>
  </section>
</template>

<script>
import {ChevronDownIcon, GridIcon, ListIcon} from 'vue-feather-icons'

export default {
  components: {
    LotCard: () => import('~/components/common/lots/LotCard'),
    LoadingSpinner: () => import('~/components/LoadingSpinner'),
    ChevronDownIcon,
    GridIcon,
    ListIcon
  },
  data: () => ({
    display: 'grid',
    selectedFilter: 'active',
    auctions: [],
    choosenAuction: {},
    choosenRequest: {},
    filteredByStatusAuctions: [],
    filteredByNameAuctions: [],
    filteredByNumberAuctions: [],
    pageNumber: 0,
    pageSize: 100,
    pagesAmount: 0,
    choosenStatuses: {name: 'Все'},
    choosenFilter: {name: 'Все'},
    isAuctionPage: false,
    lotNameSearch: '',
    lotNumberSearch: '',
    showFilters: false,
    showSearches: false,
    showSorting: false,
    showCities: false,
    choosenCity: 'Все',
    choosenSorting: 'Все',
    foundByNumberAuctions: [],
    foundByNameAuctions: [],
    chooseSearchType: 'Поиск по названию лота',
    cities: [],
    openParticipationPopup: false,
    statuses: [
      {
        name: 'Все'
      },
      {
        name: 'Текущие'
      },
      {
        name: 'Предстоящие'
      },
      {
        name: 'Повторные'
      },
      {
        name: 'Состоявшиеся'
      },
      {
        name: 'Несостоявшиеся'
      },
      {
        name: 'Отмененные'
      }
    ],
    categories: []
  }),
  async fetch() {
    if (this.$route.name !== 'auctions-all-id') {
      if (this.$route.query.category && this.$route.query.category !== 'Все') {
        const data = await this.$axios.post(process.env.API_URL + '/admin/api/admin/categoryFilter', {
          category: this.$route.query.category
        })
        this.auctions = data.data.data.data
      } else {
        const data = await this.$axios.get(process.env.API_URL + '/admin/api/admin/lot')
        this.choosenFilter = {name: 'Все'}
        this.auctions = data.data.data.data
        // console.log(data.data.data.data[0].auction.start_selling)
        if (this.$route.name !== 'auctions') {
          this.auctions = this.auctions.filter(item => item.status.includes("Предстоящие"));
        }
      }
      await this.$axios.get(process.env.API_URL + '/admin/api/admin/category').then((response) => {
        this.categories = response.data.data.data
      })
      this.filteredByStatusAuctions = this.auctions
      if (this.$route.query.status && this.$route.query.status !== 'Все') {
        this.filteredByStatusAuctions = this.auctions.filter(item => (item.status === this.choosenStatuses.name));
      }
      this.pageCount

      await this.$axios.get(process.env.API_URL + '/admin/api/admin/country').then((response) => {
        this.cities = response.data.data.data
      })
    } else {
      const data = await this.$axios.get(process.env.API_URL + `/admin/api/admin/auction/lots/${this.$route.params.id}`)
      this.auctions = data.data.data
      this.filteredByStatusAuctions = this.auctions
      this.pageCount
    }
  },
  fetchOnServer: false,
  computed: {
    pageCount() {
      let l = this.filteredByStatusAuctions.length,
        s = this.pageSize;
      this.pagesAmount = Math.ceil(l / s)
      return this.pagesAmount
    },
    paginatedData() {
      const start = this.pageNumber * this.pageSize;
      const end = start + this.pageSize;
      return this.filteredByStatusAuctions.slice(start, end);
    }
  },
  methods: {
    nextPage() {
      if (this.pageNumber !== (this.pageCount - 1)) {
        this.pageNumber++;
        window.scrollTo(0, 0);
      }
    },
    prevPage() {
      if (this.pageNumber !== 0) {
        this.pageNumber--;
        window.scrollTo(0, 0);
      }
    },
    changeCity() {
      if (this.choosenCity === 'Все') {
        this.$fetch()
      }
      if (this.choosenStatuses.name !== 'Все') {
        this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
      } else {
        this.filteredByStatusAuctions = this.auctions.filter(item => (item.auction.city_name === this.choosenCity));
      }
      this.pageNumber = 0
      this.pageCount
    },
    checkParticipation(auction) {

      let isParticipant = false
      if ((auction.status === 'Предстоящие' || auction.status === 'Повторные') && ((auction.auction.ends_at * 1000 - (new Date().getTime())) <= 0)) {
        auction.confirms.forEach(request => {
          if (request.user_id === this.$store.state.auth.userData.id) {
            if (request.confirmed_user === 1) {
              this.$router.push({name: 'auctions-id', params: {id: auction.id,}})
            } else {
              isParticipant = true
              this.choosenRequest = request
            }

          }
        })
        if (isParticipant === true) {
          this.openParticipationPopup = !this.openParticipationPopup
          this.choosenAuction = auction
        } else {
          this.$router.push({name: 'auctions-id', params: {id: auction.id,}})
        }
      } else {
        this.$router.push({name: 'auctions-id', params: {id: auction.id,}})
      }

    },
    submitParticipation() {
      this.$axios.put(process.env.API_URL + `/admin/api/admin/auctionConfirm/${this.choosenRequest.id}`, {
        user_id: this.$store.state.auth.userData.id
      }).then(() => {
        this.openParticipationPopup = !this.openParticipationPopup
        this.$router.push({name: 'auctions-id', params: {id: this.choosenAuction.id,}})
      })
    },
    chooseStatus(status) {
      this.choosenStatuses = status
      if (status.name === 'Все') {
        this.filteredByStatusAuctions = this.auctions
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
        if (status.name === 'Все') {
          this.$router.push({query: {category: this.choosenFilter.name}})
        } else {
          this.$router.push({query: {category: this.choosenFilter.name, status: this.choosenStatuses.name}})
        }
      } else {
        this.filteredByStatusAuctions = this.auctions.filter(item => (item.status === status.name));
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
        if (status.name === 'Все') {
          this.$router.push({query: {category: this.choosenFilter.name}})
        } else {
          this.$router.push({query: {category: this.choosenFilter.name, status: this.choosenStatuses.name}})
        }
      }
      this.pageNumber = 0;
    },
    chooseFilter(category) {
      this.choosenFilter = category
      if (category.name !== 'Все') {
        this.$axios.post(process.env.API_URL + '/admin/api/admin/categoryFilter', {
          category: category.name
        }).then((response) => {
          this.auctions = response.data.data.data
          this.filteredByStatusAuctions = this.auctions
          if (this.choosenStatuses.name !== 'Все') {
            this.filteredByStatusAuctions = this.auctions.filter(item => (item.status === this.choosenStatuses.name));
            this.$router.push({query: {category: this.choosenFilter.name, status: this.choosenStatuses.name}})
          } else {
            this.$router.push({query: {category: this.choosenFilter.name}})
          }
          this.pageCount
        })
      } else {
        this.$axios.get(process.env.API_URL + '/admin/api/admin/lot').then((response) => {
          this.auctions = response.data.data
          console.log(response.data, 'rsponse')
          this.filteredByStatusAuctions = this.auctions
          if (this.choosenStatuses.name !== 'Все') {
            this.filteredByStatusAuctions = this.auctions.filter(item => (item.status === this.choosenStatuses.name));
            this.$router.push({query: {category: this.choosenFilter.name, status: this.choosenStatuses.name}})
          } else {
            this.$router.push({query: {category: this.choosenFilter.name}})
          }
          this.pageCount
        })
      }

      this.lotNameSearch = ''
      this.lotNumberSearch = ''
      this.choosenSorting = 'Все'
      this.choosenCity = 'Все'
    },
    findNumber() {
      if (this.choosenStatuses.name !== 'Все') {
        this.filteredByStatusAuctions = this.auctions.filter(item => (item.lot_number.toString()).search(this.lotNumberSearch) !== -1)
        this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => this.choosenStatuses.name === item.status);
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
      }
      if (this.choosenStatuses.name === 'Все') {
        this.filteredByStatusAuctions = this.auctions.filter(item => (item.lot_number.toString()).search(this.lotNumberSearch) !== -1)
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
      }
      this.sortAuctions
    },
    findName() {
      if (this.choosenStatuses.name !== 'Все') {
        this.filteredByStatusAuctions = this.auctions.filter(item => item.name.toLowerCase().search(this.lotNameSearch.toLowerCase()) !== -1)
        this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => this.choosenStatuses.name === item.status);
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
      }
      if (this.choosenStatuses.name === 'Все') {
        this.filteredByStatusAuctions = this.auctions.filter(item => item.name.toLowerCase().search(this.lotNameSearch.toLowerCase()) !== -1)
        if (this.choosenCity !== 'Все') {
          this.filteredByStatusAuctions = this.filteredByStatusAuctions.filter(item => (item.auction.city_name === this.choosenCity));
        }
      }
      this.sortAuctions
    },
    sortAuctions() {
      if (this.choosenSorting === 'Все') {
        this.$fetch()
      }
      if (this.choosenSorting === 'По цене min') {
        this.filteredByStatusAuctions.sort(function (a, b) {
          return parseFloat(a.price_start) - parseFloat(b.price_start);
        });
      }
      if (this.choosenSorting === 'По цене max') {
        this.filteredByStatusAuctions.sort(function (a, b) {
          return parseFloat(b.price_start) - parseFloat(a.price_start);
        });
      }
      if (this.choosenSorting === 'Ближайшие') {
        this.filteredByStatusAuctions.sort(function (a, b) {
          return parseFloat(b.auction.start_selling) - parseFloat(a.auction.start_selling);
        });
      }
    }
  },
  mounted() {
    if (this.$route.name === 'auctions') {
      if (this.$route.query.category && this.$route.query.category !== 'Все') {
        this.choosenFilter = {name: this.$route.query.category}
      }
      if (this.$route.query.status !== 'Все') {
        this.$router.push({query: {category: this.choosenFilter.name}})
      }
      this.isAuctionPage = true
      this.pageSize = 9
    }
    if (this.$route.name === 'auction-all-id') {
      this.pageSize = 9
    }
  },
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.lot-item {
  flex: 30%;
  max-width: 400px;
  margin-bottom: 30px;
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
    box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.024),
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

.auction-search {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;

  .label {
    opacity: 0.6;
  }

  select {
    border: none;
    outline: none;
    padding: 0.25rem;

  }
}

.search-filters-title {
  font-size: 1rem;
}

.auction-sorting {
  display: flex;
  flex-direction: column;
  margin-top: 1rem;

  .label {
    opacity: 0.6;
    margin-bottom: 0.5rem;
  }

  select {
    border: none;
    outline: none;
    padding: 0.25rem;
  }
}

.cities {
  margin-left: 3rem;
}

.auction-sorting-wrapper {
  display: flex;
  flex-direction: column;
  margin-top: 1rem;

  .label {
    opacity: 0.6;
    margin-bottom: 0.5rem;
  }

  select {
    border-bottom: 1px solid #1919193a;
    outline: none;
    padding: 0.25rem;

  }
}

section {
  display: flex;
  align-items: center;
  padding: 0 1rem;
  margin-bottom: 3rem;

  .filters {
    margin-right: 1rem;
    background-color: #fff;
    align-self: flex-start;
    padding: 2rem;
    width: 25%;
    border-radius: 10px;
    box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
    0 12.5px 10px rgba(0, 0, 0, 0.06),
    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
    0 100px 80px rgba(0, 0, 0, 0.12);

    .title {
      font-size: 1.75rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .filters-list {
      .filters-list-mobile {
        display: none;
      }

      .filters-title {
        font-size: 1.25rem;
      }

      .filters-title-mobile {
        display: none;
      }

      .filter-list-wrapper-mobile {
        display: none;
      }

      .filter-list-wrapper {
        .filter {
          border-bottom: 1px solid #dddddd;
          display: flex;
          align-items: center;
          cursor: pointer;

          .filter-name {
            opacity: 0.5;
            padding-left: 0;
            transition: 0.25s ease-in-out;
            padding: 0.75rem 0;
          }
        }

        .filter:hover {
          .filter-name {
            padding-left: 0.5rem;
          }
        }

        .active {
          .filter-name {
            opacity: 1;
            font-weight: 600;
            padding-left: 0.5rem;
          }
        }
      }
    }

    .filters-searches-mobile {
      display: none;
    }

    .auction-sorting-mobile {
      display: none;
    }
  }

  .auctions {
    align-self: flex-start;
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    width: 100%;

    .title {
      text-align: center;
      margin-bottom: 2rem;
    }

    .auction-filters {
      margin-bottom: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;

      .auction-filters-bottom {
        margin-top: 1rem;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
      }

      .auction-search-wrapper {
        margin-right: 3rem;

        .auction-search {
          margin-bottom: 0;

          select {
            border: none;
          }
        }
      }

      .status-filters {
        .status-filter {
          padding: 0.55rem 1.25rem;
          background-color: transparent;
          color: #191919;
          border-radius: 25px;
          font-size: 0.95rem;
          font-weight: 500;
          outline: none;
          margin-bottom: 0.5rem;
          transition: .2s ease-in-out;
        }

        .status-filter:hover {
          background-color: #19191915;
        }

        .status-filter-active {
          background-color: #191919;
          color: #fff;
          box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
          0 6.7px 5.3px rgba(0, 0, 0, 0.048),
          0 12.5px 10px rgba(0, 0, 0, 0.06),
          0 22.3px 17.9px rgba(0, 0, 0, 0.072),
          0 41.8px 33.4px rgba(0, 0, 0, 0.086),
          0 100px 80px rgba(0, 0, 0, 0.12);
        }

        .status-filter-active:hover {
          background-color: #191919;
        }
      }

      .auction-sorting {
        display: flex;
        flex-direction: column;
        margin-top: 0;

        .label {
          opacity: 0.6;
          margin-bottom: 0rem;
        }

        select {
          border-bottom: 1px solid #1919193a;
          outline: none;
          padding: 0.25rem;
        }
      }
    }

    .auctions-list {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 2rem;
      align-items: flex-start;
    }

    .auctions-pagination {
      display: flex;
      justify-content: space-between;
      align-items: center;

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

.hidden {
  display: none !important;
}

@media (max-width: 1024px) {
  .auction-amount {
    display: none;
  }
}

@media (max-width: 963px) {
  section {
    flex-direction: column;
    padding: 0;

    .filters {
      width: 100%;
      margin-bottom: 1rem;

      .filters-list {
        .filters-title-mobile {
          display: flex;
          align-items: center;
          margin-bottom: 1rem;
          @include button-rounded;

          svg {
            transition: .2s ease-in-out;
            margin-right: 0.25rem;
          }

          .opened {
            transform: rotate(180deg);
          }
        }

        .filters-title {
          display: none;
        }

        .filter-list-wrapper {
          display: none;
        }

        .filter-list-wrapper-mobile {
          display: block;

          .filter {
            border-bottom: 1px solid #dddddd;
            display: flex;
            align-items: center;
            cursor: pointer;

            .filter-name {
              opacity: 0.5;
              padding-left: 0;
              transition: 0.25s ease-in-out;
              padding: 0.75rem 0;

              .auction-amount {
                display: inline;
              }
            }
          }

          .filter:hover {
            .filter-name {
              padding-left: 0.5rem;
            }
          }

          .active {
            .filter-name {
              opacity: 1;
              padding-left: 0.5rem;
            }
          }
        }
      }

      .filters-searches {
        display: none;
      }

      .filters-searches-mobile {
        display: block;

        .filters-searches-title-mobile {
          @include button-rounded;
          display: flex;
          align-items: center;
          margin-bottom: 1rem;
          @include button-rounded;

          svg {
            transition: .2s ease-in-out;
            margin-right: 0.25rem;
          }

          .opened {
            transform: rotate(180deg);
          }
        }

        .filters-searches-wrapper {
          margin-bottom: 1rem;
        }
      }

      .auction-sorting-mobile {
        display: block;

        .auction-sorting-title-mobile {
          display: flex;
          align-items: center;
          @include button-rounded;

          svg {
            transition: .2s ease-in-out;
            margin-right: 0.25rem;
          }

          .opened {
            transform: rotate(180deg);
          }
        }
      }

      .cities {
        margin-top: 1rem;
        margin-left: 0;
      }
    }

    .auctions {
      .auctions-list {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
      }

      .auction-filters {
        .auction-search-wrapper {
          display: none;
        }

        .auction-filters-bottom {
          display: none;
        }
      }
    }
  }
}
</style>
