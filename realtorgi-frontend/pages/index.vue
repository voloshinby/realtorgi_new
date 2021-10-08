<template>
  <div class="main-wrapper">
    <div class="main-banner">
      <div class="main-banner-title">
        Специализированная торговая онлайн-площадка
      </div>
      <div class="main-banner-subtitle">
        для проведения электронных торгов
      </div>
      <div class="main-banner-instruction">
          <nuxt-link :to="{name: 'auth-registration'}" class="main-banner-instruction-card registration">
            <div class="card-title">
              <div class="number"><div>1</div></div>
              <span>Регистрация</span>
            </div>
            <div class="card-subtitle">
              Пройдите регистрацию
            </div>
        </nuxt-link>
          <nuxt-link :to="{name: 'auctions'}" class="main-banner-instruction-card searching">
            <div class="card-title">
              <div class="number"><div>2</div></div>
              <span>Поиск</span>
            </div>
            <div class="card-subtitle">
              Найдите нужный вам товар
            </div>
        </nuxt-link>
        <div class="main-banner-instruction-card">
          <div class="card-title">
            <div class="number"><div>3</div></div>
            <span>Покупка</span>
          </div>
          <div class="card-subtitle">
            Делайте ставки
          </div>
        </div>
      </div>
    </div>

    <home-categories class="home-categories"/>

    <home-lots />

    <!-- <home-faq /> -->

  </div>
</template>

<script>
import HomeCategories from "~/components/partials/home/HomeCategories";
import HomeLots from "~/components/partials/home/HomeLots";
import HomeFaq from "~/components/partials/home/HomeFAQ";

export default {
  components: {HomeCategories, HomeLots, HomeFaq},
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

.main-wrapper {
  display: flex;
  flex-direction: column;
  .main-banner {
    display: flex;
    flex-direction: column;
    background-image: url("~/assets/img/main-banner.jpg");
    background-position: 90% 100%;
    background-size: cover;
    width: 100vw;
    transform: translateX(-2rem) translateY(-2.20rem);
    color: #fff;
    padding: 3.5rem 3rem;
    &-title {
      font-size: 2.25rem;
    }
    &-subtitle {
      font-size: 1.5rem;
      font-weight: 300;
      margin-bottom: 2rem;
    }
    &-instruction {
      display: flex;
      margin-bottom: 2rem;
      &-card {
        display: flex;
        flex-direction: column;
        border-radius: 20px;
        box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.25);
        background-color: rgba(4, 35, 115, 0.75);
        margin-right: 1rem;
        padding: 1rem 1rem 2rem 1rem;
        width: 16.25rem;
        .card-title {
          display: flex;
          margin-bottom: 0.5rem;
          align-items: center;
          .number {
            border-radius: 50%;
            border: 1px solid #fff;
            color: rgba(4, 35, 115, 0.75);
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 0.75rem;
            div {
              background-color: #fff;
              border: 1px solid rgba(4, 35, 115, 0.75);
              border-radius: 50%;
              line-height: 1.4rem;
              width: 1.5rem;
              height: 1.5rem;
            }
          }
          span {
            font-size: 1.5rem;
          }
        }
        .card-subtitle {
          font-weight: 300;
        }
      }
      &-card:last-child {
        margin-right: 0;
      }
      .registration {
        box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.25);
        transition: .2s ease-in-out;
        cursor: pointer;
      }
      .registration:hover {
        box-shadow: none;
      }
      .searching {
        box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.25);
        transition: .2s ease-in-out;
        cursor: pointer;
      }
      .searching:hover {
        box-shadow: none
      }
    }
    &-button {
      @include button-rounded;
      width: fit-content;
      color: #fff;
      border-color: #fff;
    }
    &-button:hover {
      @include button-active;
      border-color: #fff;
      color: $link-color;
      background-color: #fff;  
    }
  }
}
@media (max-width: 1372px) {
  .main-banner {
    transform: translateX(-2rem) translateY(-2.75rem) !important;
  }
}
@media (max-width: 1165px) {
  .main-banner {
    transform: translateX(-2rem) translateY(-2.9rem) !important;
    align-items: center;
    &-title {
      text-align: center;
    }
  }
}
@media (max-width: 886px) {
  .main-banner {
    transform: translateX(-2rem) translateY(-3rem) !important;
    background-position: 0% 100% !important;
    .home-categories {
      text-align: center;
    }
    &-title {
      font-size: 1.5rem;
      text-align: center;
      line-height: 3rem;
    }
    &-subtitle {
      font-size: 1rem !important;
      text-align: center;
    }
    &-instruction {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      &-card {
        margin-bottom: 1rem;
        margin-right: 0 !important;
        .card-title {
          display: flex;
          margin-bottom: 0.5rem;
          align-items: center;
          .number {
            border-radius: 50%;
            border: 1px solid #fff;
            color: rgba(4, 35, 115, 0.75);
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 0.75rem;
            div {
              background-color: #fff;
              border: 1px solid rgba(4, 35, 115, 0.75);
              border-radius: 50%;
              line-height: 1.4rem;
              width: 1.5rem;
              height: 1.5rem;
            }
          }
          span {
            font-size: 1.5rem;
          }
        }
        .card-subtitle {
          font-weight: 300;
        }
      }
    }
    &-button {
      @include button-rounded;
      width: fit-content;
      color: #fff;
      border-color: #fff;
    }
    &-button:hover {
      @include button-active;
      border-color: #fff;
      color: $link-color;
      background-color: #fff;  
    }
  }
}
@media (max-width: 475px) {
  .main-banner {
    transform: translateX(-2rem) translateY(-3.425rem) !important;
  }
}
@media (max-width: 424px) {
  .main-banner {
    &-title {
      font-size: 2rem !important;
    }
  }
}

@media (max-width: 375px) {
  .main-banner {
    transform: translateX(0) translateY(-3.425rem) !important;
  }
}
</style>