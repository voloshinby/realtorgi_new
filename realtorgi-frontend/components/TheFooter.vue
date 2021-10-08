<template>
  <footer>
    <div class="footer-directory">
      <div class="footer-directory-wrapper">
        <div v-for="(item, index) in menu" :key="index" class="directory-column">
          <label class="directory-label">
            {{ item.name }}
          </label>
          <ul class="directory-list">
            <li
              v-for="(child, cIndex) in item.children"
              :key="`menu-item-${index}-child-${cIndex}`"
              class="directory-list-item"
              >
              <nuxt-link :to="child.to">
                {{ child.name }}
                <span v-if="child.tag" class="tag">
                  {{ child.tag }}
                </span>
              </nuxt-link>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer-info">
        <div class="directory-list-item">
          <p class="title">ООО «Реалконсалтинг»</p>
          <p>220015, Республика Беларусь, г. Минск,</p>
          <p>ул. Пономаренко 35а, помещение 701, каб. 8</p>
          <p>Бизнес-центр «ИНФО»</p>
          <p>Время работы: Пн - Пт: 09:00 - 18:00</p>
        </div>
      </div>
    </div>
    <div class="footer-mini">
      <div class="footer-mini-wrapper">
        <div class="footer-mini-legal">&copy; Copyright {{ new Date().getFullYear() }}. <nuxt-link :to="{ name: 'policy' }"><span class="link">Все права защищены.</span></nuxt-link></div>
        <div class="footer-mini-socials">
          <a href="https://www.facebook.com/ooorealconsulting/" target="_blank"
             class="icon">
            <facebook-icon class="facebook"/>
          </a>
          <a href="https://www.instagram.com/expertiza.by/" target="_blank"
             class="icon">
            <instagram-icon class="instagram"/>
          </a>
        </div>
        <div class="footer-mini-logo">
          <span>Разработка и продвижение:</span>
          <a href="https://voloshin.by/" target="_blank" rel="noopener noreferrer">
            <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 134.28 28.13">
              <defs>
                <style>.cls-1{fill:#fff;filter:url(#luminosity-invert-noclip);}.cls-2{fill:#040706;}.cls-3{fill:#c0312c;}.cls-4{mask:url(#mask);}.cls-5{fill:#262323;}</style>
                <filter id="luminosity-invert-noclip" x="3.69" y="-5.16" width="16.41" height="33.62" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feColorMatrix values="-1 0 0 0 1 0 -1 0 0 1 0 0 -1 0 1 0 0 0 1 0" result="invert"/>
                  <feFlood flood-color="#fff" result="bg"/><feBlend in="invert" in2="bg"/>
                </filter>
                <mask id="mask" x="3.69" y="-5.16" width="16.41" height="33.62" maskUnits="userSpaceOnUse">
                  <polygon class="cls-1" points="18.66 25.04 5.55 -5.16 4.72 2.46 15.59 28.46 18.66 25.04"/>
                </mask>
              </defs>
              <path class="cls-2" d="M30.74,5.18a9.1,9.1,0,0,1,6.68,2.67,8.84,8.84,0,0,1,2.7,6.56A8.91,8.91,0,0,1,37.43,21a8.87,8.87,0,0,1-6.55,2.69A9,9,0,0,1,24.27,21a8.84,8.84,0,0,1-2.69-6.49A9.31,9.31,0,0,1,22.81,9.8a8.85,8.85,0,0,1,3.34-3.39A9,9,0,0,1,30.74,5.18Zm.07,1.71a7.24,7.24,0,0,0-3.7,1,7.4,7.4,0,0,0-2.73,2.74,7.67,7.67,0,0,0-1,3.84,7.39,7.39,0,0,0,7.41,7.45,7.52,7.52,0,0,0,3.79-1,7,7,0,0,0,2.72-2.72,7.61,7.61,0,0,0,1-3.83,7.45,7.45,0,0,0-1-3.78,7.23,7.23,0,0,0-2.75-2.71A7.4,7.4,0,0,0,30.81,6.89Z"/><path class="cls-2" d="M43.64,5.62H45.4V21.51h6.77v1.7H43.64Z"/>
              <path class="cls-2" d="M63.33,5.18A9.14,9.14,0,0,1,70,7.85a8.87,8.87,0,0,1,2.69,6.56A8.91,8.91,0,0,1,70,21a8.87,8.87,0,0,1-6.55,2.69A9,9,0,0,1,56.87,21a8.8,8.8,0,0,1-2.69-6.49A9.3,9.3,0,0,1,55.4,9.8a8.8,8.8,0,0,1,3.35-3.39A8.94,8.94,0,0,1,63.33,5.18Zm.08,1.71a7.19,7.19,0,0,0-3.7,1A7.35,7.35,0,0,0,57,10.65a7.67,7.67,0,0,0-1,3.84,7.17,7.17,0,0,0,2.18,5.29,7.16,7.16,0,0,0,5.24,2.16,7.58,7.58,0,0,0,3.79-1,7.08,7.08,0,0,0,2.71-2.72,7.61,7.61,0,0,0,1-3.83,7.45,7.45,0,0,0-1-3.78,7.28,7.28,0,0,0-2.74-2.71A7.46,7.46,0,0,0,63.41,6.89Z"/>
              <path class="cls-2" d="M74.42,19.93,75.91,19c1,1.94,2.27,2.9,3.65,2.9a3.43,3.43,0,0,0,1.66-.41,2.82,2.82,0,0,0,1.18-1.11A2.86,2.86,0,0,0,82.81,19a3,3,0,0,0-.6-1.73,14.29,14.29,0,0,0-3-2.82A15.65,15.65,0,0,1,76.46,12a4.42,4.42,0,0,1-.93-2.7,4.06,4.06,0,0,1,.55-2.09,4,4,0,0,1,1.55-1.49,4.48,4.48,0,0,1,2.17-.54,4.61,4.61,0,0,1,2.32.62,7.89,7.89,0,0,1,2.29,2.26L83,9.15a6.89,6.89,0,0,0-1.69-1.73A2.94,2.94,0,0,0,79.76,7,2.41,2.41,0,0,0,78,7.64a2.11,2.11,0,0,0-.67,1.59,2.64,2.64,0,0,0,.24,1.11,4.06,4.06,0,0,0,.87,1.18c.23.22,1,.81,2.27,1.75a12.76,12.76,0,0,1,3.13,3,4.77,4.77,0,0,1,.85,2.64,4.48,4.48,0,0,1-1.45,3.32,4.9,4.9,0,0,1-3.54,1.41,5.18,5.18,0,0,1-2.9-.85A7.8,7.8,0,0,1,74.42,19.93Z"/>
              <path class="cls-2" d="M88.41,5.62h1.75V13h9V5.62h1.76V23.21H99.12V14.72h-9v8.49H88.41Z"/>
              <path class="cls-2" d="M104.9,5.62h1.76V23.21H104.9Z"/>
              <path class="cls-2" d="M110.7,23.21V5.62h.39l11.7,13.49V5.62h1.74V23.21h-.4L112.52,9.89V23.21Z"/>
              <rect class="cls-3" x="128.14" y="21.12" width="2.09" height="2.09"/>
              <g class="cls-4">
                <path class="cls-3" d="M18.08,5.19l-4.11,9,.54,1.28.48,1L20.1,5.19Z"/>
                <polyline class="cls-5" points="12.84 21.89 12.06 23.77 11.66 23.77 3.69 5.19 5.71 5.19 12.84 21.89"/>
              </g>
            </svg>
          </a>
        </div>
      </div>
        <div class="footer-legal">&copy; Copyright {{ new Date().getFullYear() }}. <nuxt-link :to="{ name: 'policy' }"><span class="link">Все права защищены.</span></nuxt-link></div>
    </div>
  </footer>
</template>

<script>
import { FacebookIcon, InstagramIcon } from 'vue-feather-icons'

export default {
  components: {
    FacebookIcon, InstagramIcon
  },
  data: () => ({
    appName: 'Auctions',
    open: false,
    profile: false,
    menu: [
      {
        name: 'Площадка',
        children: [
          {
            name: 'О площадке',
            to: { name: 'about' },
          },
          {
            name: 'Контакты',
            to: { name: 'contacts' },
          },
          {
            name: 'Политика конфиденциальности',
            to: { name: 'policy' },
          },
          {
            name: 'Стоимость услуг',
            to: { name: 'pricing' },
          },
        ],
      },
      {
        name: 'Участникам',
        children: [
          {
            name: 'Как участвовать',
            to: { name: 'participate' },
          },
          {
            name: 'Регламент ЭТП',
            to: { name: 'terms' },
          },
          {
            name: 'Часто задаваемые вопросы',
            to: { name: 'faq' },
          },
        ],
      },
    ],
  }),
}
</script>
<style lang="scss" scoped>
@import "~/assets/scss/common.scss";

footer {
  background-color: #fff;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  z-index: 8;
  .footer-directory {
    padding: 2rem 0;
    margin: 0 6rem;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid $border-color;
    .footer-info {
      color: $text-color-2;
      font-size: 0.9rem;
      width: 30%;
      .title {
        color: $text-color-1;
        margin-bottom: 0.5rem;
        font-weight: 500;
      }
    }
    &-wrapper {
      display: flex;
      width: 60%;
    }
    .directory-column {
      display: flex;
      flex-direction: column;
      font-size: 0.9rem;
      margin-right: 3rem;
      flex-basis: 35%;
      .title {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: $text-color-1;
      }
      p {
        font-size: 0.9rem;
        margin: 0.5rem 0;
        color: $text-color-2;
      }
      p:first-child {
        margin: 0rem 0rem 0.5rem 0rem;
      }
      .directory-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
      }
      .directory-list {
        .directory-list-item {
          color: $text-color-2;
          margin-bottom: 0.5rem;
          cursor: pointer;
          .tag {
            vertical-align: super;
            font-size: 0.8rem;
            color: $link-color;
          }
        }
        .directory-list-item:hover {
          color: $text-color-1;
          text-decoration: underline;
        }
      }
    }
  }
  .footer-mini {
    padding: 1rem 2rem;
    margin: 0 4rem;
    font-size: 0.875rem;
    &-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: center;
      .footer-mini-legal {
        color: $text-color-2;
        width: 30%;
        .link {
          color: $link-color;
        }
        .link:hover {
          text-decoration: underline;
        }
      }
      .footer-mini-logo {
        width: 30%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        span {
          margin-right: 0.5rem;
        }
        svg {
          width: 7rem;
          cursor: pointer;
          .cls-3 {
            fill: #9d9d9d;
            transition: .2s ease-in-out;
          }
        }
        svg:hover {
          .cls-3 {
            fill: #c0312c;
          }
        }
      }
      .footer-mini-socials {
        display: flex;
        justify-content: center;
        width: 30%;
        .icon {
          opacity: 0.5;
          transition: .2s ease-in-out;
          .facebook {
            margin-right: 0.5rem;
          }
        }
        .icon:hover {
          opacity: 1;
        }
      }
    }
    .footer-legal {
      display: none;
      color: $text-color-2;
      text-align: center;
      margin-top: 1rem;
      .link {
        color: $link-color;
      }
      .link:hover {
        text-decoration: underline;
      }
    }
  }
}
@media (max-width: 781px) {
  footer {
    .footer-directory {
      display: flex;
      flex-direction: column;
      margin: 0 2rem;
      &-wrapper {
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
        width: 100%;
        .directory-column {
          flex-basis: 0;
        }
      }
      .footer-info {
        width: 100%;
      }
    }
    .footer-mini {
      margin: 0rem;
      &-wrapper {
        .footer-mini-legal {
          display: none;
        }
        .footer-mini-logo {
          span {
            display: none;
          }
        }
        .footer-mini-socials {
          justify-content: flex-start;
        }
      }
      .footer-legal {
        display: block;
      }
    }
  }
}

</style>
