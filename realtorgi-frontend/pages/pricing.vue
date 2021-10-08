<template>
  <section>
    <div class="warning">
      <div class="warning-wrapper">
        Прейскурант на услуги, оказываемые организатором торгов - ООО «Реалконсалтинг» (оператором электронной торговой площадки «Realtorgi.by») по реализации имущества на электронных торгах в форме аукциона
      </div>
    </div>
    <div class="pricing">
      <div class="pricing-wrapper">
        <div class="pricing-table">
          <div class="pricing-table-wrapper">
            <div class="table-row first">
              <div class="price-range">
                Стоимость, за которую реализован объект (в базовых величинах), с учетом НДС
              </div>
              <div class="price-value">
                Размер премии Исполнителя ( % от цены продажи каждого лота)
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                до 1000 базовых величин
              </div>
              <div class="price-value">
                3%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 1000 до 5000 базовых величин
              </div>
              <div class="price-value">
                2,4%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 5000 до7500 базовых величин
              </div>
              <div class="price-value">
                2,2%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 7500 до 10000 базовых величин
              </div>
              <div class="price-value">
                1,9%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 10000 до 12500 базовых величин
              </div>
              <div class="price-value">
                1,5%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 12500 до 14000 базовых величин
              </div>
              <div class="price-value">
                1,25%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 14000 до 16000 базовых величин
              </div>
              <div class="price-value">
                0,95%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 16000 до 20000 базовых величин
              </div>
              <div class="price-value">
                0,8%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                от 20000 до 30000 базовых величин
              </div>
              <div class="price-value">
                0,6%
              </div>
            </div>
            <div class="table-row">
              <div class="price-range">
                свыше 30000 базовых величин
              </div>
              <div class="price-value">
                0,5%
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";
.warning {
  background-color: #fff;
  margin: 0 1rem 2rem 1rem;
  border-radius: 10px;
  box-shadow:
  0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    padding: 2rem;
  }
}

.pricing {
  background-color: #fff;
  margin: 0 1rem 3rem 1rem;
  border-radius: 10px;
  box-shadow:
  0 2.8px 2.2px rgba(0, 0, 0, 0.034),
  0 6.7px 5.3px rgba(0, 0, 0, 0.048),
  0 12.5px 10px rgba(0, 0, 0, 0.06),
  0 22.3px 17.9px rgba(0, 0, 0, 0.072),
  0 41.8px 33.4px rgba(0, 0, 0, 0.086),
  0 100px 80px rgba(0, 0, 0, 0.12);
  &-wrapper {
    padding: 2rem;
    .pricing-table {
      display: flex;
      flex-direction: column;
      margin-bottom: 2rem;
      &-wrapper {
        display: flex;
        flex-direction: column;;
        .table-row {
          display: flex;
          width: 100%;
          justify-content: space-between;
          align-items: center;
          min-height: 3rem;
          border-bottom: 1px solid $border-color;
          .price-range {
            width: 48%;
          }
          .price-value {
            width: 48%;
          }
        }
        .first {
          opacity: 0.7;
        }
      }
    }
  }
}

@media (max-width: 375px) {
  .warning {
    margin: 0 0 2rem 0;
  }
  .pricing {
    margin: 0 0 3rem 0;
  }
}

</style>
<script>
export default {
  head() {
    return {
      title: 'Стоимость услуг',
    }
  },
}
</script>
