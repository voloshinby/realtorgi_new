<template>
  <loading-spinner v-if="$fetchState.pending"/>
  <section v-else>
    <div v-if="openImage" class="open-image" @click="openImage = !openImage">
      <div class="close-button">
        <x-icon color="white"/>
      </div>
      <div class="open-image-wrapper" @click.stop>
        <div class="image">
          <img :src="'https://realtorgi.by' + auction.gallery[swiperRealIndex].path" alt="">
        </div>
        <div @click="changeSlide('prev')" v-if="auction.gallery.length > 1 && swiperRealIndex !== 0"
             class="swiper-button-prev" slot="button-prev"></div>
        <div @click="changeSlide('next')"
             v-if="auction.gallery.length > 1 && (swiperRealIndex + 1) !== auction.gallery.length"
             class="swiper-button-next" slot="button-next"></div>
      </div>
    </div>
    <div v-if="toggleParticipationPopup" class="participation-popup" @click="openParticipationPopup">
      <div class="participation-popup-form" @click.stop>
        <div class="participation-popup-form-wrapper"
             v-if="(this.$store.state.auth.authorized === true) && (this.$store.state.auth.userData.active) && (this.$store.state.auth.userData.profile.type_user)">
          <h1 class="title">Заявка на участие в аукционе</h1>
          <div class="participation-popup-lot">
            <div class="lot-title">{{ auction.name }}</div>
            <div class="popup-lot-body">
              <div v-if="auction.gallery.length !== 0" class="popup-lot-body-image"><img
                :src="'https://realtorgi.by' + auction.gallery[0].path" :alt="auction.name"></div>
              <div class="popup-lot-body-price">
                <div class="popup-lot-body-price-start">
                  <span class="popup-lot-body-label">Начальная цена</span>
                  <span class="popup-lot-body-value">{{ auction.price_start }} BYN</span>
                </div>
                <!-- <div class="popup-lot-body-price-buy">
                <span class="popup-lot-body-label">Предложения о покупке</span>
                <span class="popup-lot-body-value">{{ auction.price_buy }} BYN</span>
                </div> -->
                <div class="popup-lot-body-price-deposit">
                  <span class="popup-lot-body-label">Задаток</span>
                  <span class="popup-lot-body-value">{{ auction.deposit }} BYN</span>
                </div>
              </div>
            </div>
          </div>
          <div class="remember-checkbox">
            <input type="checkbox" name="remember" v-model="policyAgree">
            <span>Я согласен с условиями <a href="https://realtorgi.by/policy" target="_blank" class="link">Публичной оферты.</a></span>
          </div>
          <div class="remember-checkbox">
            <input type="checkbox" name="reglament" v-model="reglamentAgree">
            <span>Я согласен с <a class="link" href="https://realtorgi.by/terms" target="_blank">Регламентом.</a></span>
          </div>
          <div class="remember-checkbox" v-if="auction.auction.type !== 'classic'">
            <input type="checkbox" name="rules" v-model="rulesAgree">
            <span>Я не являюсь должником предприятия находящегося в процедуре<br/>
            экономической несостоятельности (банкротства), антикризисным управляющим или <br/>
            оператором данной электронной торговой площадки</span>
          </div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup">Отмена</button>
            <button class="submit" @click="submitParticipation">Отправить заявку</button>
          </div>
        </div>
        <div class="participation-popup-form-wrapper" v-if="!this.$store.state.auth.authorized">
          <h1 class="title">Хотите подать заявку?</h1>
          <div class="subtitle">Для участия в аукционе, Вам нужно пройти регистрацию</div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup">Закрыть</button>
            <nuxt-link :to="{name: 'auth'}">
              <button class="submit">Зарегистрироваться</button>
            </nuxt-link>
          </div>
        </div>
        <div class="participation-popup-form-wrapper"
             v-if="this.$store.state.auth.authorized === true && !this.$store.state.auth.userData.profile.type_user && this.$store.state.auth.userData.active">
          <h1 class="title">Хотите подать заявку?</h1>
          <div class="subtitle">Для участия в аукционе, Вам нужно заполнить свой профиль</div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup">Закрыть</button>
            <nuxt-link :to="{name: 'account-profiles'}">
              <button class="submit">Заполнить профиль</button>
            </nuxt-link>
          </div>
        </div>
        <div class="participation-popup-form-wrapper"
             v-if="(this.$store.state.auth.authorized === true) && (!this.$store.state.auth.userData.active)">
          <h1 class="title">Хотите подать заявку?</h1>
          <div class="subtitle">Для участия в аукционе, Вам нужно подтвердить Вашу почту</div>
          <div class="participation-popup-buttons">
            <button class="cancel" @click="openParticipationPopup">Закрыть</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="commonRulesOpen" class="common-rules-popup" @click="commonRulesOpen = !commonRulesOpen">
      <div class="popup-pre-wrapper">
        <div class="common-rules-wrapper" @click.stop>
          <div class="button-close" @click="commonRulesOpen = !commonRulesOpen">
            <x-icon/>
          </div>
          <h1 class="title">Общие правила</h1>
          <ul class="rules" v-if="auction.auction.type !== 'econom'">
            <li class="rule">1. Электронные торги проходят на увеличение начальной цены.</li>
            <li class="rule">2. Начальная цена предмета аукциона в ходе торгов повышается на величину шага аукциона.
            </li>
            <li class="rule">3. Участники делают свои ставки.</li>
            <li class="rule">4. Победителем аукциона признаётся тот участник, который предложит максимальную цену за
              лот.
            </li>
            <li class="rule">5. В случае регистрации на аукцион одного участника и участия в аукциона единственного
              участника, ему предлагается приобрести лот по начальной цене увеличенной на 5%.
            </li>
            <li class="rule">6. Торги завершаются Оператором ЭТП в автоматическом режиме.</li>
          </ul>
          <ul class="rules" v-else>
            <li class="rule">1. Электронные торги проходят на увеличение или понижение начальной цены.</li>
            <li class="rule">2. Начальная цена лота ежечасно, начиная со 2-го часа проведения электронных торгов, при
              отсутствии ставок на повышение начальной цены, снижается равными долями до минимальной цены лота.
            </li>
            <li class="rule">3. В ходе торгов любой участник имеет право сделать ставку, которая отражает его ценовое
              предложение. Ставка участника должна превысить предыдущую ставку на установленный шаг аукциона.
            </li>
            <li class="rule">4. Победителем торгов признается участник, предложивший максимальную цену за лот.</li>
            <li class="rule">5. Срок проведения торгов с 9:00 до 16:00.</li>
            <li class="rule">6. Торги завершаются Оператором ЭТП в автоматическом режиме. Если в ходе проведения торгов
              один из участников торгов сделает ставку менее, чем за 3 минуты до завершения торгов, они продлеваются на
              время установленное организатором торгов, о чем участники оповещаются Оператором ЭТП.
            </li>
            <li class="rule">7. В случае регистрации на аукцион одного участника и участия в аукционе единственного
              участника, ему предлагается приобрести лот по начальной цене увеличенной на 5%.
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div v-if="informationPopupOpen" class="information-popup" @click="informationPopupOpen = !informationPopupOpen">
      <div class="information-wrapper" @click.stop>
        <div class="button-close" @click="informationPopupOpen = !informationPopupOpen">
          <x-icon/>
        </div>
        <h1 class="title">Заявка на участие в аукционе</h1>
        <div class="warning">Внимание</div>
        <div class="text">
          <p>
            Ваша заявка на участие принята. После проверки указанных данных и оплаты задатка Вы будете допущены к
            участию в аукционе.
          </p>
          <p>
            Вам следует связаться с организатором аукциона по телефону +375 25 549 86 22 для подтверждения участия в
            аукционе и согласования факта оплаты задатка.
          </p>
        </div>
      </div>
    </div>
    <div v-if="toggleMakeBidPopup" class="make-bid-popup">
      <div class="make-bid-popup-form">
        <div class="make-bid-popup-form-wrapper" v-if="this.$store.state.auth.authorized === true">
          <h1 class="title">Сделать ставку</h1>
          <div class="subtitle">Минимальный размер ставки: <span class="subtitle-bid">{{ currentBid.toFixed(2) }}</span>
          </div>
          <input class="bid-input" type="text" v-model='inputBid' @input="countUserBid">
          <div class="bid-error"></div>
          <div class="warning">
            <p>Если поле будет пустым, или введенная ставка будет меньше минимальной, <br>то ставка выставится
              автоматически и будет равна размеру минимальной ставки.</p>
          </div>
          <div class="make-bid-popup-buttons">
            <button class="cancel" @click="toggleMakeBidPopup = !toggleMakeBidPopup">Отмена</button>
            <button class="submit" @click="makeBid">Сделать ставку</button>
          </div>
        </div>
      </div>
    </div>
    <div class="wrapper">
      <div class="wrapper-left">
        <div class="choosen-image" v-if="auction.gallery.length !== 0">
          <img @click="openImage = !openImage" :src="'https://realtorgi.by' + auction.gallery[swiperRealIndex].path"
               alt="">
        </div>
        <div v-if="auction.gallery.length !== 0" class="images-wrapper">
          <swiper @slideChange="slideChanged" ref="swiper" class="images" :options="swiperOption"
                  :class="{'full-screen': sliderOpen}">
            <swiper-slide v-for="auctionImage in auction.gallery" :key="auctionImage.id">
              <div class="swiper-zoom-container">
                <img :src="'https://realtorgi.by' + auctionImage.path" :alt="auction.name"
                     @click="sliderOpen = !sliderOpen">
              </div>
            </swiper-slide>
            <div v-if="auction.gallery.length > 1 && swiperRealIndex !== 0" class="swiper-button-prev"
                 slot="button-prev"></div>
            <div v-if="auction.gallery.length > 1 && (swiperRealIndex + 1) !== auction.gallery.length"
                 class="swiper-button-next" slot="button-next"></div>
          </swiper>
        </div>
        <div class="users-bids">
          <tabs :options="{ useUrlFragment: false }">
            <tab name="Журнал торгов">
              <div class="bid-table" v-if="betHistory.length > 0">
                <div class="row">
                  <span class="time">Время</span>
                  <span class="username">Событие</span>
                  <span class="price">Текущая цена</span>
                </div>
                <div class="row" v-for="event in betHistory" :key="event.id">
                  <span class="time">{{ moment(event.created_at).format('HH:mm:ss') }}</span>
                  <span class="" v-if="!isCurrentuser(event)">Пользователь №{{ event.user_id }} сделал ставку</span>
                  <span class="current-username" v-if="isCurrentuser(event)">Пользователь №{{ event.user_id }} сделал ставку</span>
                  <span class="price">{{ event.bet_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} BYN</span>
                </div>
              </div>
              <div class="bid-table" v-else>
                <div class="row">
                  <span class="time">Время</span>
                  <span class="username">Событие</span>
                  <span class="price">Текущая цена</span>
                </div>
                <div class="row" v-if="suggest">
                  <span class="time"></span>
                  <span class="">Пользователь №{{ suggest }} купил лот</span>
                  <span class="price">{{ priceSuggest.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} BYN</span>
                </div>
                <div class="row no-events" v-else>
                  <span> Нет событий</span>
                </div>
              </div>
              <div class="row" v-for="event in betHistory" :key="event.id">
                <div v-if="event.winner == 1" class="winner-lot"> Победитель лота - пользователь №{{
                    event.user_id
                  }}
                </div>
              </div>
            </tab>
            <tab name="Журнал событий">
              <div class="bid-table" v-if="auction.comments.length > 0">
                <div class="row">
                  <span class="time">Время</span>
                  <span class="username">Комментарий</span>
                  <span class="price">Автор</span>
                </div>

                <div class="row" v-for="event in auction.comments" :key="event.id">
                  <span class="time">{{ moment(event.created_at).format('HH:mm:ss') }}</span>
                  <span class="username">{{ event.comment }}</span>
                  <span v-if="event.user.type === 'admin'">Администратор</span>
                  <span v-if="event.user.type === 'user'">Организатор аукциона</span>
                </div>
              </div>
              <div class="bid-table" v-else>
                <div class="row">
                  <span class="time">Время</span>
                  <span class="username">Комментарий</span>
                  <span class="price">Автор</span>
                </div>
                <div class="row no-events">
                  <span>Нет событий</span>
                </div>
              </div>
            </tab>
          </tabs>
        </div>
      </div>
      <div class="wrapper-right">
        <div class="lot-title">
          <h1 class="title">{{ auction.name }}</h1>
          <div class="info">
            <div class="info-wrapper-mobile">
              <div class="articles">
                <div class="article">
                  <span class="article-title">
                    Номер лота
                  </span>
                  <span class="article-value">
                    {{ auction.lot_number }} <nuxt-link
                    :to="{name: 'auctions-all-id', params: { id: auction.auction.id, auctionNumber: auction.auction.auction_number }}"><span
                    class="auction-number">(<span class="auction-link">Аукцион №{{
                      auction.auction.auction_number
                    }}</span>)</span></nuxt-link>
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                  Статус
                  </span>
                  <span class="article-value">
                  {{ auction.status }}
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Категория
                  </span>
                  <span class="article-value">
                    {{ auction.category.name }}
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Тип
                  </span>
                  <span class="article-value">
                    {{
                      auction.auction.type === 'econom' ? 'Торги в результате экономической несостоятельности' : 'Классические торги'
                    }}
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Количество допущенных к торгам участников
                  </span>
                  <span v-if="auction.users_count > 0" class="article-value">
                    {{ auction.users_count }}
                  </span>
                  <span v-else class="article-value">
                    Нет участников
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Дата конца приема заявок
                  </span>
                  <span class="article-value">
                    {{ moment((parseInt(auction.auction.ends_at)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Дата начала проведения торгов
                  </span>
                  <span class="article-value">
                    {{ moment((parseInt(auction.auction.start_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </span>
                </div>
                <div class="article">
                  <span class="article-title">
                    Дата конца проведения торгов
                  </span>
                  <span class="article-value">
                    {{ moment((parseInt(auction.auction.end_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </span>
                </div>
                <div class="article rules">
                  <div @click="commonRulesOpen = !commonRulesOpen" class="common-rules-button">
                    <span class="article-title">
                      Общие правила
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="info-wrapper">
              <div class="articles">
                <div class="article">
                  <div class="article-param">
                    Номер лота
                  </div>
                  <div class="article-value">
                    {{ auction.lot_number }}
                    <nuxt-link
                      :to="{name: 'auctions-all-id', params: { id: auction.auction.id, auctionNumber: auction.auction.auction_number }}">
                      <span class="auction-number">(<span
                        class="auction-link">Аукцион №{{ auction.auction.auction_number }}</span>)</span></nuxt-link>
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Статус
                  </div>
                  <div class="article-value">
                    {{ auction.status }}
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Категория
                  </div>
                  <div class="article-value">
                    {{ auction.category.name }}
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Тип
                  </div>
                  <div class="article-value">
                    {{
                      auction.auction.type === 'econom' ? 'Торги в результате экономической несостоятельности' : 'Классические торги'
                    }}
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Количество допущенных к торгам участников
                  </div>
                  <div v-if="auction.users_count > 0" class="article-value">
                    {{ auction.users_count }}
                  </div>
                  <div v-else class="article-value">
                    Нет участников
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Дата конца приема заявок
                  </div>
                  <div class="article-value">
                    {{ moment((parseInt(auction.auction.ends_at)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Дата начала проведения торгов
                  </div>
                  <div class="article-value">
                    {{ moment((parseInt(auction.auction.start_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </div>
                </div>
                <div class="article">
                  <div class="article-param">
                    Дата конца проведения торгов
                  </div>
                  <div class="article-value">
                    {{ moment((parseInt(auction.auction.end_selling)) * 1000).format('Do MMMM YYYY, HH:mm') }}
                  </div>
                </div>
                <div class="article rules">
                  <div @click="commonRulesOpen = !commonRulesOpen" class="common-rules-button">
                    <span class="article-param">
                      Общие правила
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="auction.status === 'Предстоящие' || auction.status === 'Повторные' || auction.status === 'Текущие'"
             class="timer">
          <div class="article"
               v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceStart > 0">
            <div class="article-param">
              До начала приема заявок
            </div>
            <div class="article-value">
              <span class="timer-element">
                <span class="number">{{ daysStart }}</span>
                <span class="word" v-if="
                  daysStart == 1
                  ||
                  daysStart == 21
                  ||
                  daysStart == 31
                ">
                  день
                </span>
                <span class="word" v-if="
                  (daysStart > 1
                  &&
                  daysStart < 5)
                  ||
                  (daysStart > 21
                  &&
                  daysStart < 25)
                ">
                  дня
                </span>
                <span class="word" v-if="
                  daysStart == 0
                  ||
                  (daysStart > 4
                  &&
                  daysStart < 21)
                  ||
                  (daysStart > 25
                  &&
                  daysStart < 31)
                ">
                  дней
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ hoursStart }}</span>
                <span class="word" v-if="
                  hoursStart == 1
                ">
                  час
                </span>
                <span class="word" v-if="
                  hoursStart == 2
                  ||
                  hoursStart == 3
                  ||
                  hoursStart == 4
                ">
                  часа
                </span>
                <span class="word" v-if="
                  hoursStart == 0
                  ||
                  hoursStart > 4
                ">
                  часов
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ minutesStart }}</span>
                <span class="word" v-if="
                  minutesStart == 1
                  ||
                  minutesStart == 21
                  ||
                  minutesStart == 31
                  ||
                  minutesStart == 41
                  ||
                  minutesStart == 51
                ">
                  минута
                </span>
                <span class="word" v-if="
                  (minutesStart > 1
                  &&
                  minutesStart < 5)
                  ||
                  (minutesStart > 21
                  &&
                  minutesStart < 25)
                  ||
                  (minutesStart > 31
                  &&
                  minutesStart < 35)
                  ||
                  (minutesStart > 41
                  &&
                  minutesStart < 45)
                  ||
                  (minutesStart > 51
                  &&
                  minutesStart < 55)
                ">
                  минуты
                </span>
                <span class="word" v-if="
                  minutesStart == 0
                  ||
                  (minutesStart > 4
                  &&
                  minutesStart < 21)
                  ||
                  (minutesStart > 24
                  &&
                  minutesStart < 31)
                  ||
                  (minutesStart > 34
                  &&
                  minutesStart < 41)
                  ||
                  (minutesStart > 44
                  &&
                  minutesStart < 51)
                  ||
                  (minutesStart > 54
                  &&
                  minutesStart < 61)
                ">
                  минут
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ secondsStart }}</span>
                <span class="word" v-if="
                  secondsStart == 1
                  ||
                  secondsStart == 21
                  ||
                  secondsStart == 31
                  ||
                  secondsStart == 41
                  ||
                  secondsStart == 51
                ">
                  секунда
                </span>
                <span class="word" v-if="
                  (secondsStart > 1
                  &&
                  secondsStart < 5)
                  ||
                  (secondsStart > 21
                  &&
                  secondsStart < 25)
                  ||
                  (secondsStart > 31
                  &&
                  secondsStart < 35)
                  ||
                  (secondsStart > 41
                  &&
                  secondsStart < 45)
                  ||
                  (secondsStart > 51
                  &&
                  secondsStart < 55)
                ">
                  секунды
                </span>
                <span class="word" v-if="
                  secondsStart == 0
                  ||
                  (secondsStart > 4
                  &&
                  secondsStart < 21)
                  ||
                  (secondsStart > 24
                  &&
                  secondsStart < 31)
                  ||
                  (secondsStart > 34
                  &&
                  secondsStart < 41)
                  ||
                  (secondsStart > 44
                  &&
                  secondsStart < 51)
                  ||
                  (secondsStart > 54
                  &&
                  secondsStart < 61)
                ">
                  секунд
                </span>
              </span>
            </div>
          </div>
          <div class="article"
               v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceStart < 0 && distanceEnd > 0">
            <div class="article-param">
              До конца приема заявок
            </div>
            <div class="article-value">
              <span class="timer-element">
                <span class="number">{{ daysEnd }}</span>
                <span class="word" v-if="
                  daysEnd == 1
                  ||
                  daysEnd == 21
                  ||
                  daysEnd == 31
                ">
                  день
                </span>
                <span class="word" v-if="
                  (daysEnd > 1
                  &&
                  daysEnd < 5)
                  ||
                  (daysEnd > 21
                  &&
                  daysEnd < 25)
                ">
                  дня
                </span>
                <span class="word" v-if="
                  daysEnd == 0
                  ||
                  (daysEnd > 4
                  &&
                  daysEnd < 21)
                  ||
                  (daysEnd > 25
                  &&
                  daysEnd < 31)
                ">
                  дней
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ hoursEnd }}</span>
                <span class="word" v-if="
                  hoursEnd == 1
                ">
                  час
                </span>
                <span class="word" v-if="
                  hoursEnd == 2
                  ||
                  hoursEnd == 3
                  ||
                  hoursEnd == 4
                ">
                  часа
                </span>
                <span class="word" v-if="
                  hoursEnd == 0
                  ||
                  hoursEnd > 4
                ">
                  часов
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ minutesEnd }}</span>
                <span class="word" v-if="
                  minutesEnd == 1
                  ||
                  minutesEnd == 21
                  ||
                  minutesEnd == 31
                  ||
                  minutesEnd == 41
                  ||
                  minutesEnd == 51
                ">
                  минута
                </span>
                <span class="word" v-if="
                  (minutesEnd > 1
                  &&
                  minutesEnd < 5)
                  ||
                  (minutesEnd > 21
                  &&
                  minutesEnd < 25)
                  ||
                  (minutesEnd > 31
                  &&
                  minutesEnd < 35)
                  ||
                  (minutesEnd > 41
                  &&
                  minutesEnd < 45)
                  ||
                  (minutesEnd > 51
                  &&
                  minutesEnd < 55)
                ">
                  минуты
                </span>
                <span class="word" v-if="
                  minutesEnd == 0
                  ||
                  (minutesEnd > 4
                  &&
                  minutesEnd < 21)
                  ||
                  (minutesEnd > 24
                  &&
                  minutesEnd < 31)
                  ||
                  (minutesEnd > 34
                  &&
                  minutesEnd < 41)
                  ||
                  (minutesEnd > 44
                  &&
                  minutesEnd < 51)
                  ||
                  (minutesEnd > 54
                  &&
                  minutesEnd < 61)
                ">
                  минут
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ secondsEnd }}</span>
                <span class="word" v-if="
                  secondsEnd == 1
                  ||
                  secondsEnd == 21
                  ||
                  secondsEnd == 31
                  ||
                  secondsEnd == 41
                  ||
                  secondsEnd == 51
                ">
                  секунда
                </span>
                <span class="word" v-if="
                  (secondsEnd > 1
                  &&
                  secondsEnd < 5)
                  ||
                  (secondsEnd > 21
                  &&
                  secondsEnd < 25)
                  ||
                  (secondsEnd > 31
                  &&
                  secondsEnd < 35)
                  ||
                  (secondsEnd > 41
                  &&
                  secondsEnd < 45)
                  ||
                  (secondsEnd > 51
                  &&
                  secondsEnd < 55)
                ">
                  секунды
                </span>
                <span class="word" v-if="
                  secondsEnd == 0
                  ||
                  (secondsEnd > 4
                  &&
                  secondsEnd < 21)
                  ||
                  (secondsEnd > 24
                  &&
                  secondsEnd < 31)
                  ||
                  (secondsEnd > 34
                  &&
                  secondsEnd < 41)
                  ||
                  (secondsEnd > 44
                  &&
                  secondsEnd < 51)
                  ||
                  (secondsEnd > 54
                  &&
                  secondsEnd < 61)
                ">
                  секунд
                </span>
              </span>
            </div>
          </div>
          <div class="article"
               v-if="((auction.status === 'Предстоящие' || auction.status === 'Повторные')) && distanceEnd < 0 && distanceStartSelling > 0">
            <div class="article-param">
              До начала торгов
            </div>
            <div class="article-value">
              <span class="timer-element">
                <span class="number">{{ daysStartSelling }}</span>
                <span class="word" v-if="
                  daysStartSelling == 1
                  ||
                  daysStartSelling == 21
                  ||
                  daysStartSelling == 31
                ">
                  день
                </span>
                <span class="word" v-if="
                  (daysStartSelling > 1
                  &&
                  daysStartSelling < 5)
                  ||
                  (daysStartSelling > 21
                  &&
                  daysStartSelling < 25)
                ">
                  дня
                </span>
                <span class="word" v-if="
                  daysStartSelling == 0
                  ||
                  (daysStartSelling > 4
                  &&
                  daysStartSelling < 21)
                  ||
                  (daysStartSelling > 25
                  &&
                  daysStartSelling < 31)
                ">
                  дней
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ hoursStartSelling }}</span>
                <span class="word" v-if="
                  hoursStartSelling == 1
                ">
                  час
                </span>
                <span class="word" v-if="
                  hoursStartSelling == 2
                  ||
                  hoursStartSelling == 3
                  ||
                  hoursStartSelling == 4
                ">
                  часа
                </span>
                <span class="word" v-if="
                  hoursStartSelling == 0
                  ||
                  hoursStartSelling > 4
                ">
                  часов
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ minutesStartSelling }}</span>
                <span class="word" v-if="
                  minutesStartSelling == 1
                  ||
                  minutesStartSelling == 21
                  ||
                  minutesStartSelling == 31
                  ||
                  minutesStartSelling == 41
                  ||
                  minutesStartSelling == 51
                ">
                  минута
                </span>
                <span class="word" v-if="
                  (minutesStartSelling > 1
                  &&
                  minutesStartSelling < 5)
                  ||
                  (minutesStartSelling > 21
                  &&
                  minutesStartSelling < 25)
                  ||
                  (minutesStartSelling > 31
                  &&
                  minutesStartSelling < 35)
                  ||
                  (minutesStartSelling > 41
                  &&
                  minutesStartSelling < 45)
                  ||
                  (minutesStartSelling > 51
                  &&
                  minutesStartSelling < 55)
                ">
                  минуты
                </span>
                <span class="word" v-if="
                  minutesStartSelling == 0
                  ||
                  (minutesStartSelling > 4
                  &&
                  minutesStartSelling < 21)
                  ||
                  (minutesStartSelling > 24
                  &&
                  minutesStartSelling < 31)
                  ||
                  (minutesStartSelling > 34
                  &&
                  minutesStartSelling < 41)
                  ||
                  (minutesStartSelling > 44
                  &&
                  minutesStartSelling < 51)
                  ||
                  (minutesStartSelling > 54
                  &&
                  minutesStartSelling < 61)
                ">
                  минут
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ secondsStartSelling }}</span>
                <span class="word" v-if="
                  secondsStartSelling == 1
                  ||
                  secondsStartSelling == 21
                  ||
                  secondsStartSelling == 31
                  ||
                  secondsStartSelling == 41
                  ||
                  secondsStartSelling == 51
                ">
                  секунда
                </span>
                <span class="word" v-if="
                  (secondsStartSelling > 1
                  &&
                  secondsStartSelling < 5)
                  ||
                  (secondsStartSelling > 21
                  &&
                  secondsStartSelling < 25)
                  ||
                  (secondsStartSelling > 31
                  &&
                  secondsStartSelling < 35)
                  ||
                  (secondsStartSelling > 41
                  &&
                  secondsStartSelling < 45)
                  ||
                  (secondsStartSelling > 51
                  &&
                  secondsStartSelling < 55)
                ">
                  секунды
                </span>
                <span class="word" v-if="
                  secondsStartSelling == 0
                  ||
                  (secondsStartSelling > 4
                  &&
                  secondsStartSelling < 21)
                  ||
                  (secondsStartSelling > 24
                  &&
                  secondsStartSelling < 31)
                  ||
                  (secondsStartSelling > 34
                  &&
                  secondsStartSelling < 41)
                  ||
                  (secondsStartSelling > 44
                  &&
                  secondsStartSelling < 51)
                  ||
                  (secondsStartSelling > 54
                  &&
                  secondsStartSelling < 61)
                ">
                  секунд
                </span>
              </span>
            </div>
          </div>
          <div class="article" v-if="auction.status === 'Текущие' && distanceEndSelling > 0">
            <div class="article-param">
              До конца торгов
            </div>
            <div class="article-value">
              <span class="timer-element">
                <span class="number">{{ daysEndSelling }}</span>
                <span class="word" v-if="
                  daysEndSelling == 1
                  ||
                  daysEndSelling == 21
                  ||
                  daysEndSelling == 31
                ">
                  день
                </span>
                <span class="word" v-if="
                  (daysEndSelling > 1
                  &&
                  daysEndSelling < 5)
                  ||
                  (daysEndSelling > 21
                  &&
                  daysEndSelling < 25)
                ">
                  дня
                </span>
                <span class="word" v-if="
                  daysEndSelling == 0
                  ||
                  (daysEndSelling > 4
                  &&
                  daysEndSelling < 21)
                  ||
                  (daysEndSelling > 25
                  &&
                  daysEndSelling < 31)
                ">
                  дней
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ hoursEndSelling }}</span>
                <span class="word" v-if="
                  hoursEndSelling == 1
                ">
                  час
                </span>
                <span class="word" v-if="
                  hoursEndSelling == 2
                  ||
                  hoursEndSelling == 3
                  ||
                  hoursEndSelling == 4
                ">
                  часа
                </span>
                <span class="word" v-if="
                  hoursEndSelling == 0
                  ||
                  hoursEndSelling > 4
                ">
                  часов
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ minutesEndSelling }}</span>
                <span class="word" v-if="
                  minutesEndSelling == 1
                  ||
                  minutesEndSelling == 21
                  ||
                  minutesEndSelling == 31
                  ||
                  minutesEndSelling == 41
                  ||
                  minutesEndSelling == 51
                ">
                  минута
                </span>
                <span class="word" v-if="
                  (minutesEndSelling > 1
                  &&
                  minutesEndSelling < 5)
                  ||
                  (minutesEndSelling > 21
                  &&
                  minutesEndSelling < 25)
                  ||
                  (minutesEndSelling > 31
                  &&
                  minutesEndSelling < 35)
                  ||
                  (minutesEndSelling > 41
                  &&
                  minutesEndSelling < 45)
                  ||
                  (minutesEndSelling > 51
                  &&
                  minutesEndSelling < 55)
                ">
                  минуты
                </span>
                <span class="word" v-if="
                  minutesEndSelling == 0
                  ||
                  (minutesEndSelling > 4
                  &&
                  minutesEndSelling < 21)
                  ||
                  (minutesEndSelling > 24
                  &&
                  minutesEndSelling < 31)
                  ||
                  (minutesEndSelling > 34
                  &&
                  minutesEndSelling < 41)
                  ||
                  (minutesEndSelling > 44
                  &&
                  minutesEndSelling < 51)
                  ||
                  (minutesEndSelling > 54
                  &&
                  minutesEndSelling < 61)
                ">
                  минут
                </span>
              </span>
              <span class="timer-element">
                <span class="number">{{ secondsEndSelling }}</span>
                <span class="word" v-if="
                  secondsEndSelling == 1
                  ||
                  secondsEndSelling == 21
                  ||
                  secondsEndSelling == 31
                  ||
                  secondsEndSelling == 41
                  ||
                  secondsEndSelling == 51
                ">
                  секунда
                </span>
                <span class="word" v-if="
                  (secondsEndSelling > 1
                  &&
                  secondsEndSelling < 5)
                  ||
                  (secondsEndSelling > 21
                  &&
                  secondsEndSelling < 25)
                  ||
                  (secondsEndSelling > 31
                  &&
                  secondsEndSelling < 35)
                  ||
                  (secondsEndSelling > 41
                  &&
                  secondsEndSelling < 45)
                  ||
                  (secondsEndSelling > 51
                  &&
                  secondsEndSelling < 55)
                ">
                  секунды
                </span>
                <span class="word" v-if="
                  secondsEndSelling == 0
                  ||
                  (secondsEndSelling > 4
                  &&
                  secondsEndSelling < 21)
                  ||
                  (secondsEndSelling > 24
                  &&
                  secondsEndSelling < 31)
                  ||
                  (secondsEndSelling > 34
                  &&
                  secondsEndSelling < 41)
                  ||
                  (secondsEndSelling > 44
                  &&
                  secondsEndSelling < 51)
                  ||
                  (secondsEndSelling > 54
                  &&
                  secondsEndSelling < 61)
                ">
                  секунд
                </span>
              </span>
            </div>
          </div>
        </div>
        <div class="price">
          <h1 class="title">Цена</h1>
          <div class="price-wrapper">
            <div class="price-left">
              <div v-if="betHistory.length > 0" class="current-price">
                {{ betHistory[0].bet_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} BYN
              </div>
              <div v-else class="current-price">{{
                  auction.price_start.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
                }} BYN
              </div>
              <span>Текущая цена аукциона</span>
            </div>
            <div class="price-right">
              <button
                v-if="((auction.status === 'Предстоящие' && auction.auction.seller_id !== this.$store.state.auth.userData.id || auction.status === 'Повторные' && auction.auction.seller_id !== this.$store.state.auth.userData.id )) && distanceStart < 0 && distanceEnd > 0 && canSendRequest === true"
                @click="openParticipationPopup">Подать заявку
              </button>
              <button @click="informationPopupOpen = !informationPopupOpen"
                      v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceStart < 0 && canSendRequest === false && confirmedRequest === false">
                Заявка ожидает подтверждения
              </button>
              <div
                v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceStart < 0 && canSendRequest === false && confirmedRequest === true">
                Ваша заявка подтверждена
              </div>
              <div v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceStart > 0">
                Прием заявок скоро откроется
              </div>
              <div v-if="(auction.status === 'Предстоящие' || auction.status === 'Повторные') && distanceEnd < 0">Прием
                заявок закрыт
              </div>
              <button v-if="auction.status === 'Текущие' && canSendRequest === false && confirmedRequest === true"
                      @click="[toggleMakeBidPopup = !toggleMakeBidPopup, countBid]">Сделать ставку
              </button>
              <button
                v-if="currentConfirm == 1 && this.currentConfirmCount == 1 && suggest === null && auction.status === 'Несостоявшиеся'"
                @click="makeSell()">Cогласен приобрести лот
              </button>
              <div v-if="auction.status === 'Текущие' && canSendRequest === true && confirmedRequest === false">Вы не
                подали заявку на участие
              </div>
              <div v-if="auction.status === 'Текущие' && canSendRequest === false && confirmedRequest === false">Ваша
                заявка не была подтверждена
              </div>
            </div>
          </div>
          <div class="price-info">
            <div class="price-top">
              <div class="price-start">
                <span class="label">Начальная цена</span>
                <span class="value">{{
                    auction.price_start.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
                  }} BYN</span>
              </div>

              <div class="price-min" v-if="auction.auction.type !== 'classic' && auction.price_min">
                <span class="label">Минимальная цена</span>
                <span class="value">{{ auction.price_min.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} BYN</span>
              </div>

            </div>
            <div class="price-bottom">
              <div class="price-deposit">
                <span class="label">Задаток</span>
                <span class="value">{{ auction.deposit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} BYN</span>
              </div>
              <div class="price-step">
                <span class="label">Шаг торгов</span>
                <span v-if="this.auction.step === 2 && !auction.price_step" class="value">5 %</span>
                <span v-if="this.auction.step === 2 && auction.price_step"
                      class="value">{{ auction.price_step.replace(/\D/g, '') }} %</span>
                <span v-if="this.auction.step === 1 && auction.price_step" class="value">{{
                    auction.price_step.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
                  }} BYN</span>
                <span v-if="this.auction.step === 1 && !auction.price_step" class="value">{{
                    (auction.price_start * 0.05).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
                  }} BYN</span>
              </div>
              <div class="price-buy" v-if="currentConfirm == 1 && this.currentConfirmCount == 1">
                <span class="label">Предложения о покупке</span>
                <span class="value">{{ price_buy }} BYN</span>
              </div>
            </div>
          </div>
        </div>
        <div class="dropout-list">
          <h1 class="title">Подробная информация</h1>
          <div class="dropout" v-if="auction.info">
            <div @click="toggleDropoutContent(1)" class="dropout-title">
              <span>Описание</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 1) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 1) }" class="content" v-html="auction.info">{{
                auction.info
              }}
            </div>
          </div>
          <div class="dropout" v-if="auction.additional_info">
            <div @click="toggleDropoutContent(2)" class="dropout-title">
              <span>Дополнительная информация</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 2) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 2) }" class="content" v-html="auction.additional_info">
              {{ auction.additional_info }}
            </div>
          </div>
          <div class="dropout" v-if="auctionItems.length != 0">
            <div @click="toggleDropoutContent(3)" class="dropout-title">
              <span>Предметы в лоте ({{ auctionItems.length }})</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 3) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 3) }" class="content">
              <div class="lot-item" v-for="(item, index) in auctionItems" :key="index">
                <div class="lot-item-title">
                  {{ item.name }} (Предмет №{{ index + 1 }})
                </div>
                <div class="lot-item-description" v-html="item.info">
                  {{ item.info }}
                </div>
                <swiper class="images" :options="swiperOption" :class="{'full-screen': sliderOpen}">
                  <swiper-slide v-for="image in item.gallery" :key="image.id">
                    <div class="swiper-zoom-container">
                      <img :src="'https://realtorgi.by' + image.path" :alt="item.name">
                    </div>
                  </swiper-slide>
                </swiper>
              </div>
            </div>
          </div>
          <div class="dropout" v-if="auction.auction.anticrisis_manager">
            <div @click="toggleDropoutContent(4)" class="dropout-title">
              <span>Антикризисный управляющий</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 4) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 4) }" class="content">
              <div class="seller">
                <span class="seller-name">Собственник: <span class="seller-value">{{
                    auction.auction.anticrisis_manager
                  }}</span></span>
                <span class="seller-phone">Номер собственника: <span
                  class="seller-value">{{ auction.auction.anticrisis_manager_phone }}</span></span>
              </div>
            </div>
          </div>
          <div class="dropout">
            <div @click="toggleDropoutContent(5)" class="dropout-title">
              <span>Собственник</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 5) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 5) }" class="content">
              <div class="seller">
                <span class="seller-name">Собственник: <span class="seller-value">{{
                    auction.auction.contact_person
                  }}</span></span>
                <span class="seller-phone">Номер собственника: <span
                  class="seller-value">{{ auction.auction.seller_phone }}</span></span>
              </div>
            </div>
          </div>
          <div class="dropout">
            <div @click="toggleDropoutContent(6)" class="dropout-title" v-if="auction.auction.how_to_view_the_property">
              <span>Как посмотреть имущество</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 6) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 6) }" class="content"
                 v-html="auction.auction.how_to_view_the_property">{{ auction.auction.how_to_view_the_property }}
            </div>
          </div>
          <div class="dropout">
            <div @click="toggleDropoutContent(7)" class="dropout-title">
              <span>Документы по лоту</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 7) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 7) }" class="content">
              <ul v-if="auction.files.length !== 0" class="list">
                <li v-for="file in auction.files" :key="file.id"><a target="_blank"
                                                                    :href="'https://realtorgi.by' + file.path">{{
                    file.name
                  }}</a></li>
              </ul>
              <p v-else>Нет документов</p>
            </div>
          </div>
          <div class="dropout">
            <div @click="toggleDropoutContent(8)" class="dropout-title">
              <span>Документы по аукциону</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 8) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 8) }" class="content">
              <ul v-if="auction.auction_files.length !== 0" class="list">
                <li v-for="file in auction.auction_files" :key="file.id"><a target="_blank"
                                                                            :href="'https://realtorgi.by' + file.path">{{
                    file.name
                  }}</a></li>
              </ul>
              <p v-else>Нет документов</p>
            </div>
          </div>
          <div class="dropout">
            <div @click="toggleDropoutContent(9)" class="dropout-title">
              <span>Организатор торгов</span>
              <chevron-down-icon :class="{ rotated: (showDropoutContent === 9) }"/>
            </div>
            <div :class="{ visible: (showDropoutContent === 9) }" class="content">
              <p>
                ООО «Реалконсалтинг» <br/>
                220015 Республика Беларусь, г. Минск, <br/>
                ул. Пономаренко, д.35А, пом.701. каб. 8 <br/>
                р/с BY70SLAN30124358100130000000 <br/>
                в ЗАО Банк ВТБ (Беларусь), БИК SLANBY22, 220007, <br/>
                г. Минск, ул. Московская, 14 <br/>
                УНП 191302068
              </p>
            </div>
            <div class="dropout">
              <div @click="toggleDropoutContent(10)" class="dropout-title">
                <span>Реквизиты для внесения задатка</span>
                <chevron-down-icon :class="{ rotated: (showDropoutContent === 10) }"/>
              </div>
              <div :class="{ visible: (showDropoutContent === 10) }" class="content">
                <p>
                  - для резидентов РБ задаток перечисляется в белорусских рублях по
                  курсу НБ РБ на дату перечисления Организатору аукциона задатка на
                  расчетный счет (IBAN): BY70SLAN30124358100130000000 в ЗАО Банк
                  ВТБ (Беларусь), г. Минск, ул. Московская, 14, BIC: SLANBY22;
                  <br/>
                  <br/>
                  - для нерезидентов РБ задаток перечисляется в валютном эквиваленте
                  по курсу НБ РБ на дату платежа на следующие счета: в долларах США (USD) -
                  расчетный счет (IBAN): BY64SLAN30124358151350000000, в российских рублях (RUB)
                  <br/>
                  <br/>
                  - расчетный счет (IBAN): BY64SLAN30124358151350100000 в ЗАО Банк ВТБ (Беларусь),
                  г. Минск, ул. Московская, 14, BIC: SLANBY22. Назначение платежа:
                  «Задаток согласно извещению о проведении аукциона <br/>
                  No______ по лоту No_________».
                  <br/>
                  <br/>
                  «Получатель платежа: ООО «Реалконсалтинг», УНП 191302068».
                </p>
              </div>
            </div>
            <div class="dropout">
              <div @click="toggleDropoutContent(11)" class="dropout-title">
                <span>Возмещение затрат по аукциону</span>
                <chevron-down-icon :class="{ rotated: (showDropoutContent === 11) }"/>
              </div>
              <div :class="{ visible: (showDropoutContent === 11) }" class="content">
                <p>
                  {{ auction.auction.compensation }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import {ChevronDownIcon, XIcon} from 'vue-feather-icons';
import LoadingSpinner from '~/components/LoadingSpinner';
import {Swiper, SwiperSlide} from 'vue-awesome-swiper';
import moment from 'moment';


export default {
  components: {
    ChevronDownIcon,
    XIcon,
    LoadingSpinner,
    Swiper,
    SwiperSlide
  },
  data: () => ({
    // Object and Arrays
    auction: {},
    currentConfirm: {},
    currentConfirmCount: [],
    lotRequests: {},
    auctionItems: [],
    betHistory: [],
    policyAgree: false,
    reglamentAgree: false,
    rulesAgree: false,
    bidError: true,
    price_buy: {},
    suggest: {},
    priceSuggest: {},

    // Statuses
    loading: false,
    canSendRequest: true,
    confirmedRequest: false,
    showDropoutContent: null,
    sliderOpen: false,
    openImage: false,

    // Popups
    toggleParticipationPopup: false,
    toggleMakeBidPopup: false,
    commonRulesOpen: false,
    informationPopupOpen: false,

    // Common
    currentBid: Number,
    moment: moment,
    inputBid: '',

    // Swiper
    swiperOption: {
      // loop: true,
      centeredSlides: true,
      slideToClickedSlide: true,
      // loopedSlides: 5,
      slidesPerView: 3,
      spaceBetween: 15,
      pagination: {
        el: '.images-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      }
    },
    swiperRealIndex: 0,

    // Timers
    distanceEndSelling: new Date().getTime() * 10,
    distanceStart: new Date().getTime() * 10,
    distanceEnd: new Date().getTime() * 10,
    distanceStartSelling: new Date().getTime() * 10,
    polling: null,

    daysStart: null,
    hoursStart: null,
    minutesStart: null,
    secondsStart: null,

    daysEnd: null,
    hoursEnd: null,
    minutesEnd: null,
    secondsEnd: null,

    daysStartSelling: null,
    hoursStartSelling: null,
    minutesStartSelling: null,
    secondsStartSelling: null,

    daysEndSelling: null,
    hoursEndSelling: null,
    minutesEndSelling: null,
    secondsEndSelling: null
  }),
  async fetch() {
    let data = await this.$axios.get(process.env.API_URL + `/admin/api/admin/lot/${this.$route.params.id}`);
    let percentToGet = 5;
    let percent = (percentToGet / 100) * data.data.data.price_start;
    this.price_buy = data.data.data.price_start + percent;
    this.auction = data.data.data;
    data = await this.$axios.get(process.env.API_URL + `/admin/api/admin/lot_item/${this.$route.params.id}`);
    this.auctionItems = data.data.data.data;
    data = await this.$axios.get(process.env.API_URL + `/admin/api/admin/bet/list/${this.$route.params.id}`);
    this.betHistory = data.data.data;
    let suggest = await this.$axios.get(process.env.API_URL + `/admin/api/admin/user/sells/${this.$route.params.id}`);
    this.suggest = suggest.data.data.user_sell_suggest;
    this.priceSuggest = suggest.data.data.price_sell_suggest;
    this.auction.currentUser = this.$store.state.auth.userData.id;
    var count = 0;
    for (let i = 0; i < this.auction.confirms.length; i++) {
      if (this.auction.confirms[i].user_id == this.$store.state.auth.userData.id) {
        this.currentConfirm = this.auction.confirms[i].confirmed_user;
      }
      if (this.auction.confirms[i].confirmed_user == 1) {
        count++
      }
    }
    this.currentConfirmCount = count;
    this.betHistory.sort(function (a, b) {
      return parseFloat(b.bet_amount) - parseFloat(a.bet_amount);
    });
    this.polling = setInterval(() => {
      this.$axios.get(process.env.API_URL + `/admin/api/admin/lot/${this.$route.params.id}`).then((response) => {
        this.auction = response.data.data
      })
    }, 7500)
    //Calculate the percent.
    let now = new Date().getTime()
    this.distanceEndSelling = parseInt(this.auction.auction.end_selling) * 1000 - now;
    this.distanceStartSelling = parseInt(this.auction.auction.start_selling) * 1000 - now;
    this.distanceStart = parseInt(this.auction.auction.starts_at) * 1000 - now;
    this.distanceEnd = parseInt(this.auction.auction.ends_at) * 1000 - now;
    let i = 0
    let j = 0
    let z = 0
    setInterval(() => {
      if (this.auction.status === 'Текущие') {
        if (this.distanceEndSelling > 0) {
          now = new Date().getTime()
          this.distanceEndSelling = parseInt(this.auction.auction.end_selling) * 1000 - now;
          this.daysEndSelling = Math.floor(this.distanceEndSelling / (1000 * 60 * 60 * 24));
          this.hoursEndSelling = Math.floor((this.distanceEndSelling % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          this.minutesEndSelling = Math.floor((this.distanceEndSelling % (1000 * 60 * 60)) / (1000 * 60));
          this.secondsEndSelling = Math.floor((this.distanceEndSelling % (1000 * 60)) / 1000);
        }
        if (this.distanceEndSelling <= 0) {
          while (i < 1) {
            this.auction.status = 'Состоявшиеся'
            this.$axios.$put(process.env.API_URL + `/admin/api/admin/lot/${this.$route.params.id}`, {
              status: 'Состоявшиеся',
            })
            i++;
          }
        }
      }
      if (this.auction.status === 'Предстоящие' || this.auction.status === 'Повторные') {
        if (this.distanceStart > 0) {
          now = new Date().getTime()
          this.distanceStart = parseInt(this.auction.auction.starts_at) * 1000 - now;
          this.daysStart = Math.floor(this.distanceStart / (1000 * 60 * 60 * 24));
          this.hoursStart = Math.floor((this.distanceStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          this.minutesStart = Math.floor((this.distanceStart % (1000 * 60 * 60)) / (1000 * 60));
          this.secondsStart = Math.floor((this.distanceStart % (1000 * 60)) / 1000);
        }
        if (this.distanceEnd > 0) {
          now = new Date().getTime()
          this.distanceEnd = parseInt(this.auction.auction.ends_at) * 1000 - now;
          this.daysEnd = Math.floor(this.distanceEnd / (1000 * 60 * 60 * 24));
          this.hoursEnd = Math.floor((this.distanceEnd % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          this.minutesEnd = Math.floor((this.distanceEnd % (1000 * 60 * 60)) / (1000 * 60));
          this.secondsEnd = Math.floor((this.distanceEnd % (1000 * 60)) / 1000);
        }
        if (this.distanceStartSelling <= 0) {
          if (this.auction.users_count < 2) {
            while (j <= 1) {
              this.auction.status = 'Несостоявшиеся'
              this.$axios.$put(process.env.API_URL + `/admin/api/admin/lot/${this.$route.params.id}`, {
                status: 'Несостоявшиеся',
              })
              j++;
            }
          }
        }
        if (this.distanceEnd <= 0) {
          let needToSubmit = false
          this.lotRequests.forEach(request => {
            if (request.user_id === this.$store.state.auth.userData.id) {
              if (request.confirmed_user === 0) {
                needToSubmit = true
              }
            }
          })
          if (needToSubmit === true) {
            while (z < 1) {
              this.$notify({
                'group': 'user-notifications',
                'title': `<div class='title'>Подтверждение заявки на участие.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
                'text': `Уважаемый пользователь, для подтверждения своего участия в торгах, Вам требуется перейти в аукционный зал текущих торгов.
                Аукционный зал находится в разделе "Покупателю".`,
                'duration': 5000
              })
              this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
                user_id: this.$store.state.auth.userData.id,
                title: 'Подтверждение заявки на участие.',
                text: `Уважаемый пользователь, для подтверждения своего участия в торгах, Вам требуется перейти в аукционный зал текущих торгов.
                Аукционный зал находится в разделе "Покупателю".`
              })
              z++;
            }
          }
        }
        if (this.distanceStartSelling > 0) {
          now = new Date().getTime()
          this.distanceStartSelling = parseInt(this.auction.auction.start_selling) * 1000 - now;
          this.daysStartSelling = Math.floor(this.distanceStartSelling / (1000 * 60 * 60 * 24));
          this.hoursStartSelling = Math.floor((this.distanceStartSelling % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          this.minutesStartSelling = Math.floor((this.distanceStartSelling % (1000 * 60 * 60)) / (1000 * 60));
          this.secondsStartSelling = Math.floor((this.distanceStartSelling % (1000 * 60)) / 1000);
        }
        if (this.distanceStartSelling <= 0) {
          while (j < 1) {
            this.auction.status = 'Текущие'
            this.$axios.$put(process.env.API_URL + `/admin/api/admin/lot/${this.$route.params.id}`, {
              status: 'Текущие',
            })
            j++;
          }
        }
      }
    }, 1000);
    if (this.auction.status === 'Текущие') {
      setInterval(() => {
        // кнопка сделать ставку
        this.$axios.get(process.env.API_URL + `/admin/api/admin/bet/list/${this.$route.params.id}`).then((response) => {
          this.betHistory = response.data.data;
          this.betHistory.sort(function (a, b) {
            return parseFloat(b.bet_amount) - parseFloat(a.bet_amount);
          });
        })
      }, 5000);
    }
    console.log(this.auction)
  },
  fetchOnServer: false,
  computed: {
    countBid() {
      if (this.auction.price_step) {
        if (this.auction.auction.step == 1 && this.auction.step == 2) {
          this.currentBid = (this.auction.price_start * (1 + (this.auction.price_start / 100)));
        } else if (this.auction.auction.step == 2 && this.auction.step == 2) {
          if (this.betHistory.length !== 0) {
            this.currentBid = (parseFloat(this.betHistory[0].bet_amount.replace(/\D/g, '')) * (1 + (this.auction.price_start / 100)));
          } else {
            this.currentBid = (this.auction.price_start * (1 + (this.auction.price_start / 100)));
          }
        } else if (this.auction.auction.step == 2 && this.auction.step == 1) {
          if (this.betHistory.length !== 0) {
            this.currentBid = (this.betHistory[0].bet_amount + parseFloat(this.auction.price_step.replace(/\D/g, '')))
          } else {
            this.currentBid = (this.auction.price_start + parseFloat(this.auction.price_step.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')))
          }

        } else {
          this.currentBid = (this.auction.price_start + parseFloat(this.auction.price_step.replace(/\D/g, '')))
        }
      } else {
        if (this.auction.auction.step == 1 && this.auction.step == 2) {
          if (this.betHistory.length !== 0) {
            this.currentBid = this.betHistory[0].bet_amount + (this.auction.price_start * 0.05);
          } else {
            this.currentBid = (this.auction.price_start * 1.05);
          }
        } else if (this.auction.auction.step == 2 && this.auction.step == 2) {
          if (this.betHistory.length !== 0) {
            this.currentBid = this.betHistory[0].bet_amount * 1.05;
          } else {
            this.currentBid = this.auction.price_start * 1.05;
          }
        } else if (this.auction.auction.step == 1 && this.auction.step == 1) {
          if (this.betHistory.length !== 0) {
            this.currentBid = this.betHistory[0].bet_amount + (this.auction.price_start * 1.05);
          } else {
            this.currentBid = this.auction.price_start + (this.auction.price_start * 1.05);
          }
        } else {
          if (this.betHistory.length !== 0) {
            this.currentBid = this.betHistory[0].bet_amount + (this.betHistory[0].bet_amount * 1.05);
          } else {
            this.currentBid = this.auction.price_start + (this.auction.price_start * 1.05);
          }
        }
      }
    },
  },
  methods: {
    countUserBid() {
      this.inputBid = this.inputBid.replace(/[^.\d]/g, '').replace(/^(\d*\.?)|(\d*)\.?/g, "$1$2")
      if (this.inputBid !== '') {
        if (parseFloat(this.inputBid) > this.currentBid) {
          this.bidError = false
        } else {
          this.bidError = true
        }
      }
    },
    changeSlide(action) {
      if (action === 'next') {
        this.$refs.swiper.$swiper.slideNext()
      }
      if (action === 'prev') {
        this.$refs.swiper.$swiper.slidePrev()
      }
    },
    slideChanged() {
      this.swiperRealIndex = this.$refs.swiper.$swiper.realIndex
    },
    openParticipationPopup() {
      this.toggleParticipationPopup = !this.toggleParticipationPopup
      // document.body.classList.toggle("stop-scrolling");
    },
    submitParticipation() {
      if (this.auction.auction.type !== 'classic') {
        if (this.policyAgree === true && this.rulesAgree === true && this.reglamentAgree === true && this.auction.auction.seller_id != this.$store.state.auth.userData.id) {
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/auctionConfirm', {
            user_id: this.$store.state.auth.userData.id,
            lot_id: this.auction.id,
            current_auction: this.auction.auction.seller_id,
          });
          // this.$notify({
          //   'group': 'user-notifications',
          //   'title': `<div class='title'>Невозможно подать заявку.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          //   'text': 'Вы не подтвердили согласие с Регламентом, с Публичной афертой, а также не подтвердили, что вы не являетесь должником, АУ, организатором и оператором данной площадки.',
          // })
          this.toggleParticipationPopup = false
          this.canSendRequest = false
          this.$notify({
            'group': 'user-notifications',
            'title': `<div class='title'>Заявка на участие в лоте №${this.auction.lot_number}.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
            'text': 'Ваша заявка на участие была принята. В скором времени администратор площадки её рассмотрит.',
            'duration': 5000
          })
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
            user_id: this.$store.state.auth.userData.id,
            title: `Заявка на участие в лоте №${this.auction.lot_number}.`,
            text: 'Ваша заявка на участие была принята. В скором времени администратор площадки её рассмотрит.'
          })
          this.$axios.$get(process.env.API_URL + `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
            .then((response) => {
              this.$store.dispatch('getNotifications', {
                notifications: response.data.data
              })
              this.$store.dispatch('countUnreadNotifications')
            })
          this.informationPopupOpen = true
        } else {
          this.$notify({
            'group': 'user-notifications',
            'title': `<div class='title'>Невозможно подать заявку.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
            'text': 'Вы не подтвердили согласие с Регламентом, с Публичной афертой, а также не подтвердили, что вы не являетесь должником, АУ, организатором и оператором данной площадки.',
            'duration': 5000
          })
        }
      } else {
        if (this.policyAgree === true && this.reglamentAgree === true) {
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/auctionConfirm', {
            user_id: this.$store.state.auth.userData.id,
            lot_id: this.auction.id,
          })
          this.toggleParticipationPopup = false
          this.canSendRequest = false
          this.$notify({
            'group': 'user-notifications',
            'title': `<div class='title'>Заявка на участие в лоте №${this.auction.lot_number}.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
            'text': 'Ваша заявка на участие была принята. В скором времени администратор площадки её рассмотрит.',
            'duration': 5000
          })
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
            user_id: this.$store.state.auth.userData.id,
            title: `Заявка на участие в лоте №${this.auction.lot_number}.`,
            text: 'Ваша заявка на участие была принята. В скором времени администратор площадки её рассмотрит.'
          })
          this.$axios.$get(process.env.API_URL + `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
            .then((response) => {
              this.$store.dispatch('getNotifications', {
                notifications: response.data.data
              })
              this.$store.dispatch('countUnreadNotifications')
            })
          this.informationPopupOpen = true
        } else {
          this.$notify({
            'group': 'user-notifications',
            'title': `<div class='title'>Невозможно подать заявку.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
            'text': 'Вы не подтвердили согласие с Регламентом и с Публичной афертой.',
            'duration': 5000
          })
        }
      }
    },
    makeBid() {
      // действие кнопки ставить ставку
      if (this.betHistory.length !== 0 && this.betHistory[0].user_id === this.$store.state.auth.userData.id) {
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Невозможно сделать ставку.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          'text': 'Вы не можете сделать ставку 2 раза подряд.',
          'duration': 5000
        })
      } else {
        if (this.bidError === true) {
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/bet', {
            user_id: this.$store.state.auth.userData.id,
            lot_id: this.auction.id,
          })
        } else {
          this.$axios.$post(process.env.API_URL + '/admin/api/admin/bet', {
            user_id: this.$store.state.auth.userData.id,
            lot_id: this.auction.id,
            bet: this.inputBid
          })
        }
        this.toggleMakeBidPopup = false;
        this.$axios.get(process.env.API_URL + `/admin/api/admin/bet/list/${this.$route.params.id}`)
          .then((response) => {
            this.betHistory = response.data.data
            this.betHistory.sort(function (a, b) {
              return parseFloat(b.bet_amount) - parseFloat(a.bet_amount);
            });
          })
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Ставка принята.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          'text': `Ваша ставка на лот №${this.auction.lot_number} была принята.`,
          'duration': 5000
        })
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/notification', {
          user_id: this.$store.state.auth.userData.id,
          title: 'Ставка ' + this.inputBid + 'бел. р. - принята.',
          text: `Ваша ставка на лот №${this.auction.lot_number} была принята.`
        })
        this.$axios.$get(process.env.API_URL + `/admin/api/admin/user/notifications/${this.$store.state.auth.userData.id}`)
          .then((response) => {
            this.$store.dispatch('getNotifications', {
              notifications: response.data.data
            })
            this.$store.dispatch('countUnreadNotifications')
          })
      }

    },
    makeSell() {
      // действие кнопки покупка
      if (this.currentConfirm == 1 && this.currentConfirmCount == 1) {
        this.$axios.$post(process.env.API_URL + '/admin/api/admin/user/sells', {
          user_sell_suggest: this.$store.state.auth.userData.id,
          lot_id: this.$route.params.id,
          price_sell_suggest: this.price_buy
        })
        this.$fetch();
        this.$notify({
          'group': 'user-notifications',
          'title': `<div class='title'>Успешная покупка.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
          'text': 'Покупка успешно реализовано',
          'duration': 5000
        });

      }
    },
    toggleDropoutContent(index) {
      if (this.showDropoutContent === index) {
        this.showDropoutContent = null
      } else {
        this.showDropoutContent = index
      }
    },
    isCurrentuser(event) {
      return event.user_id === this.$store.state.auth.userData.id;
    },
  },
  mounted() {
    let userId = this.$store.state.auth.userData.id
    this.$axios.get(process.env.API_URL + `/admin/api/admin/lot/confirms/${this.$route.params.id}`).then((response) => {
      this.lotRequests = response.data.data
      this.lotRequests.find((request) => {
        if (request.user_id == userId) {
          this.canSendRequest = false
          if (request.confirmed_admin == 1) {
            this.confirmedRequest = true
          }
        }
      })
    })
  },
  beforeDestroy() {
    clearInterval(this.polling)
  },
  head() {
    return {
      title: this.auction.name,
    }
  },
}
</script>
<style lang="scss">
@import "~/assets/scss/common.scss";

.main-title {
  text-align: center;
  margin-bottom: 3rem;
  font-size: 2rem;
}

.winner-lot {
  border-top: 1px solid;
  padding: 10px;
  font-size: 19px;
}

.tabs-component {
  width: 100%;
  text-align: center;

  .tabs-component-tabs {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;

    .tabs-component-tab {
      width: 100%;
      padding: 10px 0;

      .tabs-component-tab-a {
        flex: 1 0 auto;
        margin: 0 0 30px;
        padding: 1rem;
        font-weight: bold;
        transition: 100ms linear all;
        cursor: pointer;
      }
    }

    .tabs-component-tab.is-active {
      color: #1279e0;
      border-bottom: 3px solid #1279e0;
    }
  }
}

.stop-scrolling {
  height: 100%;
  overflow: hidden;
}

.open-image {
  width: 100vw;
  height: 100vh;
  position: fixed;
  padding: 4rem;
  left: 0;
  top: 0;
  background-color: rgba(0, 0, 0, 0.8);
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;

  .close-button {
    position: absolute;
    right: 2rem;
    top: 2rem;
    cursor: pointer;
  }

  .open-image-wrapper {
    .swiper-button-prev {
      margin-left: 3rem;
    }

    .swiper-button-next {
      margin-right: 3rem;
    }
  }
}

.wrapper {
  display: flex;
  justify-content: space-between;
  padding: 0 1rem;
  margin: 0 auto;
  margin-bottom: 3rem;

  &-left {
    width: 48%;
    display: flex;
    flex-direction: column;
    position: relative;

    .choosen-image {
      height: 20rem;
      width: 100%;
      margin-bottom: 1rem;
      cursor: pointer;

      img {
        height: 100%;
        width: 100%;
        object-fit: cover;
      }
    }

    .images-wrapper {
      overflow: hidden;
      margin-bottom: 1rem;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);

      .images {
        width: 100%;
        overflow: visible;

        .swiper-wrapper {
          width: 100%;
          margin-left: -269px;

          .swiper-slide {
            opacity: 0.5;
            transition: .2s ease-in-out;
            margin-right: 10px !important;
            max-width: 225px;

            img {
              object-fit: cover;
              height: 8rem;
              cursor: pointer;
            }
          }

          .swiper-slide-active {
            opacity: 1;
          }
        }
      }

      .swiper-button-next {
        opacity: 0.75;
        transform: translateY(-50%);
      }

      .swiper-button-prev {
        opacity: 0.75;
        transform: translateY(-50%);
      }

      .swiper-button-next::after {
        font-size: 2rem;
      }

      .swiper-button-prev::after {
        font-size: 2rem;
      }
    }

    .users-bids {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: #fff;
      padding: 1rem 1rem;
      border-radius: 10px;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);

      .title {
        font-size: 1.5rem;
        font-weight: 500;
        text-align: center;
        margin-bottom: 1rem;
      }

      .bid-table {
        width: 100%;
        max-height: 35rem;
        overflow-y: scroll;
        padding: 0.5rem 0;
        padding-right: 10px;

        .no-events {
          width: 100%;
          display: flex;
          justify-content: center !important;
          align-items: center;
          text-align: center;
        }

        .row:first-child {
          border-top: none;

          span {
            opacity: 0.6;
          }
        }

        .row {
          display: flex;
          justify-content: space-between;
          text-align: center;
          align-items: center;
          padding: 0.75rem 0;
          border-top: 1px solid #dddddd;

          .time {
            width: 5rem;
          }

          .current-username {
            font-weight: bold;
          }

          .price {
            width: 8rem;
          }
        }
      }
    }
  }

  &-right {
    width: 50%;

    .lot-title {
      background-color: #fff;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);
    }

    .title {
      line-height: 1.25;
      padding-bottom: 1rem;
      border-bottom: 1px solid $border-color;
    }

    .subtitle {
      display: flex;
      border-bottom: 1px solid $border-color;
      padding-bottom: 1rem;

      .participants-count {
        margin-left: 2rem;
        display: flex;
        align-items: center;
        user-select: none;

        span {
          margin-left: 0.5rem;
        }
      }

      .starts-at {
        margin-left: 2rem;
        display: flex;
        align-items: center;
        user-select: none;

        span {
          margin-left: 0.5rem;
        }
      }
    }

    .info {
      &-wrapper {
        display: flex;
        padding-top: 0.25rem;

        .articles {
          display: flex;
          flex-direction: column;
          width: 100%;

          .article {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 0.25rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid $border-color;

            &-param {
              font-weight: 400;
              color: $text-color-2;
              margin-right: 2rem;
              width: 40%;
            }

            &-value {
              width: 50%;

              .auction-link {
                color: $link-color;
              }
            }
          }

          .rules {
            color: $link-color;
            display: flex;
            justify-content: center;
            border-bottom: none;
            margin-top: 0.5rem;
            cursor: pointer;

            .article-param {
              text-align: center;
              width: 100%;
              color: $link-color;
            }
          }
        }
      }

      &-wrapper-mobile {
        display: none;
      }
    }

    .timer {
      background-color: $link-color;
      width: fit-content;
      padding: 2rem 1rem;
      border-radius: 10px;
      display: flex;
      color: #fff;
      margin: 0 auto 2rem auto;
      text-align: center;
      width: 100%;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);

      .article {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;

        &-param {
          font-size: 1.2rem;
          font-weight: 600;
          margin-bottom: 0.5rem;
        }

        &-value {
          padding: 1rem 0;
          border: 2px solid #fff;
          display: flex;
          justify-content: center;
          width: fit-content;

          .timer-element {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 5.5rem;
            border-right: 1px dashed #fff;

            .number {
              font-size: 2.4rem;
              font-weight: 800;
            }
          }

          .timer-element:last-child {
            border-right: none;
          }
        }
      }
    }

    .price {
      display: flex;
      flex-direction: column;
      background-color: #fff;
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 2rem;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);

      .price-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        margin-top: 1rem;

        .price-left {
          width: 50%;

          .current-price {
            font-size: 1.75rem;
            font-weight: 500;
          }

          span {
            margin-top: 2rem;
            font-size: .75rem;
            opacity: 0.5;
          }
        }

        .price-right {
          width: 50%;
          text-align: center;

          button {
            margin-top: 1rem;
            padding: 0.45rem 1rem;
            color: #ffffff;
            background-color: #191919;
            border: 2px solid #191919;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            margin-bottom: 0.5rem;
            transition: .2s ease-in-out;
            box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
            0 6.7px 5.3px rgba(0, 0, 0, 0.048),
            0 12.5px 10px rgba(0, 0, 0, 0.06),
            0 22.3px 17.9px rgba(0, 0, 0, 0.072),
            0 41.8px 33.4px rgba(0, 0, 0, 0.086),
            0 100px 80px rgba(0, 0, 0, 0.12);
          }

          button:hover {
            box-shadow: none;
            color: #191919;
            background-color: #ffffff;
          }
        }
      }

      .price-info {
        display: flex;
        justify-content: space-between;

        .price-top {
          display: flex;
          margin-bottom: 1rem;

          .price-start {
            display: flex;
            flex-direction: column;
            align-items: center;
          }

          .price-min {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
            justify-content: center;
            align-items: center;
          }

          .value {
            width: fit-content;
            background-color: #777777;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 500;
            padding: 0.3rem 0.8rem;
          }

          .label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.2rem;
          }
        }

        .price-bottom {
          display: flex;
          justify-content: center;
          width: 50%;
          margin-right: 22px;

          .price-deposit {
            display: flex;
            flex-direction: column;
            align-items: center;
          }

          .price-step {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
            align-items: center;
          }

          .price-buy {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
          }

          .value {
            background-color: #777777;
            width: fit-content;
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.3rem 0.8rem;
          }

          .label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.2rem;
          }

          @media (max-width: 476px) {
            width: 100%;
            margin: 0;
          }
        }
      }
    }

    .dropout-list {
      background-color: #fff;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.034),
      0 6.7px 5.3px rgba(0, 0, 0, 0.048),
      0 12.5px 10px rgba(0, 0, 0, 0.06),
      0 22.3px 17.9px rgba(0, 0, 0, 0.072),
      0 41.8px 33.4px rgba(0, 0, 0, 0.086),
      0 100px 80px rgba(0, 0, 0, 0.12);

      .title {
        border: none;
      }

      .dropout {
        .dropout-title {
          display: flex;
          justify-content: space-between;
          align-items: center;
          font-size: 1.1rem;
          border-top: 1px solid $border-color;
          cursor: pointer;
          padding: 0 1rem;
          user-select: none;

          span {
            padding: 1.5rem 0;
            max-width: 85%;
          }

          svg {
            height: 2rem;
            width: 2rem;
            transition: .25s ease-in-out;
          }

          .rotated {
            transform: rotate(180deg);
          }
        }

        .content {
          padding: 0 1rem;
          overflow: hidden;
          display: none;
          transition: all .25s ease-in-out;

          p {
            opacity: 0.6;
          }

          .seller {
            color: $text-color-2;
            display: flex;
            flex-direction: column;

            .seller-value {
              color: $text-color-1;
            }
          }

          .lot-item {
            margin-bottom: 1rem;
            border-bottom: 1px solid $border-color;
            padding-bottom: 1rem;

            &-title {
              font-size: 1.1rem;
              font-weight: 600;
              margin-bottom: 1rem;
            }

            &-description {
              margin-bottom: 1rem;
            }

            .images {
              .swiper-wrapper {
                .swiper-slide {
                  img {
                    height: 11rem;
                    cursor: pointer;
                  }
                }
              }
            }
          }

          .lot-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
          }

          .list {
            list-style: disc;

            li {
              margin-left: 2rem;
              color: $link-color;

              &:hover {
                text-decoration: underline;
              }
            }
          }
        }

        .visible {
          padding: 0 1rem 1rem 1rem;
          transition: all .25s ease-in-out;
          display: block;
        }
      }

      .dropout:first-child {
        .dropout-title {
          border-top: none;
        }
      }
    }
  }
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

.make-bid-popup {
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

  .make-bid-popup-form {
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
      margin-bottom: 0.25rem;
    }

    .subtitle {
      font-size: 0.9rem;
      margin-bottom: 1rem;
      color: $text-color-2;

      .subtitle-bid {
        color: $text-color-1;
        font-weight: 600;
      }
    }

    .bid-input {
      width: 10rem;
      margin-bottom: 1rem;
    }

    .warning {
      p {
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
      }
    }

    .make-bid-popup-buttons {
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

.common-rules-popup {
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
  color: #000;

  .common-rules-wrapper {
    position: relative;
    max-width: 60rem;
    padding: 2rem;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.024),
    0 6.7px 5.3px rgba(0, 0, 0, 0.038),
    0 12.5px 10px rgba(0, 0, 0, 0.05),
    0 22.3px 17.9px rgba(0, 0, 0, 0.062),
    0 41.8px 33.4px rgba(0, 0, 0, 0.066),
    0 100px 80px rgba(0, 0, 0, 0.08);

    .button-close {
      position: absolute;
      right: 1rem;
      top: 1rem;
      cursor: pointer;

    }

    .title {
      margin-bottom: 2rem;
      font-size: 2rem;
      text-align: center;
    }

    .rules {
      .rule {
        margin-bottom: 0.5rem;
        opacity: 0.9;
      }
    }
  }
}

.information-popup {
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
  color: #000;

  .information-wrapper {
    position: relative;
    max-width: 50rem;
    padding: 2rem;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2.8px 2.2px rgba(0, 0, 0, 0.024),
    0 6.7px 5.3px rgba(0, 0, 0, 0.038),
    0 12.5px 10px rgba(0, 0, 0, 0.05),
    0 22.3px 17.9px rgba(0, 0, 0, 0.062),
    0 41.8px 33.4px rgba(0, 0, 0, 0.066),
    0 100px 80px rgba(0, 0, 0, 0.08);

    .button-close {
      position: absolute;
      right: 1rem;
      top: 1rem;
      cursor: pointer;
    }

    .title {
      margin-bottom: 1rem;
      font-size: 2rem;
      text-align: center;
      padding-bottom: 1rem;
      border-bottom: 1px solid $border-color;
    }

    .warning {
      font-size: 2rem;
      font-weight: 700;
    }

    .text {
      p {
        margin: 0.5rem 0;
      }
    }
  }
}

@media (max-width: 1024px) {
  .wrapper {
    flex-direction: column;

    &-left {
      width: 100%;
      padding-right: 0;

      .choosen-image {
        height: 26rem;
      }

      .users-bids {
        margin-bottom: 2rem;
      }
    }

    &-right {
      width: 100%;
      z-index: 5;
    }
  }
}

@media (max-width: 534px) {
  .wrapper {
    padding: 0;

    &-left {
      width: 100%;
      padding-right: 0;

      .users-bids {
        margin-bottom: 2rem;
      }

      .choosen-image {
        height: 20rem;
      }
    }

    &-right {
      width: 100%;
      z-index: 5;

      .lot-title {
        .info-wrapper {
          display: none;
        }

        .info-wrapper-mobile {
          display: flex;
          flex-direction: column;
          margin-top: 1rem;

          .article {
            display: flex;
            flex-direction: column;
            text-align: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid $border-color;

            .article-title {
              color: $text-color-2;
              font-size: 0.9rem;
            }
          }

          .rules {
            padding: 0;
            margin: 0;
            border: none;

            span {
              color: $link-color !important;
              font-size: 1rem !important;
            }
          }
        }
      }

      .price {
        .price-wrapper {
          flex-direction: column;

          .price-left {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;

            span {
              margin-top: 0;
            }
          }

          .price-right {
            width: 100%;
            margin-top: 1rem;
            display: flex;
            justify-content: center;
          }
        }

        &-info {
          flex-direction: column;

          .price-top {
            justify-content: center;
            text-align: center;

            .price-start {
              align-items: center;
            }

            .price-min {
              align-items: center;
            }
          }

          .price-bottom {
            justify-content: center;
            text-align: center;
            margin-left: 96px;

            .price-deposit {
              align-items: center;
            }

            .price-step {
              align-items: center;
            }

            .price-buy {
              align-items: center;
            }
          }
        }
      }
    }
  }
}

.popup-pre-wrapper {
  @media (max-width: 500px) {
    max-width: 80%;
    margin: 0 auto;
  }
}

.common-rules-popup {
  @media (max-width: 500px) {
    overflow-y: scroll;
    display: block;
  }
}

.wrapper-left .images-wrapper .images .swiper-wrapper {
  @media (max-width: 500px) {
    margin-left: 10px;
  }
}
</style>
