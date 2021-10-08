<template>
  <section>
    <h1 class="title">Категории</h1>
    <div class="categories-wrapper">
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Недвижимость' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <home-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Недвижимость</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Транспорт' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <truck-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Транспорт</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Оборудование' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <monitor-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Оборудование</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Предприятие' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <briefcase-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Предприятие</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Запчасти' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <settings-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Запчасти</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Инвентарь' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <tool-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Инвентарь</div>
        </div>
      </nuxt-link>
      <nuxt-link
        :to="{
          name: 'auctions',
          query: { category: 'Дебиторская задолженность' }
        }"
        class="category-card-wrapper">
        <div class="category-card">
          <trending-down-icon class="category-card-icon" stroke-width="1"/>
          <div class="category-card-title">Дебиторская задолженность</div>
        </div>
      </nuxt-link>
    </div>
  </section>
</template>

<script>
import { HomeIcon, TruckIcon, SettingsIcon, ToolIcon, MonitorIcon, TrendingDownIcon, BriefcaseIcon } from 'vue-feather-icons';
export default {
  components: {
    HomeIcon, TruckIcon, SettingsIcon, ToolIcon, MonitorIcon, TrendingDownIcon, BriefcaseIcon
  },
}

</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

section {
  background-color: #fff;
  z-index: 3rem;
  padding: 2rem;
  margin: 0 1rem 5rem 1rem;
  border-radius: 10px;
  .title {
    text-align: center;
    margin-bottom: 2rem;
  }
  .categories-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    .category-card-wrapper {
      display: flex;
      flex: 1 1 0%;
      margin: 0.5rem;
      transition: .2s ease-in-out;
      border-radius: 10px;
      box-shadow:
        0 2.8px 2.2px rgba(0, 0, 0, 0.024),
        0 6.7px 5.3px rgba(0, 0, 0, 0.038),
        0 12.5px 10px rgba(0, 0, 0, 0.05),
        0 22.3px 17.9px rgba(0, 0, 0, 0.062),
        0 41.8px 33.4px rgba(0, 0, 0, 0.066),
        0 100px 80px rgba(0, 0, 0, 0.08);
      border: 1px solid $border-color;
      }
      .category-card {
        display: flex;
        position: relative;
        flex-direction: column;
        width: 100%;
        padding: 1rem 1.5rem;
        justify-content: center;
        align-items: center;
        background-color: #fff;
        z-index: 6;
        border-radius: 10px;
        cursor: pointer;
        &-icon {
          width: 3.5rem;
          height: 3.5rem;
          margin-bottom: 0.5rem;
        }
        &-title {
          text-align: center;
        }
      }
    .category-card-wrapper:hover {
      box-shadow: none;
    }
  }
}

@media (max-width: 375px) {
  section {
    margin: 0 0 5rem 0;
  }
  
}
</style>
