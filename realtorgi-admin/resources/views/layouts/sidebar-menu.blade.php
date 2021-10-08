<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      {{-- <li class="nav-item">
        <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
            Главная
          </p>
        </router-link>
      </li> --}}

      {{-- <li class="nav-item">
        <router-link to="/products" class="nav-link">
          <i class="nav-icon fas fa-list orange"></i>
          <p>
            Product
          </p>
        </router-link>
      </li> --}}

      @can('isAdmin')
        <li class="nav-item">
            <router-link to="/auctionConfirms" class="nav-link">
            <i class="nav-icon fas fa-list orange"></i>
            <p>
                Заявки на участие
            </p>
            </router-link>
        </li>


        <li class="nav-item">
            <router-link to="/auctions" class="nav-link">
            <i class="nav-icon fas fa-list orange"></i>
            <p>
                Аукционы
            </p>
            </router-link>
        </li>


        <li class="nav-item">
            <router-link to="/lots" class="nav-link">
            <i class="nav-icon fas fa-list orange"></i>
            <p>
                Лоты
            </p>
            </router-link>
        </li>

        <li class="nav-item">
            <router-link to="/bets" class="nav-link">
            <i class="nav-icon fas fa-list orange"></i>
            <p>
                Ставки
            </p>
            </router-link>
        </li>
      @endcan

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="fa fa-users nav-icon blue"></i>
            <p>Пользователи</p>
          </router-link>
        </li>
        <li class="nav-item">
            <router-link to="/change_users" class="nav-link">
              <i class="fa fa-users nav-icon blue"></i>
              <p>Изменения данных</p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/messages" class="nav-link">
              <i class="nav-icon fas fa-list orange"></i>
              <p>
                Сообщения
                @if(DB::table('contacts')->where('status', 'new')->count() > 0)
                    <span style="color: orange;">
                        ({{ DB::table('contacts')->where('status', 'new')->count() }})
                    </span>
                @endif
              </p>
            </router-link>
          </li>
      @endcan

      @can('isAdmin')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-cog green"></i>
          <p>
            Настройки
            @if(DB::table('feedback')->where('status', 'new')->count() > 0 || DB::table('notifications')->where('status', 'new')->count() > 0)
                <span style="color: orange;">
                    ({{ DB::table('feedback')->where('status', 'new')->count() + DB::table('notifications')->where('status', 'new')->count() }})
                </span>
            @endif
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/categories" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Категории
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/countries" class="nav-link">
              <i class="nav-icon fas fa-tags green"></i>
              <p>
                Области
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/feedback" class="nav-link">
              <i class="nav-icon fas fa-list orange"></i>
              <p>
                Организация
                @if(DB::table('feedback')->where('status', 'new')->count() > 0)
                    <span style="color: orange;">
                        ({{ DB::table('feedback')->where('status', 'new')->count() }})
                    </span>
                @endif
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/notifications" class="nav-link">
              <i class="nav-icon fas fa-list orange"></i>
              <p>
                Уведомления
                @if(DB::table('notifications')->where('status', 'new')->count() > 0)
                    <span style="color: orange;">
                        ({{ DB::table('notifications')->where('status', 'new')->count() }})
                    </span>
                @endif
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/export_data" class="nav-link">
              <i class="nav-icon fas fa-list orange"></i>
              <p>
                Архивы по лотам
              </p>
            </router-link>
          </li>

            {{-- <li class="nav-item">
              <router-link to="/developer" class="nav-link">
                  <i class="nav-icon fas fa-cogs white"></i>
                  <p>
                      Developer
                  </p>
              </router-link>
            </li> --}}
        </ul>
      </li>

      @endcan



      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Выход') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>
