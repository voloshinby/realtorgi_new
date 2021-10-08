<template>
  <section>
    <div class="faq-wrapper">
      <div class="titles">
        <h1>Часто задаваемые вопросы</h1>
      </div>
      <div class="faqs">
        <div class="faq" @click="chooseFaq(0)">
          <div class="faq-wrapper">
            <div class="faq-dropout">
              <chevron-down-icon class="dropout-icon" :class="{ opened: (choosenFaq.includes(0)) }"/>
              <div class="dropout-title">Что такое онлайн-аукционы?</div>
            </div>
            <div class="faq-content" :class="{ hidden: (!choosenFaq.includes(0)) }">
              Онлайн-аукцион - аукцион, проводящийся посредством интернета. В отличие от обычных аукционов, интернет-аукционы проводятся на расстоянии и в них можно участвовать, не находясь в определённом месте проведения, делая ставки через сайт
            </div>
          </div>
        </div>
        <div class="faq" @click="chooseFaq(1)">
          <div class="faq-wrapper">
            <div class="faq-dropout">
              <chevron-down-icon class="dropout-icon" :class="{ opened: (choosenFaq.includes(1)) }"/>
              <div class="dropout-title">Кто может участвовать в онлайн-аукционах?</div>
            </div>
            <div class="faq-content" :class="{ hidden: (!choosenFaq.includes(1)) }">
              <p><strong>1.</strong> В соответствии с законодательством к участию в электронных торгах допускаются граждане (далее – физические лица), в том числе индивидуальные предприниматели, юридические лица, организации, не являющиеся юридическими лицами (далее – юридические лица), которым предоставлено законодательством право на участие в электронных торгах.</p>
              <p>В электронных торгах не имеют права участвовать работники государственных органов, в том числе органов принудительного исполнения, суда, прокуратуры, органов внутренних дел, а также члены их семей, а также иные лица, чье право на участие в электронных торгах органично законодательством.</p>
              <p><strong>2.</strong> Физические лица могут участвовать в электронных торгах через уполномоченных лиц, при предъявлении надлежащим образом оформленной доверенности.</p>
              <p><strong>3.</strong> Физические лица – резиденты Республики Беларусь предоставляют копию паспорта.</p>
              <p><strong>4.</strong> Физические лица – нерезиденты Республики Беларусь предоставляют копию паспорта, а также нотариально заверенный перевод паспорта, за исключением случаев, когда паспорт не требует перевода на русский или белорусский языки.</p>
              <p><strong>5.</strong> Индивидуальные предприниматели – резиденты Республики Беларусь предоставляют копию паспорта и заверенную в установленном порядке копию свидетельства о государственной регистрации.</p>
              <p><strong>6.</strong> Индивидуальные предприниматели – нерезиденты Республики Беларусь предоставляют копию паспорта и заверенную в установленном порядке выписку из торгового реестра страны происхождения или иное эквивалентное доказательство юридического статуса в соответствии с законодательством страны происхождения, а также их копии с заверенным переводом, за исключением случаев, когда они не требуют перевода на русский или белорусский языки.</p>
              <p><strong>7.</strong> Юридические лица, являющиеся резидентами Республики Беларусь, предоставляют доверенность юридического лица (кроме случаев, когда юридическое лицо представляет его руководитель) и заверенные в установленном порядке копии учредительных документов и свидетельства о государственной регистрации.</p>
              <p><strong>8.</strong> Юридические лица, являющиеся нерезидентами Республики Беларусь, предоставляют доверенность юридического лица (кроме случаев, когда юридическое лицо представляет его руководитель), а также выписку из торгового реестра страны происхождения или иное эквивалентное доказательство юридического статуса в соответствии с законодательством страны происхождения, а также их копии с заверенным переводом, за исключением случаев, когда они не требует перевода на русский или белорусский языки.</p>
            </div>
          </div>
        </div>
        <div class="faq" @click="chooseFaq(2)">
          <div class="faq-wrapper">
            <div class="faq-dropout">
              <chevron-down-icon class="dropout-icon" :class="{ opened: (choosenFaq.includes(2)) }"/>
              <div class="dropout-title">Кто признается победителем в онлайн-аукционах?</div>
            </div>
            <div class="faq-content" :class="{ hidden: (!choosenFaq.includes(2)) }">
              Победителем торгов станет участник, чья ставка окажется наивысшей на момент окончания торгов по лоту. 
              Если торги признаны несостоявшимися, то единственному участнику, подавшему заявку на участие в торгах будет
              оправлено уведомление о покупке лота, на который он подавал заявку.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import { ChevronDownIcon } from 'vue-feather-icons';

export default {
  components: {
    ChevronDownIcon,
  },
  data: () => ({
    choosenFaq: []
  }),
  methods: {
    chooseFaq(index) {
      if (this.choosenFaq.includes(index)) {
        this.choosenFaq.splice(this.choosenFaq.indexOf(index), 1)
      }
      else {
        this.choosenFaq.push(index)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

section {
  padding: 0 4rem;
  margin-bottom: 3rem;
  .faq-wrapper {
    display: flex;
    .titles {
      margin-right: 2rem;
      max-width: 40%;
      display: flex;
      flex-direction: column;
      h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
      }
      .subtitle {
        color: $text-color-2;
      }
      .link {
        margin-top: 0.5rem;
        color: $link-color;
        font-weight: 400;
        font-size: 0.875rem;
      }
    }
    .faqs {
      display: flex;
      flex-direction: column;
      flex: 1;
      .faq {
        width: 100%;
        margin-bottom: 1rem;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 
        0 2.8px 2.2px rgba(0, 0, 0, 0.034),
        0 6.7px 5.3px rgba(0, 0, 0, 0.048),
        0 12.5px 10px rgba(0, 0, 0, 0.06),
        0 22.3px 17.9px rgba(0, 0, 0, 0.072),
        0 41.8px 33.4px rgba(0, 0, 0, 0.086),
        0 100px 80px rgba(0, 0, 0, 0.12);
        .faq-wrapper {
          border: 1px solid $border-color;
          border-radius: 10px;
          position: relative;
          padding: 1.25rem 1rem;
          border-radius: 10px;
          background-color: #fff;
          display: flex;
          flex-direction: column;
          .faq-dropout {
            display: flex;
            align-items: center;
            .dropout-title {
              font-size: 1.25rem;
              font-weight: 400;
            }
            .dropout-icon {
              margin-right: 0.5rem;
              transition: .2s ease-in-out;
            }
          }
          .faq-content {
            margin-top: 1rem;
            color: $text-color-2;
            padding-left: 2.25rem;
            p {
              margin-bottom: 0.5rem;
            }
            strong {
              color: $text-color-1;
            }
          }
        }
      }
    }
  }
}
.hidden {
  display: hidden;
}
.opened {
  transform: rotate(180deg);
}

@media(max-width: 768px) {
  section {
    .faq-wrapper {
      display: flex;
      flex-direction: column;
      .titles {
        max-width: 100%;
        margin-right: 0;
        text-align: center;
        margin-bottom: 1rem;
      }
    }
  }
}

@media(max-width: 768px) {
  section {
    padding: 0;
  }
}
</style>

